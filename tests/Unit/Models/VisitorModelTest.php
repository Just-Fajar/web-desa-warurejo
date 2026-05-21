<?php

namespace Tests\Unit\Models;

use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VisitorModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a visitor with all required fields.
     */
    private function createVisitor(array $overrides = []): Visitor
    {
        return Visitor::create(array_merge([
            'ip_address' => '127.0.0.'.rand(1, 254),
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp_'.uniqid(),
            'visit_date' => now()->toDateString(),
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
        ], $overrides));
    }

    // ==================== SCOPE: today ====================

    public function test_scope_today()
    {
        // Insert directly via DB to avoid date casting issues
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp1',
            'visit_date' => Carbon::today()->toDateString(),
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.2',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp2',
            'visit_date' => Carbon::yesterday()->toDateString(),
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertCount(1, Visitor::today()->get());
    }

    // ==================== SCOPE: betweenDates ====================

    public function test_scope_between_dates()
    {
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp1',
            'visit_date' => Carbon::today()->toDateString(),
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.2',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp2',
            'visit_date' => Carbon::today()->subDays(10)->toDateString(),
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $start = Carbon::today()->subDays(5)->toDateString();
        $end = Carbon::today()->toDateString();
        $this->assertCount(1, Visitor::betweenDates($start, $end)->get());
    }

    // ==================== STATIC: uniqueVisitorsCount ====================

    public function test_unique_visitors_count()
    {
        $today = Carbon::today()->toDateString();
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp1',
            'visit_date' => $today,
            'last_visit_at' => now(),
            'visit_count' => 3,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('visitors')->insert([
            'ip_address' => '127.0.0.2',
            'user_agent' => 'TestAgent/1.0',
            'device_fingerprint' => 'fp2',
            'visit_date' => $today,
            'last_visit_at' => now(),
            'visit_count' => 1,
            'page_url' => '/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $count = Visitor::uniqueVisitorsCount($today);
        $this->assertEquals(2, $count);
    }

    public function test_unique_visitors_count_without_date()
    {
        $this->createVisitor([
            'device_fingerprint' => 'fp1',
        ]);

        $count = Visitor::uniqueVisitorsCount();
        $this->assertEquals(1, $count);
    }

    // ==================== CASTS ====================

    public function test_visit_date_cast()
    {
        $visitor = $this->createVisitor([
            'visit_date' => '2026-05-16',
        ]);
        $this->assertInstanceOf(Carbon::class, $visitor->visit_date);
    }

    public function test_visit_count_is_integer()
    {
        $visitor = $this->createVisitor([
            'visit_count' => '5',
        ]);
        $this->assertIsInt($visitor->visit_count);
    }

    // ==================== FILLABLE ====================

    public function test_visitor_has_required_attributes()
    {
        $visitor = $this->createVisitor([
            'ip_address' => '192.168.1.1',
            'referer' => 'https://google.com',
        ]);

        $this->assertEquals('192.168.1.1', $visitor->ip_address);
        $this->assertNotNull($visitor->device_fingerprint);
        $this->assertNotNull($visitor->visit_date);
        $this->assertEquals('https://google.com', $visitor->referer);
    }
}
