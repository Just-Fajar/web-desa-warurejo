<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriImage;
use App\Http\Requests\GaleriRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
    public function index()
    {
        $galeri = Galeri::with(['admin', 'images'])->latest()->get(); // Eager load admin and images to prevent N+1
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
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            
            // Handle image update
            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($galeri->gambar) {
                    $this->imageUploadService->delete($galeri->gambar);
                }
                
                // Upload new image
                $data['gambar'] = $this->imageUploadService->upload(
                    $request->file('gambar'), 
                    'galeri'
                );
            }
            
            $galeri->update($data);
            
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
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            // Delete image
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
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
                    'message' => 'Tidak ada galeri yang dipilih'
                ], 400);
            }
            
            $galeriList = Galeri::whereIn('id', $ids)->get();
            
            // Delete image files
            foreach ($galeriList as $galeri) {
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
                'message' => count($ids) . ' galeri berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status aktif/non-aktif galeri (soft hide)
     * Untuk hide/show di public tanpa delete permanent
     * Return JSON response untuk AJAX
     * Route: POST /admin/galeri/{id}/toggle-active
     */
    public function toggleActive(Galeri $galeri)
    {
        try {
            $galeri->is_active = !$galeri->is_active;
            $galeri->save();
            
            // Clear cache
            Cache::forget('home.galeri');
            Cache::forget('profil_desa');
            
            $status = $galeri->is_active ? 'aktif' : 'non-aktif';
            
            return response()->json([
                'success' => true,
                'message' => "Status galeri berhasil diubah menjadi {$status}",
                'is_active' => $galeri->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
