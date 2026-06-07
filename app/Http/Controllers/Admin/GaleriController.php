<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GaleriRequest;
use App\Models\Galeri;
use App\Models\GaleriImage;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    protected $imageUploadService;

    /**
     * Constructor - Inject ImageUploadService
     * Controller untuk handle HTTP requests galeri/foto di admin panel
     */
    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Tampilkan list semua galeri
     * Eager load admin dan images untuk prevent N+1 query
     * Route: GET /admin/galeri
     */
    public function index(Request $request)
    {
        $query = Galeri::with(['admin', 'images']);

        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $galeri = $query->latest()->paginate(12);

        return view('admin.galeri.index', compact('galeri'));
    }

    /**
     * Tampilkan form create galeri baru
     * Route: GET /admin/galeri/create
     */
    public function create()
    {
        return view('admin.galeri.create');
    }

    /**
     * Simpan galeri baru dengan multiple images
     * - Validate input via GaleriRequest
     * - Upload semua gambar ke storage
     * - Simpan path gambar ke tabel galeri_images
     * - Gunakan DB transaction untuk rollback jika gagal
     * Route: POST /admin/galeri
     */
    public function store(GaleriRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['admin_id'] = auth('admin')->id();

            // Remove 'images' from data since it's handled separately
            unset($data['images']);

            // Handle status & published_at
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            } elseif ($data['status'] === 'draft') {
                $data['published_at'] = null;
            }

            // Create galeri record
            $galeri = Galeri::create($data);

            // Handle multiple images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $this->imageUploadService->upload($image, 'galeri');

                    GaleriImage::create([
                        'galeri_id' => $galeri->id,
                        'image_path' => $imagePath,
                        'urutan' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Tampilkan detail galeri dengan semua foto-fotonya
     * Route: GET /admin/galeri/{id}
     */
    public function show(Galeri $galeri)
    {
        return view('admin.galeri.show', compact('galeri'));
    }

    /**
     * Tampilkan form edit galeri
     * Route: GET /admin/galeri/{id}/edit
     */
    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    /**
     * Update galeri yang sudah ada
     * - Jika ada gambar baru, delete gambar lama lalu upload baru
     * - Clear cache setelah update
     * Route: PUT /admin/galeri/{id}
     */
    public function update(GaleriRequest $request, Galeri $galeri)
    {
        try {
            $data = $request->validated();

            // Extract new images from validated request data
            $newImages = $request->file('images', []);
            unset($data['images']);

            // Handle status & published_at BEFORE update
            if ($data['status'] === 'published' && ! $galeri->published_at) {
                $data['published_at'] = now();
            } elseif ($data['status'] === 'draft') {
                $data['published_at'] = null;
            }
            // scheduled: published_at comes from form input

            $galeri->update($data);

            // Handle uploading new images
            if (!empty($newImages)) {
                $maxUrutan = $galeri->images()->max('urutan') ?? 0;
                foreach ($newImages as $index => $image) {
                    $imagePath = $this->imageUploadService->upload($image, 'galeri');

                    GaleriImage::create([
                        'galeri_id' => $galeri->id,
                        'image_path' => $imagePath,
                        'urutan' => $maxUrutan + $index + 1,
                    ]);
                }
            }

            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Delete galeri beserta gambarnya dari storage
     * Clear cache setelah delete
     * Route: DELETE /admin/galeri/{id}
     */
    public function destroy(Galeri $galeri)
    {
        try {
            // Delete multiple images associated with this gallery
            foreach ($galeri->images as $image) {
                $this->imageUploadService->delete($image->image_path);
            }

            // Delete old single image if exists
            if ($galeri->gambar) {
                $this->imageUploadService->delete($galeri->gambar);
            }

            $galeri->delete();

            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.galeri.index')
                ->with('success', 'Galeri berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Bulk delete multiple galeri sekaligus
     * - Loop semua galeri dan delete gambarnya dari storage
     * - Return JSON response untuk AJAX
     * Route: POST /admin/galeri/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada galeri yang dipilih',
                ], 400);
            }

            $galeriList = Galeri::with('images')->whereIn('id', $ids)->get();

            // Delete image files
            foreach ($galeriList as $galeri) {
                foreach ($galeri->images as $image) {
                    $this->imageUploadService->delete($image->image_path);
                }
                if ($galeri->gambar) {
                    $this->imageUploadService->delete($galeri->gambar);
                }
            }

            // Delete records
            Galeri::whereIn('id', $ids)->delete();

            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');

            return response()->json([
                'success' => true,
                'message' => count($ids).' galeri berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX: Hapus satu foto galeri
     * Route: DELETE /admin/galeri/foto/{id}
     */
    public function deleteFoto($id)
    {
        try {
            $foto = GaleriImage::findOrFail($id);
            $this->imageUploadService->delete($foto->image_path);
            $foto->delete();

            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: '.$e->getMessage(),
            ], 500);
        }
    }
}
