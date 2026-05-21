<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaduanAdminTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_pengaduan_index()
    {
        $response = $this->get(route('admin.pengaduan.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_pengaduan_show()
    {
        $pengaduan = Pengaduan::factory()->create();
        $response = $this->get(route('admin.pengaduan.show', $pengaduan->id));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== ADMIN INDEX ====================

    public function test_admin_can_view_pengaduan_index()
    {
        Pengaduan::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengaduan.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.pengaduan.index');
        $response->assertViewHas('pengaduan');
    }

    public function test_admin_can_filter_pengaduan_by_status()
    {
        Pengaduan::factory()->menunggu()->create();
        Pengaduan::factory()->selesai()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengaduan.index', ['status' => 'Menunggu']));
        $response->assertStatus(200);
    }

    // ==================== ADMIN SHOW ====================

    public function test_admin_can_view_pengaduan_detail()
    {
        $pengaduan = Pengaduan::factory()->create([
            'nama_pelapor' => 'Budi Santoso',
            'nomor_wa' => '081234567890',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengaduan.show', $pengaduan->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.pengaduan.show');
        $response->assertSee('Budi Santoso');
        $response->assertSee('081234567890');
    }

    // ==================== ADMIN BALAS & UPDATE STATUS ====================

    public function test_admin_can_reply_with_isi_and_update_status()
    {
        $pengaduan = Pengaduan::factory()->menunggu()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'isi' => 'Terima kasih atas laporannya.',
                'status' => 'Diproses',
            ]);

        $response->assertRedirect(route('admin.pengaduan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengaduan_balasan', [
            'pengaduan_id' => $pengaduan->id,
            'isi' => 'Terima kasih atas laporannya.',
            'is_admin' => true,
        ]);

        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'Diproses',
        ]);
    }

    public function test_admin_can_update_status_without_isi()
    {
        $pengaduan = Pengaduan::factory()->menunggu()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'isi' => null,
                'status' => 'Diproses',
            ]);

        $response->assertRedirect(route('admin.pengaduan.index'));
        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'Diproses',
        ]);
        // No balasan should be created when isi is null and no lampiran
        $this->assertDatabaseCount('pengaduan_balasan', 0);
    }

    public function test_admin_can_update_status_to_selesai()
    {
        $pengaduan = Pengaduan::factory()->diproses()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'isi' => 'Masalah sudah diselesaikan.',
                'status' => 'Selesai',
            ]);

        $response->assertRedirect(route('admin.pengaduan.index'));
        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_admin_can_reject_with_alasan_penolakan()
    {
        $pengaduan = Pengaduan::factory()->menunggu()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'status' => 'Ditolak',
                'alasan_penolakan' => 'Pengaduan tidak valid.',
            ]);

        $response->assertRedirect(route('admin.pengaduan.index'));
        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'Ditolak',
            'alasan_penolakan' => 'Pengaduan tidak valid.',
        ]);
    }

    public function test_admin_can_upload_lampiran_balasan()
    {
        Storage::fake('public');
        $pengaduan = Pengaduan::factory()->menunggu()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'isi' => 'Bukti terlampir.',
                'status' => 'Diproses',
                'lampiran_balasan' => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertRedirect(route('admin.pengaduan.index'));
        $balasan = PengaduanBalasan::first();
        $this->assertNotNull($balasan->lampiran);
        Storage::disk('public')->assertExists($balasan->lampiran);
    }

    public function test_reply_fails_without_status()
    {
        $pengaduan = Pengaduan::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'isi' => 'Test',
            ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_reply_fails_with_invalid_status()
    {
        $pengaduan = Pengaduan::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengaduan.balas', $pengaduan->id), [
                'status' => 'InvalidStatus',
            ]);

        $response->assertSessionHasErrors('status');
    }

    // ==================== ADMIN DELETE ====================

    public function test_admin_can_delete_pengaduan()
    {
        Storage::fake('public');
        $pengaduan = Pengaduan::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.pengaduan.destroy', $pengaduan->id));

        $response->assertRedirect(route('admin.pengaduan.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('pengaduan', ['id' => $pengaduan->id]);
    }

    public function test_admin_delete_removes_lampiran_from_storage()
    {
        Storage::fake('public');
        Storage::disk('public')->put('pengaduan/test.jpg', 'dummy');

        $pengaduan = Pengaduan::factory()->create(['lampiran' => 'pengaduan/test.jpg']);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.pengaduan.destroy', $pengaduan->id));

        Storage::disk('public')->assertMissing('pengaduan/test.jpg');
    }

    // ==================== ADMIN BULK DELETE ====================

    public function test_admin_can_bulk_delete_pengaduan()
    {
        Storage::fake('public');
        $pengaduan1 = Pengaduan::factory()->create();
        $pengaduan2 = Pengaduan::factory()->create();
        $pengaduan3 = Pengaduan::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.pengaduan.bulk-delete'), [
                'ids' => [$pengaduan1->id, $pengaduan2->id],
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('pengaduan', ['id' => $pengaduan1->id]);
        $this->assertDatabaseMissing('pengaduan', ['id' => $pengaduan2->id]);
        $this->assertDatabaseHas('pengaduan', ['id' => $pengaduan3->id]);
    }

    public function test_bulk_delete_with_empty_ids()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.pengaduan.bulk-delete'), ['ids' => []]);

        $response->assertJson(['success' => false]);
    }
}
