<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\DailyVisitorStat;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();

        // Register strftime as YEAR function for SQLite compatibility
        if (DB::getDriverName() === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateFunction('YEAR', function ($date) {
                return date('Y', strtotime($date));
            }, 1);
            DB::connection()->getPdo()->sqliteCreateFunction('MONTH', function ($date) {
                return date('m', strtotime($date));
            }, 1);
        }
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== ADMIN INDEX ====================

    public function test_admin_can_view_dashboard()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard.index');
    }

    public function test_dashboard_has_required_view_data()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertViewHas('totalBerita');
        $response->assertViewHas('totalPotensi');
        $response->assertViewHas('totalGaleri');
        $response->assertViewHas('totalPublikasi');
        $response->assertViewHas('totalPengaduan');
        $response->assertViewHas('pengunjungHariIni');
        $response->assertViewHas('totalPengunjung');
        $response->assertViewHas('visitorChartData');
    }

    // ==================== AJAX ENDPOINTS ====================

    public function test_admin_can_get_visitor_chart_data()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.dashboard.visitor-chart', ['year' => 2026]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_can_get_content_chart_data()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.dashboard.content-chart', ['year' => 2026]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_can_get_visitors_by_period()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.dashboard.visitor-period', ['month' => 5, 'year' => 2026]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_guest_cannot_access_visitor_chart()
    {
        $response = $this->getJson(route('admin.dashboard.visitor-chart'));
        // Auth middleware redirects to login, which returns 302
        $response->assertStatus(302);
    }
}
