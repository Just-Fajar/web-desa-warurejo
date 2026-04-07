<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\PotensiDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PotensiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    /**
     * Test guest cannot access admin potensi
     */
    public function test_guest_cannot_access_admin_potensi(): void
    {
        $response = $this->get(route('admin.potensi.index'));
        
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin can view potensi list
     */
    public function test_admin_can_view_potensi_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        PotensiDesa::factory()->count(3)->create();
        
        $response = $this->get(route('admin.potensi.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.index');
        $response->assertViewHas('potensi');
    }

    /**
     * Test admin can view create potensi form
     */
    public function test_admin_can_view_create_potensi_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->get(route('admin.potensi.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.create');
    }

    /**
     * Test admin can create potensi
     */
    public function test_admin_can_create_potensi(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $data = [
            'nama' => 'Potensi Test',
            'deskripsi' => '<p>Deskripsi potensi lengkap</p>',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'is_active' => true,
        ];
        
        $response = $this->post(route('admin.potensi.store'), $data);
        
        $response->assertRedirect(route('admin.potensi.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('potensi_desa', [
            'nama' => 'Potensi Test',
            'slug' => 'potensi-test',
            'is_active' => true,
        ]);
        
        Storage::disk('public')->assertExists('potensi/' . PotensiDesa::first()->gambar);
    }

    /**
     * Test admin can view edit potensi form
     */
    public function test_admin_can_view_edit_potensi_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $potensi = PotensiDesa::factory()->create();
        
        $response = $this->get(route('admin.potensi.edit', $potensi));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.potensi.edit');
        $response->assertViewHas('potensi', $potensi);
    }

    /**
     * Test admin can update potensi
     */
    public function test_admin_can_update_potensi(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $potensi = PotensiDesa::factory()->create([
            'nama' => 'Original Name'
        ]);
        
        $response = $this->put(route('admin.potensi.update', $potensi), [
            'nama' => 'Updated Name',
            'deskripsi' => $potensi->deskripsi,
            'is_active' => true,
        ]);
        
        $response->assertRedirect(route('admin.potensi.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('potensi_desa', [
            'id' => $potensi->id,
            'nama' => 'Updated Name',
        ]);
    }

    /**
     * Test admin can delete potensi
     */
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

    /**
     * Test admin can bulk delete potensi
     */
    public function test_admin_can_bulk_delete_potensi(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $potensi1 = PotensiDesa::factory()->create();
        $potensi2 = PotensiDesa::factory()->create();
        $potensi3 = PotensiDesa::factory()->create();
        
        $response = $this->post(route('admin.potensi.bulk-delete'), [
            'ids' => [$potensi1->id, $potensi2->id]
        ]);
        
        $response->assertRedirect(route('admin.potensi.index'));
        
        $this->assertDatabaseMissing('potensi_desa', ['id' => $potensi1->id]);
        $this->assertDatabaseMissing('potensi_desa', ['id' => $potensi2->id]);
        $this->assertDatabaseHas('potensi_desa', ['id' => $potensi3->id]);
    }

    /**
     * Test potensi validation requires nama
     */
    public function test_potensi_validation_requires_nama(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.potensi.store'), [
            'deskripsi' => 'Test deskripsi',
            'is_active' => true,
        ]);
        
        $response->assertSessionHasErrors('nama');
    }

    /**
     * Test potensi validation requires deskripsi
     */
    public function test_potensi_validation_requires_deskripsi(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.potensi.store'), [
            'nama' => 'Test Potensi',
            'is_active' => true,
        ]);
        
        $response->assertSessionHasErrors('deskripsi');
    }

    /**
     * Test slug is auto generated from nama
     */
    public function test_slug_is_auto_generated_from_nama(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $data = [
            'nama' => 'Potensi Wisata Alam',
            'deskripsi' => '<p>Deskripsi</p>',
            'gambar' => UploadedFile::fake()->image('potensi.jpg'),
            'is_active' => true,
        ];
        
        $response = $this->post(route('admin.potensi.store'), $data);
        
        $this->assertDatabaseHas('potensi_desa', [
            'nama' => 'Potensi Wisata Alam',
            'slug' => 'potensi-wisata-alam',
        ]);
    }

    /**
     * Test only active potensi shown on public page
     */
    public function test_only_active_potensi_shown_on_public_page(): void
    {
        $active = PotensiDesa::factory()->create(['is_active' => true]);
        $inactive = PotensiDesa::factory()->create(['is_active' => false]);
        
        $response = $this->get(route('potensi.index'));
        
        $response->assertSee($active->nama);
        $response->assertDontSee($inactive->nama);
    }
}
