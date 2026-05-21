<?php

namespace Tests\Unit\Models;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaduanModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIPS ====================

    public function test_pengaduan_has_many_balasan()
    {
        $pengaduan = Pengaduan::factory()->create();
        PengaduanBalasan::factory()->count(3)->create(['pengaduan_id' => $pengaduan->id]);

        $this->assertCount(3, $pengaduan->balasan);
        $this->assertInstanceOf(PengaduanBalasan::class, $pengaduan->balasan->first());
    }

    // ==================== ACCESSOR: nama_sensor ====================

    public function test_nama_sensor_censors_long_words()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => 'Budi Santoso']);
        $this->assertEquals('Bu** Sa*****', $pengaduan->nama_sensor);
    }

    public function test_nama_sensor_keeps_short_words()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => 'Al']);
        $this->assertEquals('Al', $pengaduan->nama_sensor);
    }

    public function test_nama_sensor_single_char()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => 'X']);
        $this->assertEquals('X', $pengaduan->nama_sensor);
    }

    public function test_nama_sensor_three_words()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => 'Ahmad Budi Cahyono']);
        $this->assertEquals('Ah*** Bu** Ca*****', $pengaduan->nama_sensor);
    }

    public function test_nama_sensor_empty_string()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => '']);
        $this->assertEquals('', $pengaduan->nama_sensor);
    }

    // ==================== ACCESSOR: status_badge ====================

    public function test_status_badge_menunggu()
    {
        $pengaduan = Pengaduan::factory()->menunggu()->create();
        $badge = $pengaduan->status_badge;
        $this->assertEquals('yellow', $badge['color']);
        $this->assertEquals('Menunggu', $badge['label']);
    }

    public function test_status_badge_diproses()
    {
        $pengaduan = Pengaduan::factory()->diproses()->create();
        $badge = $pengaduan->status_badge;
        $this->assertEquals('blue', $badge['color']);
        $this->assertEquals('Diproses', $badge['label']);
    }

    public function test_status_badge_selesai()
    {
        $pengaduan = Pengaduan::factory()->selesai()->create();
        $badge = $pengaduan->status_badge;
        $this->assertEquals('green', $badge['color']);
        $this->assertEquals('Selesai', $badge['label']);
    }

    public function test_status_badge_ditolak()
    {
        $pengaduan = Pengaduan::factory()->ditolak()->create();
        $badge = $pengaduan->status_badge;
        $this->assertEquals('red', $badge['color']);
        $this->assertEquals('Ditolak', $badge['label']);
    }

    public function test_status_badge_unknown()
    {
        $pengaduan = Pengaduan::factory()->create();
        $pengaduan->status = 'unknown_value';
        $badge = $pengaduan->status_badge;
        $this->assertEquals('gray', $badge['color']);
        $this->assertEquals('Unknown', $badge['label']);
    }

    // ==================== ACCESSOR: lampiran_url ====================

    public function test_lampiran_url_with_lampiran()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/foto.jpg']);
        $this->assertStringContainsString('pengaduan/foto.jpg', $pengaduan->lampiran_url);
    }

    public function test_lampiran_url_without_lampiran()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => null]);
        $this->assertNull($pengaduan->lampiran_url);
    }

    // ==================== METHOD: isImage ====================

    public function test_is_image_with_jpg()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/foto.jpg']);
        $this->assertTrue($pengaduan->isImage());
    }

    public function test_is_image_with_jpeg()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/foto.jpeg']);
        $this->assertTrue($pengaduan->isImage());
    }

    public function test_is_image_with_png()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/foto.png']);
        $this->assertTrue($pengaduan->isImage());
    }

    public function test_is_image_with_pdf()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/doc.pdf']);
        $this->assertFalse($pengaduan->isImage());
    }

    public function test_is_image_with_null()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => null]);
        $this->assertFalse($pengaduan->isImage());
    }

    // ==================== METHOD: isPdf ====================

    public function test_is_pdf_with_pdf()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/doc.pdf']);
        $this->assertTrue($pengaduan->isPdf());
    }

    public function test_is_pdf_with_jpg()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/foto.jpg']);
        $this->assertFalse($pengaduan->isPdf());
    }

    public function test_is_pdf_with_null()
    {
        $pengaduan = Pengaduan::factory()->create(['lampiran' => null]);
        $this->assertFalse($pengaduan->isPdf());
    }

    // ==================== STATIC: getStatusList ====================

    public function test_get_status_list_returns_4_statuses()
    {
        $list = Pengaduan::getStatusList();
        $this->assertCount(4, $list);
        $this->assertArrayHasKey('Menunggu', $list);
        $this->assertArrayHasKey('Diproses', $list);
        $this->assertArrayHasKey('Selesai', $list);
        $this->assertArrayHasKey('Ditolak', $list);
    }

    // ==================== CONSTANTS ====================

    public function test_status_constants()
    {
        $this->assertEquals('Menunggu', Pengaduan::STATUS_MENUNGGU);
        $this->assertEquals('Diproses', Pengaduan::STATUS_DIPROSES);
        $this->assertEquals('Selesai', Pengaduan::STATUS_SELESAI);
        $this->assertEquals('Ditolak', Pengaduan::STATUS_DITOLAK);
    }

    // ==================== SCOPE: latest ====================

    public function test_scope_latest_orders_by_created_at_desc()
    {
        $old = Pengaduan::factory()->create(['created_at' => now()->subDays(2)]);
        $new = Pengaduan::factory()->create(['created_at' => now()]);

        $result = Pengaduan::latest()->get();
        $this->assertEquals($new->id, $result->first()->id);
    }
}
