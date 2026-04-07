<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BeritaCrudTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    /**
     * Test guest cannot access admin berita
     */
    public function test_guest_cannot_access_admin_berita(): void
    {
        $response = $this->get(route('admin.berita.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin can view berita list
     */
    public function test_admin_can_view_berita_list(): void
    {
        $this->actingAs($this->admin, 'admin');

        Berita::factory()->count(3)->create();

        $response = $this->get(route('admin.berita.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.berita.index');
        $response->assertViewHas('berita');
    }

    /**
     * Test admin can view create berita form
     */
    public function test_admin_can_view_create_berita_form(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.berita.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.berita.create');
    }

    /**
     * Test admin can create berita
     */
    public function test_admin_can_create_berita(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'Test Berita',
            'ringkasan' => 'Test ringkasan berita',
            'konten' => 'Test konten lengkap berita',
            'gambar_utama' => UploadedFile::fake()->image('test.jpg'),
            'status' => 'published',
            'published_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->post(route('admin.berita.store'), $data);

        $response->assertRedirect(route('admin.berita.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('berita', [
            'judul' => 'Test Berita',
            'slug' => 'test-berita',
            'status' => 'published',
        ]);
    }

    /**
     * Test admin can view edit berita form
     */
    public function test_admin_can_view_edit_berita_form(): void
    {
        $this->actingAs($this->admin, 'admin');

        $berita = Berita::factory()->create(['admin_id' => $this->admin->id]);

        $response = $this->get(route('admin.berita.edit', $berita));

        $response->assertStatus(200);
        $response->assertViewIs('admin.berita.edit');
        $response->assertViewHas('berita', $berita);
    }

    /**
     * Test admin can update berita
     */
    public function test_admin_can_update_berita(): void
    {
        $this->actingAs($this->admin, 'admin');

        $berita = Berita::factory()->create([
            'admin_id' => $this->admin->id,
            'judul' => 'Original Title',
        ]);

        $data = [
            'judul' => 'Updated Title',
            'ringkasan' => $berita->ringkasan,
            'konten' => $berita->konten,
            'status' => $berita->status,
        ];

        $response = $this->put(route('admin.berita.update', $berita), $data);

        $response->assertRedirect(route('admin.berita.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('berita', [
            'id' => $berita->id,
            'judul' => 'Updated Title',
            'slug' => 'updated-title',
        ]);
    }

    /**
     * Test admin can delete berita
     */
    public function test_admin_can_delete_berita(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'admin');

        $berita = Berita::factory()->create([
            'admin_id' => $this->admin->id,
        ]);

        $response = $this->delete(route('admin.berita.destroy', $berita));

        $response->assertRedirect(route('admin.berita.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
    }

    /**
     * Test admin can bulk delete berita
     */
    public function test_admin_can_bulk_delete_berita(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin, 'admin');

        $berita1 = Berita::factory()->create(['admin_id' => $this->admin->id]);
        $berita2 = Berita::factory()->create(['admin_id' => $this->admin->id]);

        $data = [
            'ids' => [$berita1->id, $berita2->id],
        ];

        $response = $this->post(route('admin.berita.bulk-delete'), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('berita', ['id' => $berita1->id]);
        $this->assertDatabaseMissing('berita', ['id' => $berita2->id]);
    }

    /**
     * Test berita validation requires judul
     */
    public function test_berita_validation_requires_judul(): void
    {
        $this->actingAs($this->admin, 'admin');

        $data = [
            'ringkasan' => 'Test ringkasan',
            'konten' => 'Test konten',
            'status' => 'draft',
        ];

        $response = $this->post(route('admin.berita.store'), $data);

        $response->assertSessionHasErrors('judul');
    }

    /**
     * Test berita validation requires konten
     */
    public function test_berita_validation_requires_konten(): void
    {
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'Test Berita',
            'ringkasan' => 'Test ringkasan',
            'status' => 'draft',
        ];

        $response = $this->post(route('admin.berita.store'), $data);

        $response->assertSessionHasErrors('konten');
    }

    /**
     * Test published berita requires published at
     * Note: This is now handled automatically by BeritaService
     */
    public function test_published_berita_requires_published_at(): void
    {
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'Test Berita',
            'ringkasan' => 'Test ringkasan',
            'konten' => 'Test konten',
            'status' => 'published',
            // published_at is now auto-set by service, so this test should pass
        ];

        $response = $this->post(route('admin.berita.store'), $data);

        // Should succeed because service auto-sets published_at
        $response->assertRedirect(route('admin.berita.index'));
        $this->assertDatabaseHas('berita', [
            'judul' => 'Test Berita',
            'status' => 'published',
        ]);
    }
}
