<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BeritaModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIP ====================

    public function test_berita_belongs_to_admin()
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()->create(['admin_id' => $admin->id]);

        $this->assertInstanceOf(Admin::class, $berita->admin);
        $this->assertEquals($admin->id, $berita->admin->id);
    }

    // ==================== ACCESSORS ====================

    public function test_gambar_utama_url_with_gambar()
    {
        $berita = Berita::factory()->create(['gambar_utama' => 'berita/foto.jpg']);
        $this->assertStringContainsString('berita/foto.jpg', $berita->gambar_utama_url);
    }

    public function test_gambar_utama_url_without_gambar()
    {
        $berita = Berita::factory()->create(['gambar_utama' => null]);
        $this->assertStringContainsString('logo-web-desa.jpg', $berita->gambar_utama_url);
    }

    public function test_excerpt_returns_ringkasan_if_exists()
    {
        $berita = Berita::factory()->create(['ringkasan' => 'Ringkasan singkat.']);
        $this->assertEquals('Ringkasan singkat.', $berita->excerpt);
    }

    public function test_excerpt_generates_from_konten_if_no_ringkasan()
    {
        $berita = Berita::factory()->create([
            'ringkasan' => null,
            'konten' => '<p>Ini adalah konten yang sangat panjang.</p>',
        ]);
        $this->assertStringNotContainsString('<p>', $berita->excerpt);
    }

    public function test_status_badge_published()
    {
        $berita = Berita::factory()->create(['status' => 'published']);
        $this->assertEquals('green', $berita->status_badge['color']);
        $this->assertEquals('Published', $berita->status_badge['label']);
    }

    public function test_status_badge_draft()
    {
        $berita = Berita::factory()->create(['status' => 'draft']);
        $this->assertEquals('yellow', $berita->status_badge['color']);
    }

    public function test_status_badge_scheduled()
    {
        $berita = Berita::factory()->create(['status' => 'published']);
        // Override status in memory to test the accessor
        $berita->status = 'scheduled';
        $this->assertEquals('blue', $berita->status_badge['color']);
    }

    public function test_status_badge_unknown()
    {
        $berita = Berita::factory()->create();
        $berita->status = 'unknown';
        $this->assertEquals('gray', $berita->status_badge['color']);
    }

    // ==================== SCOPES ====================

    public function test_scope_published()
    {
        Berita::factory()->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        Berita::factory()->create(['status' => 'draft']);

        $this->assertCount(1, Berita::published()->get());
    }

    public function test_scope_draft()
    {
        Berita::factory()->create(['status' => 'draft']);
        Berita::factory()->create(['status' => 'published', 'published_at' => now()]);

        $this->assertCount(1, Berita::draft()->get());
    }

    public function test_scope_due_for_publishing()
    {
        // Create with valid status first, then update via DB to bypass enum check
        $b1 = Berita::factory()->create([
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);
        DB::table('berita')->where('id', $b1->id)->update(['status' => 'scheduled']);

        $b2 = Berita::factory()->create([
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);
        DB::table('berita')->where('id', $b2->id)->update(['status' => 'scheduled']);

        $this->assertCount(1, Berita::dueForPublishing()->get());
    }

    // ==================== STATIC METHODS ====================

    public function test_get_status_list()
    {
        $list = Berita::getStatusList();
        $this->assertCount(3, $list);
        $this->assertArrayHasKey('draft', $list);
        $this->assertArrayHasKey('scheduled', $list);
        $this->assertArrayHasKey('published', $list);
    }

    // ==================== BOOT: slug generation ====================

    public function test_slug_is_auto_generated_from_judul()
    {
        $admin = Admin::factory()->create();
        // Create berita directly without factory (which pre-generates slug)
        $berita = Berita::create([
            'admin_id' => $admin->id,
            'judul' => 'Berita Terbaru Hari Ini',
            'konten' => '<p>Konten berita terbaru.</p>',
            'status' => 'draft',
        ]);
        $this->assertEquals('berita-terbaru-hari-ini', $berita->slug);
    }

    public function test_slug_is_unique()
    {
        $berita1 = Berita::factory()->create(['judul' => 'Same Title']);
        $berita2 = Berita::factory()->create(['judul' => 'Same Title']);

        $this->assertNotEquals($berita1->slug, $berita2->slug);
    }

    // ==================== CONSTANTS ====================

    public function test_status_constants()
    {
        $this->assertEquals('draft', Berita::STATUS_DRAFT);
        $this->assertEquals('scheduled', Berita::STATUS_SCHEDULED);
        $this->assertEquals('published', Berita::STATUS_PUBLISHED);
    }
}
