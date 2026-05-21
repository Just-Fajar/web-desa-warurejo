<?php

namespace Tests\Unit\Models;

use App\Models\Publikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublikasiModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== SCOPES ====================

    public function test_scope_published()
    {
        Publikasi::factory()->create([
            'status' => 'published',
            'tanggal_publikasi' => now()->subDay(),
        ]);
        Publikasi::factory()->create(['status' => 'draft']);

        $this->assertCount(1, Publikasi::published()->get());
    }

    public function test_scope_draft()
    {
        Publikasi::factory()->create(['status' => 'draft']);
        Publikasi::factory()->create(['status' => 'published', 'tanggal_publikasi' => now()]);

        $this->assertCount(1, Publikasi::draft()->get());
    }

    public function test_scope_due_for_publishing()
    {
        // Note: The DB enum for status only allows 'draft' and 'published'.
        // The dueForPublishing scope filters by 'scheduled' which requires
        // a DB migration to add. We test at the query level instead.
        $pub1 = Publikasi::factory()->create([
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        // Directly update status to 'scheduled' bypassing enum check in SQLite
        DB::table('publikasis')->where('id', $pub1->id)->update(['status' => 'scheduled']);

        $pub2 = Publikasi::factory()->create([
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);
        DB::table('publikasis')->where('id', $pub2->id)->update(['status' => 'scheduled']);

        $this->assertCount(1, Publikasi::dueForPublishing()->get());
    }

    public function test_scope_by_kategori()
    {
        Publikasi::factory()->create(['kategori' => 'APBDes']);
        Publikasi::factory()->create(['kategori' => 'RPJMDes']);

        $this->assertCount(1, Publikasi::byKategori('APBDes')->get());
    }

    public function test_scope_by_tahun()
    {
        Publikasi::factory()->create(['tahun' => 2026]);
        Publikasi::factory()->create(['tahun' => 2025]);

        $this->assertCount(1, Publikasi::byTahun(2026)->get());
    }

    // ==================== ACCESSORS ====================

    public function test_file_url()
    {
        $pub = Publikasi::factory()->create(['file_dokumen' => 'publikasi/doc.pdf']);
        $this->assertStringContainsString('publikasi/doc.pdf', $pub->file_url);
    }

    public function test_thumbnail_url_with_thumbnail()
    {
        $pub = Publikasi::factory()->create(['thumbnail' => 'publikasi/thumb.jpg']);
        $this->assertStringContainsString('publikasi/thumb.jpg', $pub->thumbnail_url);
    }

    public function test_thumbnail_url_without_thumbnail()
    {
        $pub = Publikasi::factory()->create(['thumbnail' => null]);
        $this->assertStringContainsString('default-document', $pub->thumbnail_url);
    }

    public function test_status_badge_draft()
    {
        $pub = Publikasi::factory()->create(['status' => 'draft']);
        $this->assertEquals('yellow', $pub->status_badge['color']);
    }

    public function test_status_badge_scheduled()
    {
        $pub = Publikasi::factory()->create(['status' => 'published']);
        // Override status in memory to test the accessor
        $pub->status = 'scheduled';
        $this->assertEquals('blue', $pub->status_badge['color']);
    }

    public function test_status_badge_published()
    {
        $pub = Publikasi::factory()->create(['status' => 'published']);
        $this->assertEquals('green', $pub->status_badge['color']);
    }

    public function test_status_badge_unknown()
    {
        $pub = Publikasi::factory()->create();
        $pub->status = 'unknown';
        $this->assertEquals('gray', $pub->status_badge['color']);
    }

    // ==================== METHODS ====================

    public function test_increment_download()
    {
        $pub = Publikasi::factory()->create(['jumlah_download' => 5]);
        $pub->incrementDownload();
        $this->assertEquals(6, $pub->fresh()->jumlah_download);
    }

    public function test_increment_views()
    {
        $pub = Publikasi::factory()->create(['views' => 10]);
        $pub->incrementViews();
        $this->assertEquals(11, $pub->fresh()->views);
    }

    public function test_get_status_list()
    {
        $list = Publikasi::getStatusList();
        $this->assertCount(3, $list);
        $this->assertArrayHasKey('draft', $list);
        $this->assertArrayHasKey('scheduled', $list);
        $this->assertArrayHasKey('published', $list);
    }

    // ==================== CONSTANTS ====================

    public function test_status_constants()
    {
        $this->assertEquals('draft', Publikasi::STATUS_DRAFT);
        $this->assertEquals('scheduled', Publikasi::STATUS_SCHEDULED);
        $this->assertEquals('published', Publikasi::STATUS_PUBLISHED);
    }
}
