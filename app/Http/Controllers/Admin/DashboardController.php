<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\Publikasi;
use App\Services\VisitorStatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $visitorService;

    /**
     * Constructor - inject VisitorStatisticsService untuk handle statistik pengunjung
     */
    public function __construct(VisitorStatisticsService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    /**
     * Menampilkan halaman dashboard admin dengan semua statistik dan data
     * - Menghitung total konten (berita, potensi, galeri, publikasi)
     * - Mengambil statistik pengunjung (hari ini, minggu, bulan, total)
     * - Menyiapkan data chart untuk visitor dan content statistics
     * - Mengambil recent activities dari semua konten
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Statistik Cards
        $totalBerita = Berita::count();
        $totalPotensi = PotensiDesa::count();
        $totalGaleri = Galeri::count();
        $totalPublikasi = Publikasi::count();
        
        // Visitor Statistics (Real Data)
        $pengunjungHariIni = $this->visitorService->getTodayVisitors();
        $pengunjungMingguIni = $this->visitorService->getWeeklyVisitors();
        $pengunjungBulanIni = $this->visitorService->getMonthlyVisitors();
        $totalPengunjung = $this->visitorService->getTotalVisitors();
        $pertumbuhanHariIni = $this->visitorService->getVisitorGrowth();
        $pageViewsHariIni = $this->visitorService->getTodayPageViews();
        
        // Chart Data untuk Visitor Statistics
        $currentYear = Carbon::now()->year;
        $visitorChartData = $this->visitorService->getYearlyChartData($currentYear);
        $availableYears = $this->visitorService->getAvailableYears();
        $allTimeStats = $this->visitorService->getAllTimeStats();
        
        // Recent Activities
        $recentBerita = Berita::with('admin')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPotensi = PotensiDesa::latest()
            ->take(5)
            ->get();
        
        $recentGaleri = Galeri::latest()
            ->take(5)
            ->get();
        
        $recentPublikasi = Publikasi::orderBy('tanggal_publikasi', 'desc')
            ->take(5)
            ->get();
        
        // Monthly Stats untuk Chart (content) - yearly data
        $currentContentYear = Carbon::now()->year;
        $monthlyStats = $this->visitorService->getYearlyContentChartData($currentContentYear);
        $contentAvailableYears = $this->visitorService->getContentAvailableYears();
        
        // Quick Stats
        $beritaPublished = Berita::where('status', 'published')->count();
        $beritaDraft = Berita::where('status', 'draft')->count();
        
        return view('admin.dashboard.index', compact(
            'totalBerita',
            'totalPotensi',
            'totalGaleri',
            'totalPublikasi',
            'pengunjungHariIni',
            'pengunjungMingguIni',
            'pengunjungBulanIni',
            'totalPengunjung',
            'pertumbuhanHariIni',
            'pageViewsHariIni',
            'visitorChartData',
            'availableYears',
            'currentYear',
            'allTimeStats',
            'recentBerita',
            'recentPotensi',
            'recentGaleri',
            'recentPublikasi',
            'monthlyStats',
            'contentAvailableYears',
            'currentContentYear',
            'beritaPublished',
            'beritaDraft'
        ));
    }
    
    /**
     * AJAX: Mengambil data chart pengunjung berdasarkan tahun tertentu
     * Digunakan saat user mengubah filter tahun di chart visitor statistics
     * 
     * @param Request $request - berisi parameter 'year'
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVisitorChartByYear(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $chartData = $this->visitorService->getYearlyChartData($year);
        
        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }
    
    /**
     * AJAX: Mengambil data chart konten (berita, potensi, galeri) berdasarkan tahun
     * Digunakan saat user mengubah filter tahun di chart content statistics
     * 
     * @param Request $request - berisi parameter 'year'
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContentChartByYear(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $chartData = $this->visitorService->getYearlyContentChartData($year);
        
        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }
    
    /**
     * Private method: Menghitung statistik konten per bulan untuk 6 bulan terakhir
     * Menghasilkan data untuk chart content statistics (berita, potensi, galeri)
     * 
     * @return array - berisi labels (bulan) dan data per kategori konten
     */
    private function getMonthlyStats()
    {
        $months = [];
        $beritaData = [];
        $potensiData = [];
        $galeriData = [];
        
        // Data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthYear = $date->format('M Y');
            $months[] = $monthYear;
            
            // Hitung data per bulan
            $beritaData[] = Berita::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $potensiData[] = PotensiDesa::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $galeriData[] = Galeri::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        return [
            'months' => $months,
            'berita' => $beritaData,
            'potensi' => $potensiData,
            'galeri' => $galeriData,
        ];
    }
}
