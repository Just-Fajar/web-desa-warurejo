<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\StrukturOrganisasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StrukturOrganisasiCrudTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_index()
    {
        $response = $this->get(route('admin.struktur-organisasi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_create()
    {
        $response = $this->get(route('admin.struktur-organisasi.create'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== INDEX ====================

    public function test_admin_can_view_index()
    {
        StrukturOrganisasi::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.struktur-organisasi.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.struktur-organisasi.index');
        $response->assertViewHas('strukturOrganisasi');
        $response->assertViewHas('levels');
    }

    // ==================== CREATE & STORE ====================

    public function test_admin_can_view_create_form()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.struktur-organisasi.create'));
        $response->assertStatus(200);
        $response->assertViewHas('levels');
    }

    public function test_admin_can_store_with_valid_data()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Budi Hartono',
                'jabatan' => 'Kepala Desa',
                'level' => 'kepala',
                'is_active' => true,
                'urutan' => 1,
            ]);

        $response->assertRedirect(route('admin.struktur-organisasi.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('struktur_organisasi', [
            'nama' => 'Budi Hartono',
            'jabatan' => 'Kepala Desa',
            'level' => 'kepala',
        ]);
    }

    public function test_admin_can_store_with_foto()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Ahmad Fauzi',
                'jabatan' => 'Sekretaris Desa',
                'level' => 'sekretaris',
                'is_active' => true,
                'foto' => UploadedFile::fake()->image('photo.jpg', 800, 800),
            ]);

        $response->assertRedirect(route('admin.struktur-organisasi.index'));
        $response->assertSessionHas('success');
    }

    // ==================== VALIDATION ====================

    public function test_store_fails_without_nama()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'jabatan' => 'Kepala Desa',
                'level' => 'kepala',
                'is_active' => true,
            ]);
        $response->assertSessionHasErrors('nama');
    }

    public function test_store_fails_without_jabatan()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Test',
                'level' => 'kepala',
                'is_active' => true,
            ]);
        $response->assertSessionHasErrors('jabatan');
    }

    public function test_store_fails_without_level()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Test',
                'jabatan' => 'Test',
                'is_active' => true,
            ]);
        $response->assertSessionHasErrors('level');
    }

    public function test_store_fails_with_invalid_level()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Test',
                'jabatan' => 'Test',
                'level' => 'invalid_level',
                'is_active' => true,
            ]);
        $response->assertSessionHasErrors('level');
    }

    public function test_store_fails_without_is_active()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Test',
                'jabatan' => 'Test',
                'level' => 'kepala',
            ]);
        $response->assertSessionHasErrors('is_active');
    }

    public function test_store_fails_with_non_image_foto()
    {
        Storage::fake('public');
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.struktur-organisasi.store'), [
                'nama' => 'Test',
                'jabatan' => 'Test',
                'level' => 'kepala',
                'is_active' => true,
                'foto' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
            ]);
        $response->assertSessionHasErrors('foto');
    }

    // ==================== EDIT & UPDATE ====================

    public function test_admin_can_view_edit_form()
    {
        $item = StrukturOrganisasi::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.struktur-organisasi.edit', $item->id));
        $response->assertStatus(200);
        $response->assertViewHas('strukturOrganisasi');
    }

    public function test_admin_can_update_with_valid_data()
    {
        Storage::fake('public');
        $item = StrukturOrganisasi::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.struktur-organisasi.update', $item->id), [
                'nama' => 'Nama Updated',
                'jabatan' => 'Jabatan Updated',
                'level' => 'sekretaris',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.struktur-organisasi.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('struktur_organisasi', [
            'id' => $item->id,
            'nama' => 'Nama Updated',
        ]);
    }

    // ==================== DELETE ====================

    public function test_admin_can_delete()
    {
        Storage::fake('public');
        $item = StrukturOrganisasi::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.struktur-organisasi.destroy', $item->id));

        $response->assertRedirect(route('admin.struktur-organisasi.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('struktur_organisasi', ['id' => $item->id]);
    }

    // ==================== BULK DELETE ====================

    public function test_admin_can_bulk_delete()
    {
        Storage::fake('public');
        $item1 = StrukturOrganisasi::factory()->create();
        $item2 = StrukturOrganisasi::factory()->create();
        $item3 = StrukturOrganisasi::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.struktur-organisasi.bulk-delete'), [
                'ids' => [$item1->id, $item2->id],
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('struktur_organisasi', ['id' => $item1->id]);
        $this->assertDatabaseMissing('struktur_organisasi', ['id' => $item2->id]);
        $this->assertDatabaseHas('struktur_organisasi', ['id' => $item3->id]);
    }

    public function test_bulk_delete_with_empty_ids()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.struktur-organisasi.bulk-delete'), ['ids' => []]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }
}
