<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotensiDesa;
use App\Models\PotensiDesaFoto;
use App\Http\Requests\PotensiRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PotensiController extends Controller
{
    protected $imageUploadService;

    /**
     * Constructor - Inject ImageUploadService
     * Controller untuk handle HTTP requests potensi desa di admin panel
     */
    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Tampilkan list semua potensi desa
     * Sort by created_at descending (terbaru dulu)
     * Route: GET /admin/potensi
     */
    public function index()
    {
        $potensi = PotensiDesa::orderBy('created_at', 'desc')->get();
        return view('admin.potensi.index', compact('potensi'));
    }

    /**
     * Tampilkan form create potensi baru
     * Route: GET /admin/potensi/create
     */
    public function create()
    {
        return view('admin.potensi.create');
    }

    /**
     * Simpan potensi baru ke database
     * - Auto-generate slug dari nama
     * - Handle status & published_at
     * - Upload gambar utama
     * - Upload foto galeri (multiple)
     * - Clear cache homepage
     * Route: POST /admin/potensi
     */
    public function store(PotensiRequest $request)
    {
        try {
            $data = $request->validated();

            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            } elseif ($data['status'] === 'draft') {
                $data['published_at'] = null;
            }

            // Handle image upload
            if ($request->hasFile('gambar')) {
                $data['gambar'] = $this->imageUploadService->upload(
                    $request->file('gambar'),
                    'potensi'
                );
            }

            // Remove foto_galeri from data before creating
            $fotoGaleri = $request->file('foto_galeri', []);
            unset($data['foto_galeri']);

            $potensi = PotensiDesa::create($data);

            // Handle foto galeri upload
            if (!empty($fotoGaleri)) {
                foreach ($fotoGaleri as $index => $file) {
                    $filename = time() . '_galeri_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('potensi/galeri', $filename, 'public');
                    
                    PotensiDesaFoto::create([
                        'potensi_desa_id' => $potensi->id,
                        'foto' => $path,
                        'urutan' => $index + 1,
                    ]);
                }
            }

            // Clear cache
            Cache::forget('home.potensi');
            Cache::forget('home.total_potensi');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.potensi.index')
                ->with('success', 'Potensi berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail potensi (preview)
     * Route: GET /admin/potensi/{id}
     */
    public function show(PotensiDesa $potensi)
    {
        $potensi->load('fotoGaleri');
        return view('admin.potensi.show', compact('potensi'));
    }

    /**
     * Tampilkan form edit potensi
     * Route: GET /admin/potensi/{id}/edit
     */
    public function edit(PotensiDesa $potensi)
    {
        $potensi->load('fotoGaleri');
        return view('admin.potensi.edit', compact('potensi'));
    }

    /**
     * Update potensi yang sudah ada
     * - Handle status & published_at
     * - Update slug jika nama berubah
     * - Jika ada gambar baru, delete lama lalu upload baru
     * - Handle foto galeri baru
     * - Clear cache setelah update
     * Route: PUT /admin/potensi/{id}
     */
    public function update(PotensiRequest $request, PotensiDesa $potensi)
    {
        try {
            $data = $request->validated();

            // Handle status & published_at
            if ($data['status'] === 'published' && !$potensi->published_at) {
                $data['published_at'] = now();
            } elseif ($data['status'] === 'draft') {
                $data['published_at'] = null;
            }

            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($potensi->gambar) {
                    $this->imageUploadService->delete($potensi->gambar);
                }

                // Upload new image
                $data['gambar'] = $this->imageUploadService->upload(
                    $request->file('gambar'),
                    'potensi'
                );
            }

            // Remove foto_galeri from data before updating
            $fotoGaleri = $request->file('foto_galeri', []);
            unset($data['foto_galeri']);

            $potensi->update($data);

            // Handle foto galeri upload
            if (!empty($fotoGaleri)) {
                $maxUrutan = $potensi->fotoGaleri()->max('urutan') ?? 0;
                foreach ($fotoGaleri as $index => $file) {
                    $filename = time() . '_galeri_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('potensi/galeri', $filename, 'public');

                    PotensiDesaFoto::create([
                        'potensi_desa_id' => $potensi->id,
                        'foto' => $path,
                        'urutan' => $maxUrutan + $index + 1,
                    ]);
                }
            }

            // Clear cache
            Cache::forget('home.potensi');
            Cache::forget('home.total_potensi');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.potensi.index')
                ->with('success', 'Potensi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete potensi beserta gambar dan foto galeri dari storage
     * Clear cache setelah delete
     * Route: DELETE /admin/potensi/{id}
     */
    public function destroy(PotensiDesa $potensi)
    {
        try {
            // Delete main image
            if ($potensi->gambar) {
                $this->imageUploadService->delete($potensi->gambar);
            }

            // Delete gallery photos from storage
            foreach ($potensi->fotoGaleri as $foto) {
                Storage::disk('public')->delete($foto->foto);
            }

            $potensi->delete();

            // Clear cache
            Cache::forget('home.potensi');
            Cache::forget('home.total_potensi');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.potensi.index')
                ->with('success', 'Potensi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Hapus satu foto galeri
     * Route: DELETE /admin/potensi/foto/{id}
     */
    public function deleteFoto($id)
    {
        try {
            $foto = PotensiDesaFoto::findOrFail($id);
            Storage::disk('public')->delete($foto->foto);
            $foto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete multiple potensi sekaligus
     * - Loop semua dan delete gambar + foto galeri dari storage
     * - Return JSON response untuk AJAX
     * Route: POST /admin/potensi/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada potensi yang dipilih'
                ], 400);
            }

            $potensiList = PotensiDesa::with('fotoGaleri')->whereIn('id', $ids)->get();

            // Delete images & gallery photos
            foreach ($potensiList as $potensi) {
                if ($potensi->gambar) {
                    $this->imageUploadService->delete($potensi->gambar);
                }
                foreach ($potensi->fotoGaleri as $foto) {
                    Storage::disk('public')->delete($foto->foto);
                }
            }

            // Delete records (cascade will handle foto)
            PotensiDesa::whereIn('id', $ids)->delete();

            // Clear cache
            Cache::forget('home.potensi');
            Cache::forget('home.total_potensi');
            Cache::forget('profil_desa');

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' potensi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
