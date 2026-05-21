<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\PotensiDesa;
use App\Models\PotensiDesaFoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PotensiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_admin_potensi(): void
    {
        $response = $this->get(route('admin.potensi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== INDEX ====================

    public function test_admin_can_view_potensi_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        PotensiDesa::factory()->count(3)->create();

        $response = $this->get(route('admin.potensi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.index');
        $response->assertViewHas('potensi');
    }

    // ==================== CREATE ====================

    public function test_admin_can_view_create_potensi_form(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.potensi.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.create');
    }

    public function test_admin_can_create_potensi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'nama' => 'Potensi Test',
            'kategori' => 'pertanian',
            'deskripsi' => '<p>Deskripsi potensi lengkap</p>',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'nama_pengelola' => 'Pak Tani',
            'whatsapp' => '081234567890',
            'status' => 'published',
        ];

        $response = $this->post(route('admin.potensi.store'), $data);

        $response->assertRedirect(route('admin.potensi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('potensi_desa', [
            'nama' => 'Potensi Test',
            'slug' => 'potensi-test',
        ]);
    }

    // ==================== EDIT ====================

    public function test_admin_can_view_edit_potensi_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();

        $response = $this->get(route('admin.potensi.edit', $potensi));

        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.edit');
        $response->assertViewHas('potensi');
    }

    // ==================== UPDATE ====================

    public function test_admin_can_update_potensi(): void
    {
        $this->actingAs($this->admin, 'admin');

        $potensi = PotensiDesa::factory()->create(['nama' => 'Original Name']);

        $response = $this->put(route('admin.potensi.update', $potensi), [
            'nama' => 'Updated Name',
            'kategori' => $potensi->kategori ?? 'pertanian',
            'deskripsi' => $potensi->deskripsi,
            'nama_pengelola' => $potensi->nama_pengelola ?? 'Pak Admin',
            'whatsapp' => $potensi->whatsapp ?? '081234567890',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.potensi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('potensi_desa', [
            'id' => $potensi->id,
            'nama' => 'Updated Name',
        ]);
    }

    // ==================== DELETE ====================

    public function test_admin_can_delete_potensi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();

        $response = $this->delete(route('admin.potensi.destroy', $potensi));

        $response->assertRedirect(route('admin.potensi.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('potensi_desa', ['id' => $potensi->id]);
    }

    public function test_admin_can_bulk_delete_potensi(): void
    {
        $this->actingAs($this->admin, 'admin');

        $potensi1 = PotensiDesa::factory()->create();
        $potensi2 = PotensiDesa::factory()->create();
        $potensi3 = PotensiDesa::factory()->create();

        $response = $this->postJson(route('admin.potensi.bulk-delete'), [
            'ids' => [$potensi1->id, $potensi2->id],
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('potensi_desa', ['id' => $potensi1->id]);
        $this->assertDatabaseMissing('potensi_desa', ['id' => $potensi2->id]);
        $this->assertDatabaseHas('potensi_desa', ['id' => $potensi3->id]);
    }

    // ==================== VALIDATION ====================

    public function test_potensi_validation_requires_nama(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.potensi.store'), [
            'deskripsi' => 'Test deskripsi',
            'kategori' => 'pertanian',
            'nama_pengelola' => 'Pak Admin',
            'whatsapp' => '081234567890',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('nama');
    }

    public function test_potensi_validation_requires_deskripsi(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.potensi.store'), [
            'nama' => 'Test Potensi',
            'kategori' => 'pertanian',
            'nama_pengelola' => 'Pak Admin',
            'whatsapp' => '081234567890',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('deskripsi');
    }

    // ==================== SLUG ====================

    public function test_slug_is_auto_generated_from_nama(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'nama' => 'Potensi Wisata Alam',
            'kategori' => 'wisata',
            'deskripsi' => '<p>Deskripsi</p>',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'nama_pengelola' => 'Pak Wisata',
            'whatsapp' => '081234567890',
            'status' => 'published',
        ];

        $this->post(route('admin.potensi.store'), $data);

        $this->assertDatabaseHas('potensi_desa', [
            'nama' => 'Potensi Wisata Alam',
            'slug' => 'potensi-wisata-alam',
        ]);
    }

    // ==================== PUBLIC ====================

    public function test_only_published_potensi_shown_on_public_page(): void
    {
        $active = PotensiDesa::factory()->active()->create();
        $inactive = PotensiDesa::factory()->inactive()->create();

        $response = $this->get(route('potensi.index'));

        $response->assertSee($active->nama);
        $response->assertDontSee($inactive->nama);
    }

    /**
     * Test admin can view potensi show page
     */
    public function test_admin_can_view_potensi_show_page(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();

        $response = $this->get(route('admin.potensi.show', $potensi));

        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.show');
        $response->assertViewHas('potensi');
    }

    /**
     * Test admin can view edit page with loaded photo galeri
     */
    public function test_admin_can_view_edit_page_with_photos(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();
        PotensiDesaFoto::create([
            'potensi_desa_id' => $potensi->id,
            'foto' => 'foto.jpg',
            'urutan' => 1,
        ]);

        $response = $this->get(route('admin.potensi.edit', $potensi));

        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.edit');
        $response->assertViewHas('potensi');
    }

    /**
     * Test admin can update potensi with new image & new gallery photos
     */
    public function test_admin_can_update_potensi_with_new_images(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create(['gambar' => 'old.jpg']);

        $response = $this->put(route('admin.potensi.update', $potensi), [
            'nama' => 'Updated Potensi',
            'kategori' => 'pertanian',
            'deskripsi' => '<p>Updated deskripsi</p>',
            'gambar' => UploadedFile::fake()->image('new.jpg'),
            'foto_galeri' => [
                UploadedFile::fake()->image('galeri1.jpg'),
                UploadedFile::fake()->image('galeri2.jpg'),
            ],
            'nama_pengelola' => 'Pak Tani',
            'whatsapp' => '81234567890',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.potensi.index'));
        $this->assertDatabaseHas('potensi_desa', [
            'id' => $potensi->id,
            'nama' => 'Updated Potensi',
        ]);
        $this->assertCount(2, $potensi->fresh()->fotoGaleri);
    }

    /**
     * Test bulk delete requires ids
     */
    public function test_admin_bulk_delete_potensi_requires_ids(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->postJson(route('admin.potensi.bulk-delete'), ['ids' => []]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Tidak ada potensi yang dipilih',
        ]);
    }

    /**
     * Test admin can delete single gallery photo
     */
    public function test_admin_can_delete_single_galeri_foto(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();
        $foto = PotensiDesaFoto::create([
            'potensi_desa_id' => $potensi->id,
            'foto' => 'foto.jpg',
            'urutan' => 1,
        ]);

        $response = $this->delete(route('admin.potensi.foto.delete', $foto->id));

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('potensi_desa_foto', ['id' => $foto->id]);
    }

    /**
     * Test store exception handles gracefully
     */
    public function test_store_exception_handles_gracefully(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('upload')->andThrow(new \Exception('Upload error'));
        });

        $response = $this->post(route('admin.potensi.store'), [
            'nama' => 'Potensi Error',
            'kategori' => 'pertanian',
            'deskripsi' => 'Deskripsi',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'nama_pengelola' => 'Pak Tani',
            'whatsapp' => '81234567890',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Upload error');
    }

    /**
     * Test update exception handles gracefully
     */
    public function test_update_exception_handles_gracefully(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create();

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->zeroOrMoreTimes()->andReturn(true);
            $mock->shouldReceive('upload')->andThrow(new \Exception('Upload error'));
        });

        $response = $this->put(route('admin.potensi.update', $potensi), [
            'nama' => 'Updated Potensi',
            'kategori' => 'pertanian',
            'deskripsi' => 'Deskripsi',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'nama_pengelola' => 'Pak Tani',
            'whatsapp' => '81234567890',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Upload error');
    }

    /**
     * Test destroy exception handles gracefully
     */
    public function test_destroy_exception_handles_gracefully(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create(['gambar' => 'potensi.jpg']);

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->andThrow(new \Exception('Delete error'));
        });

        $response = $this->delete(route('admin.potensi.destroy', $potensi));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Delete error');
    }

    /**
     * Test delete photo exception handles gracefully
     */
    public function test_delete_foto_exception_handles_gracefully(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Delete a non-existent ID to trigger Exception
        $response = $this->deleteJson(route('admin.potensi.foto.delete', 9999));

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Gagal menghapus foto: No query results for model [App\\Models\\PotensiDesaFoto] 9999',
        ]);
    }

    /**
     * Test bulk delete exception handles gracefully
     */
    public function test_bulk_delete_exception_handles_gracefully(): void
    {
        $this->actingAs($this->admin, 'admin');
        $potensi = PotensiDesa::factory()->create(['gambar' => 'potensi.jpg']);

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->andThrow(new \Exception('Delete error'));
        });

        $response = $this->postJson(route('admin.potensi.bulk-delete'), ['ids' => [$potensi->id]]);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Terjadi kesalahan: Delete error',
        ]);
    }
}
