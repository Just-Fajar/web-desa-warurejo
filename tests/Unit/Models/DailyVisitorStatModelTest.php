<?php

namespace Tests\Unit\Models;

use App\Models\DailyVisitorStat;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DailyVisitorStatModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== SCOPE: betweenDates ====================

    public function test_scope_between_dates()
    {
        $today = Carbon::today()->toDateString();
        $tenDaysAgo = Carbon::today()->subDays(10)->toDateString();

        DB::table('daily_visitor_stats')->insert([
            'date' => $today,
            'unique_visitors' => 10,
            'page_views' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('daily_visitor_stats')->insert([
            'date' => $tenDaysAgo,
            'unique_visitors' => 5,
            'page_views' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $start = Carbon::today()->subDays(3)->toDateString();
        $end = $today;
        $result = DailyVisitorStat::betweenDates($start, $end)->get();
        $this->assertCount(1, $result);
        $this->assertEquals(10, $result->first()->unique_visitors);
    }

    // ==================== STATIC: todayStats ====================

    public function test_today_stats_creates_if_not_exist()
    {
        $stat = DailyVisitorStat::todayStats();
        $this->assertInstanceOf(DailyVisitorStat::class, $stat);
        $this->assertEquals(Carbon::today()->toDateString(), $stat->date->toDateString());
        $this->assertEquals(0, $stat->unique_visitors);
        $this->assertEquals(0, $stat->page_views);
    }

    public function test_today_stats_returns_existing()
    {
        // Use todayStats() to create, ensuring format consistency
        $created = DailyVisitorStat::todayStats();
        $created->update([
            'unique_visitors' => 25,
            'page_views' => 100,
        ]);

        $stat = DailyVisitorStat::todayStats();
        $this->assertEquals(25, $stat->unique_visitors);
        $this->assertEquals(100, $stat->page_views);
    }

    // ==================== CASTS ====================

    public function test_date_cast()
    {
        $stat = DailyVisitorStat::create([
            'date' => '2026-05-16',
            'unique_visitors' => 5,
            'page_views' => 10,
        ]);
        $this->assertInstanceOf(\Carbon\Carbon::class, $stat->date);
    }

    public function test_unique_visitors_is_integer()
    {
        $stat = DailyVisitorStat::create([
            'date' => '2026-05-15',
            'unique_visitors' => '15',
            'page_views' => '30',
        ]);
        $this->assertIsInt($stat->unique_visitors);
        $this->assertIsInt($stat->page_views);
    }
}
