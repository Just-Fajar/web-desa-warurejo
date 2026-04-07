<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StrukturOrganisasiRequest;
use App\Services\StrukturOrganisasiService;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StrukturOrganisasiController extends Controller
{
    protected $strukturOrganisasiService;

    /**
     * Constructor - Inject StrukturOrganisasiService
     * Controller untuk handle HTTP requests struktur organisasi desa di admin
     */
    public function __construct(StrukturOrganisasiService $strukturOrganisasiService)
    {
        $this->strukturOrganisasiService = $strukturOrganisasiService;
    }

    /**
     * Tampilkan list semua anggota struktur organisasi
     * Include data levels untuk filter
     * Route: GET /admin/struktur-organisasi
     */
    public function index()
    {
        $strukturOrganisasi = $this->strukturOrganisasiService->getPaginatedStrukturOrganisasi(15);
        $levels = StrukturOrganisasi::getLevels();
        
        return view('admin.struktur-organisasi.index', compact('strukturOrganisasi', 'levels'));
    }

    /**
     * Tampilkan form create anggota baru
     * - Load data levels (Kepala, Sekretaris, Kaur, dll)
     * - Load potential atasan untuk hierarki
     * Route: GET /admin/struktur-organisasi/create
     */
    public function create()
    {
        $levels = StrukturOrganisasi::getLevels();
        
        // Get potential atasan (kepala, sekretaris, kaur, kasi)
        $potentialAtasan = StrukturOrganisasi::active()
            ->whereIn('level', ['kepala', 'sekretaris', 'kaur', 'kasi'])
            ->ordered()
            ->get();
        
        return view('admin.struktur-organisasi.create', compact('levels', 'potentialAtasan'));
    }

    /**
     * Simpan anggota struktur organisasi baru
     * - Validate via StrukturOrganisasiRequest
     * - Upload foto profile jika ada
     * - Clear cache struktur organisasi
     * Route: POST /admin/struktur-organisasi
     */
    public function store(StrukturOrganisasiRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Handle foto upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto');
            }

            $this->strukturOrganisasiService->createStrukturOrganisasi($data);

            // Clear cache
            Cache::forget('struktur_organisasi');

            return redirect()
                ->route('admin.struktur-organisasi.index')
                ->with('success', 'Anggota struktur organisasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail anggota struktur organisasi
     * Route: GET /admin/struktur-organisasi/{id}
     */
    public function show($id)
    {
        $strukturOrganisasi = $this->strukturOrganisasiService->getStrukturOrganisasiById($id);
        return view('admin.struktur-organisasi.show', compact('strukturOrganisasi'));
    }

    /**
     * Tampilkan form edit anggota
     * - Load levels dan potential atasan
     * - Exclude current item dari list atasan
     * Route: GET /admin/struktur-organisasi/{id}/edit
     */
    public function edit($id)
    {
        $strukturOrganisasi = $this->strukturOrganisasiService->getStrukturOrganisasiById($id);
        $levels = StrukturOrganisasi::getLevels();
        
        // Get potential atasan
        $potentialAtasan = StrukturOrganisasi::active()
            ->whereIn('level', ['kepala', 'sekretaris', 'kaur', 'kasi'])
            ->where('id', '!=', $id) // exclude current item
            ->ordered()
            ->get();
        
        return view('admin.struktur-organisasi.edit', compact('strukturOrganisasi', 'levels', 'potentialAtasan'));
    }

    /**
     * Update anggota struktur organisasi
     * - Handle foto upload (delete lama jika ada baru)
     * - Clear cache setelah update
     * Route: PUT /admin/struktur-organisasi/{id}
     */
    public function update(StrukturOrganisasiRequest $request, $id)
    {
        try {
            $data = $request->validated();
            
            // Handle foto upload
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto');
            }

            $this->strukturOrganisasiService->updateStrukturOrganisasi($id, $data);

            // Clear cache
            Cache::forget('struktur_organisasi');

            return redirect()
                ->route('admin.struktur-organisasi.index')
                ->with('success', 'Anggota struktur organisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete anggota beserta foto profilenya
     * Clear cache setelah delete
     * Route: DELETE /admin/struktur-organisasi/{id}
     */
    public function destroy($id)
    {
        try {
            $this->strukturOrganisasiService->deleteStrukturOrganisasi($id);

            // Clear cache
            Cache::forget('struktur_organisasi');

            return redirect()
                ->route('admin.struktur-organisasi.index')
                ->with('success', 'Anggota struktur organisasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete multiple anggota sekaligus
     * - Delete foto dari storage untuk setiap anggota
     * - Return JSON response untuk AJAX
     * Route: POST /admin/struktur-organisasi/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang dipilih'
                ], 400);
            }

            $this->strukturOrganisasiService->bulkDelete($ids);

            // Clear cache
            Cache::forget('struktur_organisasi');

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' anggota struktur organisasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
