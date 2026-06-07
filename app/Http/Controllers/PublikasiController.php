<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    /**
     * Display a listing of publikasi
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'APBDes');
        $tahun = $request->get('tahun');
        $search = $request->get('search');
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
            })
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', '%'.$search.'%');
            });

        // Apply sorting
        switch ($urutkan) {
            case 'terlama':
                $query->orderBy('tanggal_publikasi', 'asc')
                    ->orderBy('created_at', 'asc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('tanggal_publikasi', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
        }

        $publikasi = $query->paginate(10);

        // Get sidebar publikasi (other categories)
        $sidebarPublikasi = Publikasi::published()
            ->where('kategori', '!=', $kategori)
            ->orderBy('tanggal_publikasi', 'desc')
            ->orderBy('created_at', 'desc')
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
            ->orderBy('tanggal_publikasi', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('public.publikasi.show', compact('publikasi', 'relatedPublikasi'));
    }

    /**
     * Download dokumen
     */
    public function download($id)
    {
        abort(403, 'Fitur unduh dokumen tidak tersedia. Dokumen hanya dapat dibaca secara online.');
    }
}
