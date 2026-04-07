<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PublikasiController extends Controller
{
    /**
     * Tampilkan list semua publikasi dengan filter
     * Filter available: search, kategori, status, tahun
     * Sort by tanggal_publikasi descending
     * Route: GET /admin/publikasi
     */
    public function index(Request $request)
    {
        $query = Publikasi::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $publikasi = $query->latest('tanggal_publikasi')->paginate(12);

        // Get available years for filter
        $availableYears = Publikasi::distinct()->pluck('tahun')->sort()->reverse();

        return view('admin.publikasi.index', compact('publikasi', 'availableYears'));
    }

    /**
     * Tampilkan form create publikasi baru
     * Publikasi: APBDes, RPJMDes, RKPDes (dokumen desa)
     * Route: GET /admin/publikasi/create
     */
    public function create()
    {
        return view('admin.publikasi.create');
    }

    /**
     * Simpan publikasi baru dengan file PDF dan thumbnail
     * - Validate: PDF max 10MB, thumbnail image max 2MB
     * - Upload file dokumen ke storage/publikasi
     * - Upload thumbnail optional ke storage/publikasi/thumbnails
     * - Clear cache homepage
     * Route: POST /admin/publikasi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:APBDes,RPJMDes,RKPDes',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'deskripsi' => 'nullable|string',
            'file_dokumen' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_publikasi' => 'required|date',
            'status' => 'required|in:draft,published',
        ]);

        // Upload file dokumen
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('publikasi', 'public');
            $validated['file_dokumen'] = $filePath;
        }

        // Upload thumbnail (optional)
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('publikasi/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        Publikasi::create($validated);

        // Clear cache
        Cache::forget('home.publikasi');
        Cache::forget('profil_desa');

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail publikasi (preview)
     * Route: GET /admin/publikasi/{id}
     */
    public function show(string $id)
    {
        $publikasi = Publikasi::findOrFail($id);
        return view('admin.publikasi.show', compact('publikasi'));
    }

    /**
     * Tampilkan form edit publikasi
     * Route: GET /admin/publikasi/{id}/edit
     */
    public function edit(string $id)
    {
        $publikasi = Publikasi::findOrFail($id);
        return view('admin.publikasi.edit', compact('publikasi'));
    }

    /**
     * Update publikasi yang sudah ada
     * - Jika ada file baru, delete file lama lalu upload baru
     * - Jika ada thumbnail baru, delete thumbnail lama lalu upload baru
     * - Clear cache setelah update
     * Route: PUT /admin/publikasi/{id}
     */
    public function update(Request $request, string $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:APBDes,RPJMDes,RKPDes',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'deskripsi' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_publikasi' => 'required|date',
            'status' => 'required|in:draft,published',
        ]);

        // Upload new file dokumen if provided
        if ($request->hasFile('file_dokumen')) {
            // Delete old file
            if ($publikasi->file_dokumen && Storage::disk('public')->exists($publikasi->file_dokumen)) {
                Storage::disk('public')->delete($publikasi->file_dokumen);
            }
            $filePath = $request->file('file_dokumen')->store('publikasi', 'public');
            $validated['file_dokumen'] = $filePath;
        }

        // Upload new thumbnail if provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($publikasi->thumbnail && Storage::disk('public')->exists($publikasi->thumbnail)) {
                Storage::disk('public')->delete($publikasi->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('publikasi/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $publikasi->update($validated);

        // Clear cache
        Cache::forget('home.publikasi');
        Cache::forget('profil_desa');

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui!');
    }

    /**
     * Delete publikasi beserta file PDF dan thumbnail
     * Clear cache setelah delete
     * Route: DELETE /admin/publikasi/{id}
     */
    public function destroy(string $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        // Delete files
        if ($publikasi->file_dokumen && Storage::disk('public')->exists($publikasi->file_dokumen)) {
            Storage::disk('public')->delete($publikasi->file_dokumen);
        }
        if ($publikasi->thumbnail && Storage::disk('public')->exists($publikasi->thumbnail)) {
            Storage::disk('public')->delete($publikasi->thumbnail);
        }

        $publikasi->delete();

        // Clear cache
        Cache::forget('home.publikasi');
        Cache::forget('profil_desa');

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus!');
    }

    /**
     * Bulk delete multiple publikasi sekaligus
     * - Loop semua dan delete file PDF + thumbnail
     * - Return JSON response untuk AJAX
     * Route: POST /admin/publikasi/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada publikasi yang dipilih']);
        }

        $publikasi = Publikasi::whereIn('id', $ids)->get();

        foreach ($publikasi as $item) {
            // Delete files
            if ($item->file_dokumen && Storage::disk('public')->exists($item->file_dokumen)) {
                Storage::disk('public')->delete($item->file_dokumen);
            }
            if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                Storage::disk('public')->delete($item->thumbnail);
            }
            $item->delete();
        }

        // Clear cache
        Cache::forget('home.publikasi');
        Cache::forget('profil_desa');

        return response()->json(['success' => true, 'message' => count($ids) . ' publikasi berhasil dihapus']);
    }
}

