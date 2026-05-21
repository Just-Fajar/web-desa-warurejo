<?php

namespace Tests\Unit\Models;

use App\Models\StrukturOrganisasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StrukturOrganisasiModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIPS ====================

    public function test_atasan_relationship()
    {
        $kepala = StrukturOrganisasi::factory()->kepala()->create();
        $sekretaris = StrukturOrganisasi::factory()->sekretaris()->create(['atasan_id' => $kepala->id]);

        $this->assertInstanceOf(StrukturOrganisasi::class, $sekretaris->atasan);
        $this->assertEquals($kepala->id, $sekretaris->atasan->id);
    }

    public function test_bawahan_relationship()
    {
        $kepala = StrukturOrganisasi::factory()->kepala()->create();
        StrukturOrganisasi::factory()->sekretaris()->create(['atasan_id' => $kepala->id]);
        StrukturOrganisasi::factory()->kaur()->create(['atasan_id' => $kepala->id]);

        $this->assertCount(2, $kepala->bawahan);
    }

    // ==================== ACCESSORS ====================

    public function test_foto_url_with_foto()
    {
        $item = StrukturOrganisasi::factory()->create(['foto' => 'struktur/foto.jpg']);
        $this->assertStringContainsString('struktur/foto.jpg', $item->foto_url);
    }

    public function test_foto_url_without_foto()
    {
        $item = StrukturOrganisasi::factory()->create(['foto' => null]);
        $this->assertStringContainsString('default-avatar', $item->foto_url);
    }

    public function test_level_label_kepala()
    {
        $item = StrukturOrganisasi::factory()->kepala()->create();
        $this->assertEquals('Kepala Desa', $item->level_label);
    }

    public function test_level_label_sekretaris()
    {
        $item = StrukturOrganisasi::factory()->sekretaris()->create();
        $this->assertEquals('Sekretaris Desa', $item->level_label);
    }

    public function test_level_label_unknown_returns_level_value()
    {
        $item = StrukturOrganisasi::factory()->create();
        $item->level = 'unknown_level';
        $this->assertEquals('unknown_level', $item->level_label);
    }

    // ==================== SCOPES ====================

    public function test_scope_active()
    {
        StrukturOrganisasi::factory()->active()->create();
        StrukturOrganisasi::factory()->inactive()->create();

        $this->assertCount(1, StrukturOrganisasi::active()->get());
    }

    public function test_scope_by_level()
    {
        StrukturOrganisasi::factory()->kepala()->create();
        StrukturOrganisasi::factory()->sekretaris()->create();

        $result = StrukturOrganisasi::byLevel('kepala')->get();
        $this->assertCount(1, $result);
        $this->assertEquals('kepala', $result->first()->level);
    }

    public function test_scope_kepala()
    {
        StrukturOrganisasi::factory()->kepala()->create();
        StrukturOrganisasi::factory()->sekretaris()->create();

        $this->assertCount(1, StrukturOrganisasi::kepala()->get());
    }

    public function test_scope_sekretaris()
    {
        StrukturOrganisasi::factory()->sekretaris()->create();
        $this->assertCount(1, StrukturOrganisasi::sekretaris()->get());
    }

    public function test_scope_kaur()
    {
        StrukturOrganisasi::factory()->kaur()->create();
        $this->assertCount(1, StrukturOrganisasi::kaur()->get());
    }

    public function test_scope_kasi()
    {
        StrukturOrganisasi::factory()->kasi()->create();
        $this->assertCount(1, StrukturOrganisasi::kasi()->get());
    }

    // ==================== STATIC METHOD ====================

    public function test_get_levels_returns_6_levels()
    {
        $levels = StrukturOrganisasi::getLevels();
        $this->assertCount(6, $levels);
        $this->assertArrayHasKey('kepala', $levels);
        $this->assertArrayHasKey('sekretaris', $levels);
        $this->assertArrayHasKey('kaur', $levels);
        $this->assertArrayHasKey('staff_kaur', $levels);
        $this->assertArrayHasKey('kasi', $levels);
        $this->assertArrayHasKey('staff_kasi', $levels);
    }

    // ==================== CONSTANTS ====================

    public function test_level_constants()
    {
        $this->assertEquals('kepala', StrukturOrganisasi::LEVEL_KEPALA);
        $this->assertEquals('sekretaris', StrukturOrganisasi::LEVEL_SEKRETARIS);
        $this->assertEquals('kaur', StrukturOrganisasi::LEVEL_KAUR);
        $this->assertEquals('staff_kaur', StrukturOrganisasi::LEVEL_STAFF_KAUR);
        $this->assertEquals('kasi', StrukturOrganisasi::LEVEL_KASI);
        $this->assertEquals('staff_kasi', StrukturOrganisasi::LEVEL_STAFF_KASI);
    }

    // ==================== CASTS ====================

    public function test_is_active_is_cast_to_boolean()
    {
        $item = StrukturOrganisasi::factory()->create(['is_active' => 1]);
        $this->assertIsBool($item->is_active);
    }

    public function test_urutan_is_cast_to_integer()
    {
        $item = StrukturOrganisasi::factory()->create(['urutan' => '5']);
        $this->assertIsInt($item->urutan);
    }
}
