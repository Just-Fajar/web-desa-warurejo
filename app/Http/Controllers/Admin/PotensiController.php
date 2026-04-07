<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotensiDesa;
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
     * - Handle checkbox is_active (default 0 jika unchecked)
     * - Upload gambar jika ada
     * - Clear cache homepage
     * Route: POST /admin/potensi
     */
    public function store(PotensiRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Auto-generate slug
            $data['slug'] = Str::slug($data['nama']);
            
            // Handle is_active checkbox (default true if not exists)
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            
            // Handle image upload
            if ($request->hasFile('gambar')) {
                $data['gambar'] = $this->imageUploadService->upload(
                    $request->file('gambar'), 
                    'potensi'
                );
            }
            
            PotensiDesa::create($data);
            
            // Clear cache
            Cache::forget('home.potensi');
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
        return view('admin.potensi.show', compact('potensi'));
    }

    /**
     * Tampilkan form edit potensi
     * Route: GET /admin/potensi/{id}/edit
     */
    public function edit(PotensiDesa $potensi)
    {
        return view('admin.potensi.edit', compact('potensi'));
    }

    /**
     * Update potensi yang sudah ada
     * - Handle checkbox is_active
     * - Update slug jika nama berubah
     * - Jika ada gambar baru, delete lama lalu upload baru
     * - Clear cache setelah update
     * Route: PUT /admin/potensi/{id}
     */
    public function update(PotensiRequest $request, PotensiDesa $potensi)
    {
        try {
            $data = $request->validated();
            
            // Handle is_active checkbox
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            
            // Update slug if nama changed
            if ($data['nama'] !== $potensi->nama) {
                $data['slug'] = Str::slug($data['nama']);
            }
            
            // Handle image upload
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
            
            $potensi->update($data);
            
            // Clear cache
            Cache::forget('home.potensi');
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
     * Delete potensi beserta gambarnya dari storage
     * Clear cache setelah delete
     * Route: DELETE /admin/potensi/{id}
     */
    public function destroy(PotensiDesa $potensi)
    {
        try {
            // Delete image
            if ($potensi->gambar) {
                $this->imageUploadService->delete($potensi->gambar);
            }
            
            $potensi->delete();
            
            // Clear cache
            Cache::forget('home.potensi');
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
     * Bulk delete multiple potensi sekaligus
     * - Loop semua dan delete gambarnya dari storage
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
            
            $potensiList = PotensiDesa::whereIn('id', $ids)->get();
            
            // Delete images
            foreach ($potensiList as $potensi) {
                if ($potensi->gambar) {
                    $this->imageUploadService->delete($potensi->gambar);
                }
            }
            
            // Delete records
            PotensiDesa::whereIn('id', $ids)->delete();
            
            // Clear cache
            Cache::forget('home.potensi');
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
