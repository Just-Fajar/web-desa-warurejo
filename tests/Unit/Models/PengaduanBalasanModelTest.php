<?php

namespace Tests\Unit\Models;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaduanBalasanModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIP ====================

    public function test_balasan_belongs_to_pengaduan()
    {
        $pengaduan = Pengaduan::factory()->create();
        $balasan = PengaduanBalasan::factory()->create(['pengaduan_id' => $pengaduan->id]);

        $this->assertInstanceOf(Pengaduan::class, $balasan->pengaduan);
        $this->assertEquals($pengaduan->id, $balasan->pengaduan->id);
    }

    // ==================== ACCESSOR: lampiran_url ====================

    public function test_lampiran_url_with_lampiran()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => 'pengaduan/balasan/bukti.jpg']);
        $this->assertStringContainsString('pengaduan/balasan/bukti.jpg', $balasan->lampiran_url);
    }

    public function test_lampiran_url_without_lampiran()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => null]);
        $this->assertNull($balasan->lampiran_url);
    }

    // ==================== METHOD: isImage ====================

    public function test_is_image_with_jpg()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => 'foto.jpg']);
        $this->assertTrue($balasan->isImage());
    }

    public function test_is_image_with_jpeg()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => 'foto.jpeg']);
        $this->assertTrue($balasan->isImage());
    }

    public function test_is_image_with_png()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => 'foto.png']);
        $this->assertTrue($balasan->isImage());
    }

    public function test_is_image_with_pdf()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => 'doc.pdf']);
        $this->assertFalse($balasan->isImage());
    }

    public function test_is_image_with_null()
    {
        $balasan = PengaduanBalasan::factory()->create(['lampiran' => null]);
        $this->assertFalse($balasan->isImage());
    }

    // ==================== CASTS ====================

    public function test_is_admin_is_cast_to_boolean()
    {
        $balasan = PengaduanBalasan::factory()->create(['is_admin' => 1]);
        $this->assertIsBool($balasan->is_admin);
        $this->assertTrue($balasan->is_admin);
    }
}
