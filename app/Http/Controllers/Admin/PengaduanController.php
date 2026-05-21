<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBalasanRequest;
use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Tampilkan daftar pengaduan di admin
     * Route: GET /admin/pengaduan
     */
    public function index(Request $request)
    {
        $query = Pengaduan::latest()->withCount('balasan');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengaduan = $query->paginate(15);

        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    /**
     * Tampilkan detail pengaduan di admin (data lengkap tanpa sensor)
     * Route: GET /admin/pengaduan/{id}
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::with(['balasan' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Simpan balasan admin + update status pengaduan
     * Route: POST /admin/pengaduan/{id}/balas
     */
    public function storeBalasan(StoreBalasanRequest $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Simpan balasan jika isi diisi atau ada lampiran
        if ($request->filled('isi') || $request->hasFile('lampiran_balasan')) {
            $balasanData = [
                'pengaduan_id' => $pengaduan->id,
                'isi' => $request->isi,
                'is_admin' => true,
            ];

            // Upload lampiran balasan jika ada
            if ($request->hasFile('lampiran_balasan')) {
                $balasanData['lampiran'] = $request->file('lampiran_balasan')->store('pengaduan/balasan', 'public');
            }

            PengaduanBalasan::create($balasanData);
        }

        // Update status pengaduan
        $pengaduan->status = $request->status;

        // Simpan alasan penolakan jika status Ditolak
        if ($request->status === 'Ditolak') {
            $pengaduan->alasan_penolakan = $request->alasan_penolakan;
        } else {
            // Clear alasan jika status bukan Ditolak
            $pengaduan->alasan_penolakan = null;
        }

        $pengaduan->save();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Balasan berhasil dikirim dan status diperbarui.');
    }

    /**
     * Hapus pengaduan beserta balasan & lampiran
     * Route: DELETE /admin/pengaduan/{id}
     */
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Hapus lampiran pengaduan dari storage jika ada
        if ($pengaduan->lampiran && Storage::disk('public')->exists($pengaduan->lampiran)) {
            Storage::disk('public')->delete($pengaduan->lampiran);
        }

        // Hapus lampiran balasan dari storage
        foreach ($pengaduan->balasan as $balasan) {
            if ($balasan->lampiran && Storage::disk('public')->exists($balasan->lampiran)) {
                Storage::disk('public')->delete($balasan->lampiran);
            }
        }

        // Balasan otomatis terhapus via cascade
        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    /**
     * Hapus banyak pengaduan sekaligus
     * Route: POST /admin/pengaduan/bulk-delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada pengaduan yang dipilih.']);
        }

        $pengaduanList = Pengaduan::whereIn('id', $ids)->get();

        foreach ($pengaduanList as $pengaduan) {
            // Hapus lampiran dari storage jika ada
            if ($pengaduan->lampiran && Storage::disk('public')->exists($pengaduan->lampiran)) {
                Storage::disk('public')->delete($pengaduan->lampiran);
            }
            $pengaduan->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($ids).' pengaduan berhasil dihapus.',
        ]);
    }
}
