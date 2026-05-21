<?php

namespace Tests\Unit\Models;

use App\Models\ProfilDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilDesaModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Reset singleton
        $reflection = new \ReflectionClass(ProfilDesa::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    // ==================== SINGLETON PATTERN ====================

    public function test_get_instance_creates_default_if_empty()
    {
        $profil = ProfilDesa::getInstance();
        $this->assertInstanceOf(ProfilDesa::class, $profil);
        $this->assertEquals('Desa Warurejo', $profil->nama_desa);
    }

    public function test_get_instance_returns_existing_record()
    {
        $profil1 = ProfilDesa::getInstance();
        // Reset singleton to test retrieval
        $reflection = new \ReflectionClass(ProfilDesa::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $profil2 = ProfilDesa::getInstance();
        $this->assertEquals($profil1->id, $profil2->id);
    }

    // ==================== PREVENT MULTIPLE RECORDS ====================

    public function test_cannot_create_second_record()
    {
        ProfilDesa::getInstance(); // creates first record

        $this->expectException(\Exception::class);
        ProfilDesa::create([
            'nama_desa' => 'Desa Lain',
            'kecamatan' => 'Test',
            'kabupaten' => 'Test',
            'provinsi' => 'Test',
        ]);
    }

    // ==================== PREVENT DELETE ====================

    public function test_delete_throws_exception()
    {
        $profil = ProfilDesa::getInstance();

        $this->expectException(\Exception::class);
        $profil->delete();
    }

    // ==================== ACCESSORS ====================

    public function test_logo_url_with_logo()
    {
        $profil = ProfilDesa::getInstance();
        $profil->update(['logo' => 'profil/logo.png']);
        $this->assertStringContainsString('profil/logo.png', $profil->logo_url);
    }

    public function test_logo_url_without_logo()
    {
        $profil = ProfilDesa::getInstance();
        $this->assertStringContainsString('default-logo', $profil->logo_url);
    }

    public function test_gambar_kantor_url_with_gambar()
    {
        $profil = ProfilDesa::getInstance();
        $profil->update(['gambar_kantor' => 'profil/kantor.jpg']);
        $this->assertStringContainsString('profil/kantor.jpg', $profil->gambar_kantor_url);
    }

    public function test_gambar_kantor_url_without_gambar()
    {
        $profil = ProfilDesa::getInstance();
        $this->assertStringContainsString('default-kantor', $profil->gambar_kantor_url);
    }

    public function test_alamat_lengkap_format()
    {
        $profil = ProfilDesa::getInstance();
        $format = $profil->alamat_lengkap_format;
        $this->assertStringContainsString('Balerejo', $format);
        $this->assertStringContainsString('Madiun', $format);
        $this->assertStringContainsString('Jawa Timur', $format);
    }

    public function test_misi_array_with_misi()
    {
        $profil = ProfilDesa::getInstance();
        $profil->update(['misi' => "Misi satu\nMisi dua\nMisi tiga"]);
        $misi = $profil->misi_array;
        $this->assertCount(3, $misi);
    }

    public function test_misi_array_with_empty_misi()
    {
        $profil = ProfilDesa::getInstance();
        $this->assertEquals([], $profil->misi_array);
    }
}
