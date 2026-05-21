<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GaleriCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_admin_galeri(): void
    {
        $response = $this->get(route('admin.galeri.index'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== INDEX ====================

    public function test_admin_can_view_galeri_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        Galeri::factory()->count(5)->create();

        $response = $this->get(route('admin.galeri.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.index');
        $response->assertViewHas('galeri');
    }

    // ==================== CREATE ====================

    public function test_admin_can_view_create_galeri_form(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.galeri.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.create');
    }

    public function test_admin_can_create_galeri_with_single_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'Galeri Test',
            'deskripsi' => 'Deskripsi galeri test',
            'kategori' => 'kegiatan',
            'images' => [
                UploadedFile::fake()->image('galeri.jpg', 1920, 1080),
            ],
            'status' => 'published',
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('admin.galeri.store'), $data);

        $response->assertRedirect(route('admin.galeri.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galeri', [
            'judul' => 'Galeri Test',
            'kategori' => 'kegiatan',
        ]);
    }

    public function test_admin_can_create_galeri_with_multiple_images(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'Galeri Multiple',
            'deskripsi' => 'Test multiple images',
            'kategori' => 'pembangunan',
            'images' => [
                UploadedFile::fake()->image('img1.jpg'),
                UploadedFile::fake()->image('img2.jpg'),
                UploadedFile::fake()->image('img3.jpg'),
            ],
            'status' => 'published',
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('admin.galeri.store'), $data);

        $response->assertRedirect(route('admin.galeri.index'));

        $galeri = Galeri::first();
        $this->assertNotNull($galeri);
        $this->assertEquals('Galeri Multiple', $galeri->judul);
    }

    // ==================== UPDATE ====================

    public function test_admin_can_update_galeri(): void
    {
        $this->actingAs($this->admin, 'admin');

        $galeri = Galeri::factory()->create(['judul' => 'Original Title']);

        $response = $this->put(route('admin.galeri.update', $galeri), [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated description',
            'kategori' => $galeri->kategori,
            'status' => 'published',
            'tanggal' => $galeri->tanggal->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('admin.galeri.index'));

        $this->assertDatabaseHas('galeri', [
            'id' => $galeri->id,
            'judul' => 'Updated Title',
        ]);
    }

    // ==================== DELETE ====================

    public function test_admin_can_delete_galeri(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create();

        $response = $this->delete(route('admin.galeri.destroy', $galeri));

        $response->assertRedirect(route('admin.galeri.index'));
        $this->assertDatabaseMissing('galeri', ['id' => $galeri->id]);
    }

    public function test_admin_can_bulk_delete_galeri(): void
    {
        $this->actingAs($this->admin, 'admin');

        $galeri1 = Galeri::factory()->create();
        $galeri2 = Galeri::factory()->create();
        $galeri3 = Galeri::factory()->create();

        $response = $this->postJson(route('admin.galeri.bulk-delete'), [
            'ids' => [$galeri1->id, $galeri2->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('galeri', ['id' => $galeri1->id]);
        $this->assertDatabaseMissing('galeri', ['id' => $galeri2->id]);
        $this->assertDatabaseHas('galeri', ['id' => $galeri3->id]);
    }

    // ==================== VALIDATION ====================

    public function test_galeri_validation_requires_judul(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.galeri.store'), [
            'deskripsi' => 'Test',
            'kategori' => 'kegiatan',
            'status' => 'draft',
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('judul');
    }

    public function test_galeri_validation_requires_kategori(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test Galeri',
            'status' => 'draft',
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    public function test_galeri_image_validation(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test',
            'kategori' => 'kegiatan',
            'images' => [
                UploadedFile::fake()->create('document.pdf', 1000),
            ],
            'status' => 'draft',
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('images.0');
    }

    public function test_only_published_galeri_shown_on_public_page(): void
    {
        $active = Galeri::factory()->create([
            'status' => 'published',
            'published_at' => now(),
        ]);
        $inactive = Galeri::factory()->create([
            'status' => 'draft',
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertSee($active->judul);
        $response->assertDontSee($inactive->judul);
    }

    public function test_galeri_kategori_enum_validation(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test',
            'kategori' => 'invalid_category',
            'status' => 'draft',
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    /**
     * Test admin can view galeri show page
     */
    public function test_admin_can_view_galeri_show_page(): void
    {
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create();

        $response = $this->get(route('admin.galeri.show', $galeri));

        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.show');
        $response->assertViewHas('galeri');
    }

    /**
     * Test admin can view galeri edit page
     */
    public function test_admin_can_view_galeri_edit_page(): void
    {
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create();

        $response = $this->get(route('admin.galeri.edit', $galeri));

        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.edit');
        $response->assertViewHas('galeri');
    }

    /**
     * Test admin can update galeri with a new image
     */
    public function test_admin_can_update_galeri_with_new_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $galeri = Galeri::factory()->create([
            'gambar' => 'old_image.jpg',
        ]);

        $response = $this->put(route('admin.galeri.update', $galeri), [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated description',
            'kategori' => $galeri->kategori,
            'status' => 'published',
            'tanggal' => $galeri->tanggal->format('Y-m-d'),
            'gambar' => UploadedFile::fake()->image('new_image.jpg'),
        ]);

        $response->assertRedirect(route('admin.galeri.index'));
        $this->assertDatabaseHas('galeri', [
            'id' => $galeri->id,
            'judul' => 'Updated Title',
        ]);
    }

    /**
     * Test bulk delete validation with empty ids
     */
    public function test_admin_bulk_delete_galeri_requires_ids(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->postJson(route('admin.galeri.bulk-delete'), ['ids' => []]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Tidak ada galeri yang dipilih',
        ]);
    }

    /**
     * Test store exception rollbacks database
     */
    public function test_store_exception_rolls_back(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('upload')->andThrow(new \Exception('Upload failed'));
        });

        $data = [
            'judul' => 'Galeri Exception',
            'deskripsi' => 'Deskripsi',
            'kategori' => 'kegiatan',
            'images' => [
                UploadedFile::fake()->image('galeri.jpg'),
            ],
            'status' => 'published',
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('admin.galeri.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Upload failed');

        $this->assertDatabaseMissing('galeri', [
            'judul' => 'Galeri Exception',
        ]);
    }

    /**
     * Test update exception is handled
     */
    public function test_update_handles_exception(): void
    {
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create();

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->zeroOrMoreTimes()->andReturn(true);
            $mock->shouldReceive('upload')->andThrow(new \Exception('Upload failed'));
        });

        $response = $this->put(route('admin.galeri.update', $galeri), [
            'judul' => 'Updated Title',
            'kategori' => $galeri->kategori,
            'status' => 'published',
            'tanggal' => $galeri->tanggal->format('Y-m-d'),
            'gambar' => UploadedFile::fake()->image('new_image.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Upload failed');
    }

    /**
     * Test destroy exception is handled
     */
    public function test_destroy_handles_exception(): void
    {
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create(['gambar' => 'image.jpg']);

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->andThrow(new \Exception('Delete failed'));
        });

        $response = $this->delete(route('admin.galeri.destroy', $galeri));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Terjadi kesalahan: Delete failed');
    }

    /**
     * Test bulk delete exception is handled
     */
    public function test_bulk_delete_handles_exception(): void
    {
        $this->actingAs($this->admin, 'admin');
        $galeri = Galeri::factory()->create(['gambar' => 'image.jpg']);

        $this->mock(\App\Services\ImageUploadService::class, function ($mock) {
            $mock->shouldReceive('delete')->andThrow(new \Exception('Delete failed'));
        });

        $response = $this->postJson(route('admin.galeri.bulk-delete'), ['ids' => [$galeri->id]]);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Terjadi kesalahan: Delete failed',
        ]);
    }
}
