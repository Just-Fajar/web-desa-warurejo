<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeritaRequest;
use App\Services\BeritaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BeritaController extends Controller
{
    protected $beritaService;

    /**
     * Constructor - Inject BeritaService
     * Controller untuk handle HTTP requests berita di admin panel
     */
    public function __construct(BeritaService $beritaService)
    {
        $this->beritaService = $beritaService;
    }

    /**
     * Tampilkan list semua berita dengan pagination
     * Route: GET /admin/berita
     */
    public function index()
    {
        $berita = $this->beritaService->getPaginatedBerita(10);
        return view('admin.berita.index', compact('berita'));
    }

    /**
     * Tampilkan form create berita baru
     * Route: GET /admin/berita/create
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Simpan berita baru ke database
     * - Validate input via BeritaRequest
     * - Set admin_id dari user yang login
     * - Clear cache homepage dan profil desa
     * Route: POST /admin/berita
     */
    public function store(BeritaRequest $request)
    {
        try {
            $data = $request->validated();
            $data['admin_id'] = auth()->guard('admin')->id();

            $this->beritaService->createBerita($data);

            // Clear cache
            Cache::forget('home.latest_berita');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.berita.index')
                ->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail berita (preview)
     * Route: GET /admin/berita/{id}
     */
    public function show($id)
    {
        $berita = $this->beritaService->getBeritaById($id);
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Tampilkan form edit berita
     * Route: GET /admin/berita/{id}/edit
     */
    public function edit($id)
    {
        $berita = $this->beritaService->getBeritaById($id);
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update berita yang sudah ada
     * - Validate input via BeritaRequest
     * - Handle image upload/delete di service layer
     * - Clear cache setelah update
     * Route: PUT /admin/berita/{id}
     */
    public function update(BeritaRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $this->beritaService->updateBerita($id, $data);

            // Clear cache
            Cache::forget('home.latest_berita');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.berita.index')
                ->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete berita beserta gambarnya
     * Clear cache setelah delete
     * Route: DELETE /admin/berita/{id}
     */
    public function destroy($id)
    {
        try {
            $this->beritaService->deleteBerita($id);

            // Clear cache
            Cache::forget('home.latest_berita');
            Cache::forget('profil_desa');

            return redirect()
                ->route('admin.berita.index')
                ->with('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete multiple berita sekaligus
     * - Terima array ids dari request
     * - Delete satu per satu (include gambar)
     * - Return JSON response untuk AJAX
     * Route: POST /admin/berita/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada berita yang dipilih.'
                ], 400);
            }

            $count = 0;
            foreach ($ids as $id) {
                $this->beritaService->deleteBerita($id);
                $count++;
            }

            // Clear cache
            Cache::forget('home.latest_berita');
            Cache::forget('profil_desa');

            return response()->json([
                'success' => true,
                'message' => "{$count} berita berhasil dihapus."
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }} 
