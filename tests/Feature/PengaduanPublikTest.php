<?php

namespace Tests\Feature;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaduanPublikTest extends TestCase
{
    use RefreshDatabase;

    // ==================== HALAMAN LIST PUBLIK ====================

    public function test_public_can_view_pengaduan_index()
    {
        $response = $this->get(route('pengaduan.index'));
        $response->assertStatus(200);
    }

    public function test_pengaduan_index_displays_pengaduan()
    {
        $pengaduan = Pengaduan::factory()->create(['judul' => 'Jalan Rusak RT 05']);

        $response = $this->get(route('pengaduan.index'));
        $response->assertStatus(200);
        $response->assertSee('Jalan Rusak RT 05');
    }

    public function test_nama_pelapor_is_censored_on_public_page()
    {
        Pengaduan::factory()->create(['nama_pelapor' => 'Budi Santoso']);

        $response = $this->get(route('pengaduan.index'));
        $response->assertDontSee('Budi Santoso');
    }

    public function test_public_can_view_pengaduan_index_with_filters()
    {
        $p1 = Pengaduan::factory()->create([
            'judul' => 'Sampah menumpuk di pasar',
            'isi' => 'Isi laporan sampah',
            'lokasi_kejadian' => 'Pasar Desa',
            'status' => 'Menunggu',
            'created_at' => '2026-05-10 10:00:00',
        ]);
        $p2 = Pengaduan::factory()->create([
            'judul' => 'Saluran tersumbat RT 01',
            'isi' => 'Isi laporan saluran',
            'lokasi_kejadian' => 'RT 01',
            'status' => 'Diproses',
            'created_at' => '2026-05-20 10:00:00',
        ]);

        // Search filter
        $response = $this->get(route('pengaduan.index', ['search' => 'Sampah']));
        $response->assertSee('Sampah menumpuk');
        $response->assertDontSee('Saluran tersumbat');

        // Status filter
        $response = $this->get(route('pengaduan.index', ['status' => 'Diproses']));
        $response->assertSee('Saluran tersumbat');
        $response->assertDontSee('Sampah menumpuk');

        // Date range filter
        $response = $this->get(route('pengaduan.index', ['date_from' => '2026-05-15', 'date_to' => '2026-05-25']));
        $response->assertSee('Saluran tersumbat');
        $response->assertDontSee('Sampah menumpuk');

        // Sort filter: oldest
        $response = $this->get(route('pengaduan.index', ['sort' => 'oldest']));
        $response->assertStatus(200);

        // Sort filter: latest
        $response = $this->get(route('pengaduan.index', ['sort' => 'latest']));
        $response->assertStatus(200);
    }

    // ==================== HALAMAN DETAIL PUBLIK ====================

    public function test_public_can_view_pengaduan_detail()
    {
        $pengaduan = Pengaduan::factory()->create([
            'judul' => 'Lampu Jalan Mati',
            'isi' => 'Lampu jalan di RT 03 mati sejak minggu lalu.',
        ]);

        $response = $this->get(route('pengaduan.show', $pengaduan->id));
        $response->assertStatus(200);
        $response->assertSee('Lampu Jalan Mati');
        $response->assertSee('Lampu jalan di RT 03 mati sejak minggu lalu.');
    }

    public function test_nama_pelapor_is_censored_on_detail_page()
    {
        $pengaduan = Pengaduan::factory()->create(['nama_pelapor' => 'Budi Santoso']);

        $response = $this->get(route('pengaduan.show', $pengaduan->id));
        $response->assertDontSee('Budi Santoso');
    }

    public function test_admin_replies_are_visible_on_public_detail()
    {
        $pengaduan = Pengaduan::factory()->create();
        PengaduanBalasan::factory()->create([
            'pengaduan_id' => $pengaduan->id,
            'isi' => 'Terima kasih laporannya, kami sedang proses.',
            'is_admin' => true,
        ]);

        $response = $this->get(route('pengaduan.show', $pengaduan->id));
        $response->assertSee('Terima kasih laporannya, kami sedang proses.');
    }

    // ==================== FORM SUBMIT PENGADUAN ====================

    public function test_pengaduan_can_be_submitted_with_valid_data()
    {
        Storage::fake('public');

        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Ahmad Fauzi',
            'nomor_wa' => '081234567890',
            'judul' => 'Saluran Air Tersumbat',
            'isi' => 'Saluran air di depan rumah saya tersumbat.',
            'lokasi_kejadian' => 'RT 02 RW 01',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pengaduan', [
            'nama_pelapor' => 'Ahmad Fauzi',
            'judul' => 'Saluran Air Tersumbat',
            'status' => 'Menunggu',
        ]);
    }

    public function test_pengaduan_status_defaults_to_menunggu()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test User',
            'nomor_wa' => '081234567890',
            'judul' => 'Test Judul',
            'isi' => 'Test isi pengaduan.',
            'lokasi_kejadian' => 'Test Lokasi',
        ]);

        $this->assertDatabaseHas('pengaduan', [
            'status' => 'Menunggu',
        ]);
    }

    public function test_pengaduan_redirects_to_detail_after_submit()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test User',
            'nomor_wa' => '081234567890',
            'judul' => 'Test Judul',
            'isi' => 'Test isi.',
            'lokasi_kejadian' => 'Test Lokasi',
        ]);

        $pengaduan = Pengaduan::first();
        $response->assertRedirect(route('pengaduan.show', $pengaduan->id));
    }

    // ==================== VALIDASI FORM ====================

    public function test_submit_fails_without_nama_pelapor()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
        ]);
        $response->assertSessionHasErrors('nama_pelapor');
    }

    public function test_submit_fails_without_nomor_wa()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
        ]);
        $response->assertSessionHasErrors('nomor_wa');
    }

    public function test_submit_fails_without_judul()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
        ]);
        $response->assertSessionHasErrors('judul');
    }

    public function test_submit_fails_without_isi()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'lokasi_kejadian' => 'Test',
        ]);
        $response->assertSessionHasErrors('isi');
    }

    public function test_submit_fails_without_lokasi_kejadian()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
        ]);
        $response->assertSessionHasErrors('lokasi_kejadian');
    }

    public function test_submit_succeeds_without_lampiran()
    {
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
        ]);
        $response->assertSessionDoesntHaveErrors('lampiran');
        $this->assertDatabaseCount('pengaduan', 1);
    }

    public function test_submit_fails_with_exe_lampiran()
    {
        Storage::fake('public');
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
            'lampiran' => UploadedFile::fake()->create('virus.exe', 100),
        ]);
        $response->assertSessionHasErrors('lampiran');
    }

    public function test_submit_succeeds_with_jpg_lampiran()
    {
        Storage::fake('public');
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
            'lampiran' => UploadedFile::fake()->image('foto.jpg'),
        ]);
        $response->assertSessionDoesntHaveErrors('lampiran');
        $this->assertDatabaseCount('pengaduan', 1);
    }

    public function test_submit_succeeds_with_pdf_lampiran()
    {
        Storage::fake('public');
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
            'lampiran' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ]);
        $response->assertSessionDoesntHaveErrors('lampiran');
    }

    public function test_submit_fails_with_oversized_lampiran()
    {
        Storage::fake('public');
        $response = $this->post(route('pengaduan.store'), [
            'nama_pelapor' => 'Test',
            'nomor_wa' => '081234567890',
            'judul' => 'Test',
            'isi' => 'Test',
            'lokasi_kejadian' => 'Test',
            'lampiran' => UploadedFile::fake()->create('big.jpg', 6000),
        ]);
        $response->assertSessionHasErrors('lampiran');
    }

    // ==================== PROTEKSI BALASAN ====================

    public function test_guest_cannot_reply_to_pengaduan()
    {
        $pengaduan = Pengaduan::factory()->create();

        $response = $this->post(route('admin.pengaduan.balas', $pengaduan->id), [
            'isi' => 'Test',
            'status' => 'Diproses',
        ]);
        $response->assertRedirect(route('admin.login'));
    }
}
