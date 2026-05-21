<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIPS ====================

    public function test_galeri_belongs_to_admin()
    {
        $admin = Admin::factory()->create();
        $galeri = Galeri::factory()->create(['admin_id' => $admin->id]);

        $this->assertInstanceOf(Admin::class, $galeri->admin);
        $this->assertEquals($admin->id, $galeri->admin->id);
    }

    // ==================== SCOPES ====================

    public function test_scope_published()
    {
        Galeri::factory()->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        Galeri::factory()->create(['status' => 'draft']);

        $this->assertCount(1, Galeri::published()->get());
    }

    public function test_scope_published_includes_null_published_at()
    {
        Galeri::factory()->create([
            'status' => 'published',
            'published_at' => null,
        ]);

        $this->assertCount(1, Galeri::published()->get());
    }

    public function test_scope_draft()
    {
        Galeri::factory()->create(['status' => 'draft']);
        Galeri::factory()->create(['status' => 'published']);

        $this->assertCount(1, Galeri::draft()->get());
    }

    public function test_scope_scheduled()
    {
        Galeri::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->addDay(),
        ]);
        Galeri::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->subDay(),
        ]);

        $this->assertCount(1, Galeri::scheduled()->get());
    }

    public function test_scope_due_for_publishing()
    {
        Galeri::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->subHour(),
        ]);

        $this->assertCount(1, Galeri::dueForPublishing()->get());
    }

    public function test_scope_by_kategori()
    {
        Galeri::factory()->create(['kategori' => 'kegiatan']);
        Galeri::factory()->create(['kategori' => 'budaya']);

        $this->assertCount(1, Galeri::byKategori('kegiatan')->get());
    }

    // ==================== ACCESSORS ====================

    public function test_gambar_url_without_gambar()
    {
        $galeri = Galeri::factory()->create(['gambar' => null]);
        $this->assertStringContainsString('default-gallery', $galeri->gambar_url);
    }

    public function test_gambar_url_with_gambar()
    {
        $galeri = Galeri::factory()->create(['gambar' => 'galeri/test.jpg']);
        // Force no images relation loaded
        $galeri->setRelation('images', collect());
        $this->assertStringContainsString('galeri/test.jpg', $galeri->gambar_url);
    }

    public function test_status_badge_published()
    {
        $galeri = Galeri::factory()->create(['status' => 'published']);
        $this->assertEquals('green', $galeri->status_badge['color']);
    }

    public function test_status_badge_draft()
    {
        $galeri = Galeri::factory()->create(['status' => 'draft']);
        $this->assertEquals('yellow', $galeri->status_badge['color']);
    }

    public function test_status_badge_scheduled()
    {
        $galeri = Galeri::factory()->create(['status' => 'scheduled']);
        $this->assertEquals('blue', $galeri->status_badge['color']);
    }

    // ==================== METHODS ====================

    public function test_increment_views()
    {
        $galeri = Galeri::factory()->create(['views' => 5]);
        $galeri->incrementViews();
        $this->assertEquals(6, $galeri->fresh()->views);
    }

    // ==================== STATIC METHODS ====================

    public function test_get_kategori_list()
    {
        $list = Galeri::getKategoriList();
        $this->assertCount(6, $list);
        $this->assertArrayHasKey('kegiatan', $list);
        $this->assertArrayHasKey('pembangunan', $list);
        $this->assertArrayHasKey('budaya', $list);
        $this->assertArrayHasKey('keagamaan', $list);
        $this->assertArrayHasKey('sosial', $list);
        $this->assertArrayHasKey('lainnya', $list);
    }

    public function test_get_status_list()
    {
        $list = Galeri::getStatusList();
        $this->assertCount(3, $list);
    }

    // ==================== CONSTANTS ====================

    public function test_status_constants()
    {
        $this->assertEquals('draft', Galeri::STATUS_DRAFT);
        $this->assertEquals('scheduled', Galeri::STATUS_SCHEDULED);
        $this->assertEquals('published', Galeri::STATUS_PUBLISHED);
    }

    public function test_kategori_constants()
    {
        $this->assertEquals('kegiatan', Galeri::KATEGORI_KEGIATAN);
        $this->assertEquals('pembangunan', Galeri::KATEGORI_PEMBANGUNAN);
        $this->assertEquals('budaya', Galeri::KATEGORI_BUDAYA);
        $this->assertEquals('keagamaan', Galeri::KATEGORI_KEAGAMAAN);
        $this->assertEquals('sosial', Galeri::KATEGORI_SOSIAL);
        $this->assertEquals('lainnya', Galeri::KATEGORI_LAINNYA);
    }
}
