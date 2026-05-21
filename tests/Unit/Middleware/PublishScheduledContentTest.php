<?php

namespace Tests\Unit\Middleware;

use App\Models\Admin;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\Publikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublishScheduledContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * Helper: Create a record with valid status, then update to 'scheduled' via DB
     * to bypass SQLite CHECK constraints on enum columns.
     */
    private function createScheduledBerita(array $overrides = []): Berita
    {
        $berita = Berita::factory()->create(array_merge([
            'status' => 'draft',
        ], $overrides));
        DB::table('berita')->where('id', $berita->id)->update([
            'status' => 'scheduled',
            'published_at' => $overrides['published_at'] ?? now()->subHour(),
        ]);
        return $berita->fresh();
    }

    private function createScheduledPublikasi(array $overrides = []): Publikasi
    {
        $pub = Publikasi::factory()->create(array_merge([
            'status' => 'draft',
        ], $overrides));
        DB::table('publikasis')->where('id', $pub->id)->update([
            'status' => 'scheduled',
            'published_at' => $overrides['published_at'] ?? now()->subHour(),
        ]);
        return $pub->fresh();
    }

    // ==================== AUTO-PUBLISH ====================

    public function test_berita_scheduled_past_gets_published()
    {
        $admin = Admin::factory()->create();
        $berita = $this->createScheduledBerita([
            'published_at' => now()->subHour(),
        ]);

        // Trigger middleware by accessing admin route
        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $this->assertEquals('published', $berita->fresh()->status);
    }

    public function test_berita_scheduled_future_stays_scheduled()
    {
        $admin = Admin::factory()->create();
        $berita = $this->createScheduledBerita([
            'published_at' => now()->addDay(),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $this->assertEquals('scheduled', $berita->fresh()->status);
    }

    public function test_galeri_scheduled_past_gets_published()
    {
        $admin = Admin::factory()->create();
        $galeri = Galeri::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->subHour(),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $this->assertEquals('published', $galeri->fresh()->status);
    }

    public function test_publikasi_scheduled_past_gets_published()
    {
        $admin = Admin::factory()->create();
        $pub = $this->createScheduledPublikasi([
            'published_at' => now()->subHour(),
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $this->assertEquals('published', $pub->fresh()->status);
    }

    // ==================== THROTTLE ====================

    public function test_middleware_is_throttled()
    {
        $admin = Admin::factory()->create();

        // First request triggers auto-publish
        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        // Create new scheduled content AFTER first request
        $berita = $this->createScheduledBerita([
            'published_at' => now()->subHour(),
        ]);

        // Second request within 60s should be throttled
        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        // Should still be scheduled because middleware was throttled
        $this->assertEquals('scheduled', $berita->fresh()->status);
    }
}
