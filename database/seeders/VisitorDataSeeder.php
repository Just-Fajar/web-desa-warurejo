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
     * Seed realistic visitor statistics data for the dashboard charts.
     * 
     * Generates data for the past 6 months with:
     * - Realistic daily patterns (weekdays busier than weekends)
     * - Growing traffic trend
     * - Per-section page view breakdown
     * - Dummy visitor records for unique visitor tracking
     */
    public function run(): void
    {
        $this->command->info('📊 Seeding visitor statistics data...');
        
        // Clear existing data
        DailyVisitorStat::truncate();
        Visitor::truncate();
        
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::today();
        
        $currentDate = $startDate->copy();
        $monthIndex = 0;
        $lastMonth = $startDate->month;
        
        // Base traffic ranges per month (increasing trend)
        $baseTrafficRanges = [
            ['min' => 15, 'max' => 40],   // Month 1 (6 months ago)
            ['min' => 25, 'max' => 55],   // Month 2
            ['min' => 35, 'max' => 70],   // Month 3
            ['min' => 45, 'max' => 90],   // Month 4
            ['min' => 55, 'max' => 110],  // Month 5
            ['min' => 70, 'max' => 140],  // Month 6 (current month)
        ];
        
        $dailyStatsBatch = [];
        $visitorBatch = [];
        $batchCount = 0;
        
        while ($currentDate->lte($endDate)) {
            // Track month changes for traffic range
            if ($currentDate->month !== $lastMonth) {
                $monthIndex = min($monthIndex + 1, count($baseTrafficRanges) - 1);
                $lastMonth = $currentDate->month;
            }
            
            $range = $baseTrafficRanges[$monthIndex];
            $isWeekend = $currentDate->isWeekend();
            
            // Weekend traffic is lower
            $modifier = $isWeekend ? 0.6 : 1.0;
            
            // Add some randomness
            $uniqueVisitors = (int) round(rand($range['min'], $range['max']) * $modifier);
            
            // Page views are typically 2-4x unique visitors (users browse multiple pages)
            $pvMultiplier = rand(20, 40) / 10; // 2.0 to 4.0
            $pageViews = (int) round($uniqueVisitors * $pvMultiplier);
            
            // Distribute page views across sections (percentages with randomness)
            // Beranda typically gets the most views (entry page)
            $berandaPct = rand(25, 35) / 100;
            $beritaPct = rand(18, 28) / 100;
            $potensiPct = rand(10, 18) / 100;
            $galeriPct = rand(8, 15) / 100;
            $petaPct = rand(5, 10) / 100;
            $dokumenPct = rand(4, 8) / 100;
            
            $berandaViews = (int) round($pageViews * $berandaPct);
            $beritaViews = (int) round($pageViews * $beritaPct);
            $potensiViews = (int) round($pageViews * $potensiPct);
            $galeriViews = (int) round($pageViews * $galeriPct);
            $petaViews = (int) round($pageViews * $petaPct);
            $dokumenViews = (int) round($pageViews * $dokumenPct);
            
            $dateStr = $currentDate->toDateString();
            
            $dailyStatsBatch[] = [
                'date' => $dateStr,
                'unique_visitors' => $uniqueVisitors,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Generate some visitor rows for this day (for unique visitor queries)
            $visitorsToCreate = min($uniqueVisitors, 10); // Cap at 10 per day for performance
            for ($i = 0; $i < $visitorsToCreate; $i++) {
                $fingerprint = hash('sha256', $dateStr . '_visitor_' . $i . '_' . rand(1000, 9999));
                
                $visitorBatch[] = [
                    'ip_address' => '192.168.' . rand(0, 255) . '.0',
                    'user_agent' => $this->getRandomUserAgent(),
                    'device_fingerprint' => $fingerprint,
                    'visit_date' => $dateStr,
                    'last_visit_at' => $currentDate->copy()->addHours(rand(6, 22))->addMinutes(rand(0, 59)),
                    'visit_count' => rand(1, 5),
                    'referer' => rand(0, 1) ? 'https://www.google.com' : null,
                    'created_at' => $currentDate->copy()->addHours(rand(6, 22)),
                    'updated_at' => now(),
                ];
            }
            
            $batchCount++;
            
            // Insert in batches of 30 days for performance
            if ($batchCount >= 30) {
                DB::table('daily_visitor_stats')->insert($dailyStatsBatch);
                DB::table('visitors')->insert($visitorBatch);
                $dailyStatsBatch = [];
                $visitorBatch = [];
                $batchCount = 0;
            }
            
            $currentDate->addDay();
        }
        
        // Insert remaining data
        if (!empty($dailyStatsBatch)) {
            DB::table('daily_visitor_stats')->insert($dailyStatsBatch);
        }
        if (!empty($visitorBatch)) {
            DB::table('visitors')->insert($visitorBatch);
        }
    }
    
    /**
     * Get a random user agent string for realistic visitor data
     */
    private function getRandomUserAgent(): string
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 13; SM-A546B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0',
            'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36',
        ];
        
        return $agents[array_rand($agents)];
    }
}
