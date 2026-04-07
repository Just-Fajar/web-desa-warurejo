<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\DailyVisitorStat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorStatisticsService
{
    /**
     * Menghitung unique visitors hari ini
     * Menggunakan device_fingerprint untuk identify unique visitor
     * 
     * @return int - jumlah pengunjung unik hari ini
     */
    public function getTodayVisitors(): int
    {
        return Visitor::whereDate('visit_date', Carbon::today())
                      ->distinct()
                      ->count('device_fingerprint');
    }
    
    /**
     * Menghitung unique visitors 7 hari terakhir
     * 
     * @return int - jumlah pengunjung unik minggu ini
     */
    public function getWeeklyVisitors(): int
    {
        return Visitor::whereDate('visit_date', '>=', Carbon::now()->subDays(7))
                      ->distinct()
                      ->count('device_fingerprint');
    }
    
    /**
     * Menghitung unique visitors 30 hari terakhir
     * 
     * @return int - jumlah pengunjung unik bulan ini
     */
    public function getMonthlyVisitors(): int
    {
        return Visitor::whereDate('visit_date', '>=', Carbon::now()->subDays(30))
                      ->distinct()
                      ->count('device_fingerprint');
    }
    
    /**
     * Menghitung total unique visitors sepanjang waktu
     * 
     * @return int - total pengunjung unik all time
     */
    public function getTotalVisitors(): int
    {
        return Visitor::distinct()->count('device_fingerprint');
    }

    /**
     * Menghitung total page views hari ini
     * Satu visitor bisa menghasilkan multiple page views
     * 
     * @return int - total halaman yang dikunjungi hari ini
     */
    public function getTodayPageViews(): int
    {
        return Visitor::whereDate('visit_date', Carbon::today())->count();
    }
    
    /**
     * Mengambil data chart pengunjung untuk periode tertentu
     * - Mengisi tanggal yang kosong dengan nilai 0
     * - Return format siap untuk Chart.js
     * 
     * @param int $days - jumlah hari yang ditampilkan (default: 30)
     * @return array - berisi labels, visitors, dan pageViews
     */
    public function getChartData(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->toDateString();
        $endDate = Carbon::today()->toDateString();
        
        $stats = DailyVisitorStat::whereBetween('date', [$startDate, $endDate])
                                 ->orderBy('date', 'asc')
                                 ->get();
        
        // Fill missing dates with zeros
        $filledStats = [];
        $currentDate = Carbon::parse($startDate);
        
        while ($currentDate->lte(Carbon::parse($endDate))) {
            $dateStr = $currentDate->toDateString();
            $stat = $stats->firstWhere('date', $dateStr);
            
            $filledStats[] = [
                'date' => $dateStr,
                'unique_visitors' => $stat ? $stat->unique_visitors : 0,
                'page_views' => $stat ? $stat->page_views : 0,
            ];
            
            $currentDate->addDay();
        }
        
        return [
            'labels' => array_map(fn($s) => Carbon::parse($s['date'])->format('d M'), $filledStats),
            'visitors' => array_column($filledStats, 'unique_visitors'),
            'pageViews' => array_column($filledStats, 'page_views'),
        ];
    }

    /**
     * Menghitung persentase pertumbuhan visitor hari ini vs kemarin
     * - Nilai positif = pertumbuhan
     * - Nilai negatif = penurunan
     * - Return 0 jika kemarin tidak ada visitor
     * 
     * @return float - persentase pertumbuhan (misal: 25.5 atau -10.2)
     */
    public function getVisitorGrowth(): float
    {
        $today = $this->getTodayVisitors();
        $yesterday = Visitor::whereDate('visit_date', Carbon::yesterday())
                            ->distinct()
                            ->count('device_fingerprint');
        
        if ($yesterday == 0) {
            return $today > 0 ? 100.0 : 0.0;
        }
        
        return round((($today - $yesterday) / $yesterday) * 100, 1);
    }

    /**
     * Get most visited pages today
     */
    public function getMostVisitedPages(int $limit = 10): array
    {
        return Visitor::whereDate('visit_date', Carbon::today())
                      ->select('page_url', DB::raw('count(*) as visit_count'))
                      ->groupBy('page_url')
                      ->orderByDesc('visit_count')
                      ->limit($limit)
                      ->get()
                      ->toArray();
    }

    /**
     * Clean up old visitor data (older than specified days)
     */
    public function cleanupOldData(int $daysToKeep = 90): int
    {
        $cutoffDate = Carbon::now()->subDays($daysToKeep)->toDateString();
        
        return Visitor::where('visit_date', '<', $cutoffDate)->delete();
    }

    /**
     * Aggregate yesterday's data into daily stats
     * This ensures we have backup data even if live counting fails
     */
    public function aggregateYesterdayStats(): void
    {
        $yesterday = Carbon::yesterday()->toDateString();
        
        $uniqueVisitors = Visitor::whereDate('visit_date', Carbon::yesterday())
                                 ->distinct()
                                 ->count('device_fingerprint');
        
        $pageViews = Visitor::whereDate('visit_date', Carbon::yesterday())->count();
        
        DailyVisitorStat::updateOrCreate(
            ['date' => $yesterday],
            [
                'unique_visitors' => $uniqueVisitors,
                'page_views' => $pageViews,
            ]
        );
    }

    /**
     * Get yearly chart data for specified year
     * Returns monthly aggregated data for better visualization
     */
    public function getYearlyChartData(?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;
        $startDate = Carbon::create($year, 1, 1)->toDateString();
        $endDate = Carbon::create($year, 12, 31)->toDateString();
        
        // Get monthly data
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($year, $month, 1)->toDateString();
            $monthEnd = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
            
            // Get data from DailyVisitorStat
            $stats = DailyVisitorStat::whereBetween('date', [$monthStart, $monthEnd])->get();
            
            $uniqueVisitors = $stats->sum('unique_visitors');
            $pageViews = $stats->sum('page_views');
            
            // If no daily stats, fallback to raw Visitor data
            if ($uniqueVisitors == 0 && $pageViews == 0) {
                $uniqueVisitors = Visitor::whereBetween('visit_date', [$monthStart, $monthEnd])
                                         ->distinct()
                                         ->count('device_fingerprint');
                $pageViews = Visitor::whereBetween('visit_date', [$monthStart, $monthEnd])->count();
            }
            
            $monthlyData[] = [
                'month' => Carbon::create($year, $month, 1)->format('M'),
                'unique_visitors' => $uniqueVisitors,
                'page_views' => $pageViews,
            ];
        }
        
        return [
            'labels' => array_column($monthlyData, 'month'),
            'visitors' => array_column($monthlyData, 'unique_visitors'),
            'pageViews' => array_column($monthlyData, 'page_views'),
        ];
    }

    /**
     * Get all years that have visitor data
     * Returns array of years in descending order
     */
    public function getAvailableYears(): array
    {
        $years = Visitor::selectRaw('YEAR(visit_date) as year')
                        ->distinct()
                        ->orderByDesc('year')
                        ->pluck('year')
                        ->toArray();
        
        // If no data yet, return current year
        if (empty($years)) {
            return [Carbon::now()->year];
        }
        
        return $years;
    }

    /**
     * Get all-time statistics summary
     */
    public function getAllTimeStats(): array
    {
        $firstVisit = Visitor::min('visit_date');
        $lastVisit = Visitor::max('visit_date');
        
        return [
            'total_unique_visitors' => $this->getTotalVisitors(),
            'total_page_views' => Visitor::count(),
            'first_visit_date' => $firstVisit ? Carbon::parse($firstVisit)->format('d M Y') : '-',
            'last_visit_date' => $lastVisit ? Carbon::parse($lastVisit)->format('d M Y') : '-',
            'days_active' => $firstVisit ? Carbon::parse($firstVisit)->diffInDays(Carbon::today()) + 1 : 0,
        ];
    }

    /**
     * Get yearly content chart data (monthly aggregation)
     */
    public function getYearlyContentChartData(?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;
        
        // Get all models
        $beritaModel = app(\App\Models\Berita::class);
        $potensiModel = app(\App\Models\PotensiDesa::class);
        $galeriModel = app(\App\Models\Galeri::class);
        $publikasiModel = app(\App\Models\Publikasi::class);
        
        // Initialize arrays for 12 months
        $months = [];
        $beritaData = [];
        $potensiData = [];
        $galeriData = [];
        $publikasiData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::create($year, $month, 1)->format('M');
            $months[] = $monthName;
            
            // Count content for each category in this month
            $beritaData[] = $beritaModel
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $potensiData[] = $potensiModel
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $galeriData[] = $galeriModel
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();
            
            $publikasiData[] = $publikasiModel
                ->whereYear('tanggal_publikasi', $year)
                ->whereMonth('tanggal_publikasi', $month)
                ->count();
        }
        
        return [
            'labels' => $months,
            'berita' => $beritaData,
            'potensi' => $potensiData,
            'galeri' => $galeriData,
            'publikasi' => $publikasiData
        ];
    }

    /**
     * Get available years for content statistics
     */
    public function getContentAvailableYears(): array
    {
        $beritaModel = app(\App\Models\Berita::class);
        $potensiModel = app(\App\Models\PotensiDesa::class);
        $galeriModel = app(\App\Models\Galeri::class);
        $publikasiModel = app(\App\Models\Publikasi::class);
        
        $years = [];
        
        // Get years from Berita
        $beritaYears = $beritaModel->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
        
        // Get years from Potensi
        $potensiYears = $potensiModel->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
        
        // Get years from Galeri
        $galeriYears = $galeriModel->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
        
        // Get years from Publikasi
        $publikasiYears = $publikasiModel->selectRaw('YEAR(tanggal_publikasi) as year')
            ->whereNotNull('tanggal_publikasi')
            ->distinct()
            ->pluck('year')
            ->toArray();
        
        // Merge and sort years
        $years = array_unique(array_merge($beritaYears, $potensiYears, $galeriYears, $publikasiYears));
        rsort($years); // Sort descending
        
        // If no years found, return current year
        return empty($years) ? [Carbon::now()->year] : array_values($years);
    }
}
