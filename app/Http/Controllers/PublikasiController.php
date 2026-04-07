<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;

class PublikasiController extends Controller
{
    /**
     * Display a listing of publikasi
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'APBDes');
        $tahun = $request->get('tahun');
        $urutkan = $request->get('urutkan', 'terbaru'); // terbaru, terlama, terpopuler

        // Get all available years
        $availableYears = Publikasi::published()
            ->byKategori($kategori)
            ->distinct()
            ->pluck('tahun')
            ->sort()
            ->reverse()
            ->values();

        // Query publikasi
        $query = Publikasi::published()
            ->byKategori($kategori)
            ->when($tahun, function ($query, $tahun) {
                return $query->byTahun($tahun);
            });
        
        // Apply sorting
        switch ($urutkan) {
            case 'terpopuler':
                $query->orderBy('jumlah_download', 'desc');
                break;
            case 'terlama':
                $query->oldest();
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }
        
        $publikasi = $query->paginate(10);

        // Get sidebar publikasi (other categories)
        $sidebarPublikasi = Publikasi::published()
            ->where('kategori', '!=', $kategori)
            ->latest()
            ->take(5)
            ->get();

        // Available categories
        $categories = ['APBDes', 'RPJMDes', 'RKPDes'];

        return view('public.publikasi.index', compact(
            'publikasi',
            'kategori',
            'tahun',
            'availableYears',
            'sidebarPublikasi',
            'categories'
        ));
    }

    /**
     * Display the specified publikasi
     */
    public function show($id)
    {
        $publikasi = Publikasi::published()->findOrFail($id);
        
        // Get related publikasi (same category)
        $relatedPublikasi = Publikasi::published()
            ->byKategori($publikasi->kategori)
            ->where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();

        return view('public.publikasi.show', compact('publikasi', 'relatedPublikasi'));
    }

    /**
     * Download dokumen
     */
    public function download($id)
    {
        $publikasi = Publikasi::published()->findOrFail($id);
        $publikasi->incrementDownload();

        $filePath = storage_path('app/public/' . $publikasi->file_dokumen);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $publikasi->judul . '.pdf');
    }
}
