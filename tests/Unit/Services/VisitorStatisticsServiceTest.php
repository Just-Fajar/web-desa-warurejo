<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\VisitorStatisticsService;
use App\Models\Visitor;
use App\Models\DailyVisitorStat;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VisitorStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $visitorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->visitorService = app(VisitorStatisticsService::class);
    }

    /**
     * Test get today visitors
     */
    public function test_get_today_visitors(): void
    {
        // Create visitors for today
        Visitor::create([
            'visit_date' => now()->toDateString(),
            'device_fingerprint' => 'fingerprint1',
            'ip_address' => '127.0.0.1',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now(),
        ]);

        Visitor::create([
            'visit_date' => now()->toDateString(),
            'device_fingerprint' => 'fingerprint2',
            'ip_address' => '127.0.0.2',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now(),
        ]);

        $count = $this->visitorService->getTodayVisitors();

        $this->assertEquals(2, $count);
    }

    /**
     * Test get weekly visitors
     */
    public function test_get_weekly_visitors(): void
    {
        // Create visitors for this week
        Visitor::create([
            'visit_date' => now()->subDays(3)->toDateString(),
            'device_fingerprint' => 'fingerprint1',
            'ip_address' => '127.0.0.1',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now()->subDays(3),
        ]);

        $count = $this->visitorService->getWeeklyVisitors();

        $this->assertGreaterThanOrEqual(1, $count);
    }

    /**
     * Test get monthly visitors
     */
    public function test_get_monthly_visitors(): void
    {
        // Create visitors for this month
        Visitor::create([
            'visit_date' => now()->subDays(10)->toDateString(),
            'device_fingerprint' => 'fingerprint1',
            'ip_address' => '127.0.0.1',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now()->subDays(10),
        ]);

        $count = $this->visitorService->getMonthlyVisitors();

        $this->assertGreaterThanOrEqual(1, $count);
    }

    /**
     * Test get total visitors returns correct count
     */
    public function test_get_total_visitors_returns_correct_count(): void
    {
        // Create some visitor records with different fingerprints
        for ($i = 1; $i <= 5; $i++) {
            Visitor::create([
                'visit_date' => now()->toDateString(),
                'device_fingerprint' => 'fingerprint' . $i,
                'ip_address' => '127.0.0.' . $i,
                'page_url' => '/test',
                'user_agent' => 'Mozilla/5.0',
                'last_visit_at' => now(),
            ]);
        }

        $total = $this->visitorService->getTotalVisitors();

        $this->assertEquals(5, $total);
    }

    /**
     * Test get chart data returns data
     */
    public function test_get_chart_data_returns_data(): void
    {
        // Create daily stats with correct column name 'date'
        DailyVisitorStat::create([
            'date' => now()->subDays(2)->toDateString(),
            'unique_visitors' => 8,
            'page_views' => 10,
        ]);

        DailyVisitorStat::create([
            'date' => now()->subDays(1)->toDateString(),
            'unique_visitors' => 12,
            'page_views' => 15,
        ]);

        $chartData = $this->visitorService->getChartData(7);

        $this->assertIsArray($chartData);
        $this->assertArrayHasKey('labels', $chartData);
        $this->assertArrayHasKey('visitors', $chartData);
        $this->assertArrayHasKey('pageViews', $chartData);
    }

    /**
     * Test aggregate yesterday stats
     */
    public function test_aggregate_yesterday_stats(): void
    {
        $yesterday = now()->subDay()->toDateString();

        // Create visitors for yesterday
        Visitor::create([
            'visit_date' => $yesterday,
            'device_fingerprint' => 'fingerprint1',
            'ip_address' => '127.0.0.1',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now()->subDay(),
        ]);

        Visitor::create([
            'visit_date' => $yesterday,
            'device_fingerprint' => 'fingerprint2',
            'ip_address' => '127.0.0.2',
            'page_url' => '/test',
            'user_agent' => 'Mozilla/5.0',
            'last_visit_at' => now()->subDay(),
        ]);

        $this->visitorService->aggregateYesterdayStats();

        // Check using whereDate to handle datetime comparison
        $stat = DailyVisitorStat::whereDate('date', $yesterday)->first();
        
        $this->assertNotNull($stat);
        $this->assertEquals(2, $stat->unique_visitors);
        $this->assertEquals(2, $stat->page_views);
    }
}
