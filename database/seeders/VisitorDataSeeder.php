<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyVisitorStat;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitorDataSeeder extends Seeder
{
    /**
     * Seed realistic visitor statistics: 1 tahun ke belakang sampai hari ini.
     * - Pola realistis: weekday lebih ramai, weekend lebih sepi
     * - Tren traffic naik bertahap (website makin dikenal)
     * - Data ringan: hanya daily_visitor_stats + beberapa sample visitor rows
     */
    public function run(): void
    {
        $this->command->info('📊 Seeding statistik pengunjung web (1 tahun)...');

        DailyVisitorStat::truncate();
        Visitor::truncate();

        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate = Carbon::today();
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Traffic naik bertahap per bulan (website makin dikenal)
        $monthlyBase = [
            ['min' => 5,  'max' => 15],   // 12 bulan lalu - baru launch
            ['min' => 8,  'max' => 20],   // 11 bulan lalu
            ['min' => 12, 'max' => 30],   // 10 bulan lalu
            ['min' => 18, 'max' => 40],   // 9 bulan lalu
            ['min' => 22, 'max' => 50],   // 8 bulan lalu
            ['min' => 28, 'max' => 60],   // 7 bulan lalu
            ['min' => 35, 'max' => 70],   // 6 bulan lalu
            ['min' => 40, 'max' => 85],   // 5 bulan lalu
            ['min' => 45, 'max' => 95],   // 4 bulan lalu
            ['min' => 55, 'max' => 110],  // 3 bulan lalu
            ['min' => 65, 'max' => 125],  // 2 bulan lalu
            ['min' => 75, 'max' => 140],  // bulan ini
        ];

        $pages = ['/', '/berita', '/galeri', '/potensi', '/publikasi', '/profil', '/peta', '/pengaduan'];

        $statsBatch = [];
        $visitorBatch = [];
        $currentDate = $startDate->copy();
        $batchCount = 0;

        while ($currentDate->lte($endDate)) {
            // Hitung bulan ke berapa dari start
            $monthIdx = min((int) $startDate->diffInMonths($currentDate), 11);
            $range = $monthlyBase[$monthIdx];

            // Weekend lebih sepi
            $mod = $currentDate->isWeekend() ? 0.55 : 1.0;
            // Hari Jumat sedikit lebih ramai
            if ($currentDate->isFriday()) $mod = 1.15;

            $visitors = (int) round(rand($range['min'], $range['max']) * $mod);
            $pageViews = (int) round($visitors * (rand(20, 40) / 10)); // 2x-4x visitors

            $dateStr = $currentDate->toDateString();

            $statsBatch[] = [
                'date' => $dateStr,
                'unique_visitors' => $visitors,
                'page_views' => $pageViews,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Sample visitor rows (max 5 per hari, cukup untuk tracking)
            $sampleCount = min($visitors, 5);
            for ($i = 0; $i < $sampleCount; $i++) {
                $fp = hash('sha256', $dateStr . '_v_' . $i . '_' . rand(1000, 9999));
                $visitorBatch[] = [
                    'ip_address' => '192.168.' . rand(1, 254) . '.' . rand(1, 254),
                    'user_agent' => $this->randomUA(),
                    'device_fingerprint' => $fp,
                    'visit_date' => $dateStr,
                    'last_visit_at' => $currentDate->copy()->addHours(rand(7, 22))->addMinutes(rand(0, 59)),
                    'visit_count' => rand(1, 5),
                    'referer' => $this->randomReferer(),
                    'page_url' => $pages[array_rand($pages)],
                    'created_at' => $currentDate->copy()->addHours(rand(7, 22)),
                    'updated_at' => now(),
                ];
            }

            $batchCount++;

            // Insert per 30 hari untuk performa
            if ($batchCount >= 30) {
                DB::table('daily_visitor_stats')->insert($statsBatch);
                DB::table('visitors')->insert($visitorBatch);
                $statsBatch = [];
                $visitorBatch = [];
                $batchCount = 0;
            }

            $currentDate->addDay();
        }

        // Sisa data
        if (!empty($statsBatch)) DB::table('daily_visitor_stats')->insert($statsBatch);
        if (!empty($visitorBatch)) DB::table('visitors')->insert($visitorBatch);

        $totalVisitors = DailyVisitorStat::sum('unique_visitors');
        $totalPV = DailyVisitorStat::sum('page_views');
        $this->command->info("   ✅ {$totalDays} hari data: ~{$totalVisitors} pengunjung, ~{$totalPV} page views");
    }

    private function randomUA(): string
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) AppleWebKit/605.1.15 Mobile Safari/604.1',
            'Mozilla/5.0 (Linux; Android 14; SM-A546B) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0',
            'Mozilla/5.0 (Linux; Android 13; Pixel 7) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36',
        ];
        return $agents[array_rand($agents)];
    }

    private function randomReferer(): ?string
    {
        $refs = [null, null, null, 'https://www.google.com', 'https://www.google.co.id', 'https://www.facebook.com', 'https://wa.me'];
        return $refs[array_rand($refs)];
    }
}
