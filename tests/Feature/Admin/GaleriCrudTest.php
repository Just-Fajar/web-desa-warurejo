<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class GaleriCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    /**
     * Test guest cannot access admin galeri
     */
    public function test_guest_cannot_access_admin_galeri(): void
    {
        $response = $this->get(route('admin.galeri.index'));
        
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin can view galeri list
     */
    public function test_admin_can_view_galeri_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        Galeri::factory()->count(5)->create();
        
        $response = $this->get(route('admin.galeri.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.index');
        $response->assertViewHas('galeri');
    }

    /**
     * Test admin can view create galeri form
     */
    public function test_admin_can_view_create_galeri_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->get(route('admin.galeri.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.galeri.create');
    }

    /**
     * Test admin can create galeri with single image
     */
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
            'is_active' => true,
            'tanggal' => now()->format('Y-m-d'),
        ];
        
        $response = $this->post(route('admin.galeri.store'), $data);
        
        $response->assertRedirect(route('admin.galeri.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('galeri', [
            'judul' => 'Galeri Test',
            'kategori' => 'kegiatan',
            'is_active' => true,
        ]);
    }

    /**
     * Test admin can create galeri with multiple images
     */
    public function test_admin_can_create_galeri_with_multiple_images(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $data = [
            'judul' => 'Galeri Multiple',
            'deskripsi' => 'Test multiple images',
            'kategori' => 'infrastruktur',
            'images' => [
                UploadedFile::fake()->image('img1.jpg'),
                UploadedFile::fake()->image('img2.jpg'),
                UploadedFile::fake()->image('img3.jpg'),
            ],
            'is_active' => true,
            'tanggal' => now()->format('Y-m-d'),
        ];
        
        $response = $this->post(route('admin.galeri.store'), $data);
        
        $response->assertRedirect(route('admin.galeri.index'));
        
        $galeri = Galeri::first();
        $this->assertNotNull($galeri);
        
        // Check that galeri was created
        $this->assertEquals('Galeri Multiple', $galeri->judul);
    }

    /**
     * Test admin can update galeri
     */
    public function test_admin_can_update_galeri(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $galeri = Galeri::factory()->create([
            'judul' => 'Original Title'
        ]);
        
        $response = $this->put(route('admin.galeri.update', $galeri), [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated description',
            'kategori' => $galeri->kategori,
            'is_active' => true,
            'tanggal' => $galeri->tanggal->format('Y-m-d'),
        ]);
        
        $response->assertRedirect(route('admin.galeri.index'));
        
        $this->assertDatabaseHas('galeri', [
            'id' => $galeri->id,
            'judul' => 'Updated Title',
        ]);
    }

    /**
     * Test admin can delete galeri
     */
    public function test_admin_can_delete_galeri(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $galeri = Galeri::factory()->create();
        
        $response = $this->delete(route('admin.galeri.destroy', $galeri));
        
        $response->assertRedirect(route('admin.galeri.index'));
        
        $this->assertDatabaseMissing('galeri', ['id' => $galeri->id]);
    }

    /**
     * Test admin can bulk delete galeri
     */
    public function test_admin_can_bulk_delete_galeri(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $galeri1 = Galeri::factory()->create();
        $galeri2 = Galeri::factory()->create();
        $galeri3 = Galeri::factory()->create();
        
        $response = $this->post(route('admin.galeri.bulk-delete'), [
            'ids' => [$galeri1->id, $galeri2->id]
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseMissing('galeri', ['id' => $galeri1->id]);
        $this->assertDatabaseMissing('galeri', ['id' => $galeri2->id]);
        $this->assertDatabaseHas('galeri', ['id' => $galeri3->id]);
    }

    /**
     * Test galeri validation requires judul
     */
    public function test_galeri_validation_requires_judul(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.galeri.store'), [
            'deskripsi' => 'Test',
            'kategori' => 'kegiatan',
        ]);
        
        $response->assertSessionHasErrors('judul');
    }

    /**
     * Test galeri validation requires kategori
     */
    public function test_galeri_validation_requires_kategori(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test Galeri',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
        ]);
        
        $response->assertSessionHasErrors('kategori');
    }

    /**
     * Test galeri image validation
     */
    public function test_galeri_image_validation(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        // Test with non-image file
        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test',
            'kategori' => 'kegiatan',
            'images' => [
                UploadedFile::fake()->create('document.pdf', 1000),
            ],
            'is_active' => true,
            'tanggal' => now()->format('Y-m-d'),
        ]);
        
        $response->assertSessionHasErrors('images.0');
    }

    /**
     * Test only published galeri shown on public page
     */
    public function test_only_published_galeri_shown_on_public_page(): void
    {
        $active = Galeri::factory()->create(['is_active' => true]);
        $inactive = Galeri::factory()->create(['is_active' => false]);
        
        $response = $this->get(route('galeri.index'));
        
        $response->assertSee($active->judul);
        $response->assertDontSee($inactive->judul);
    }

    /**
     * Test galeri kategoris are valid enum values
     */
    public function test_galeri_kategori_enum_validation(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        // Test with invalid kategori
        $response = $this->post(route('admin.galeri.store'), [
            'judul' => 'Test',
            'kategori' => 'invalid_category',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
        ]);
        
        $response->assertSessionHasErrors('kategori');
    }
}
