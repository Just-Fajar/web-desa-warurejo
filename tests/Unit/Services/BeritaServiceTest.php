<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Berita;
use App\Services\BeritaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BeritaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BeritaService $beritaService;
    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beritaService = app(BeritaService::class);
        $this->admin = Admin::factory()->create();
    }

    /**
     * Test create berita generates slug automatically
     */
    public function test_create_berita_generates_slug_automatically(): void
    {
        $data = [
            'admin_id' => $this->admin->id,
            'judul' => 'Test Berita Slug',
            'konten' => 'Content here',
            'status' => 'draft',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertEquals('test-berita-slug', $berita->slug);
    }

    /**
     * Test create berita sanitizes HTML content
     */
    public function test_create_berita_sanitizes_html_content(): void
    {
        $data = [
            'admin_id' => $this->admin->id,
            'judul' => 'Test Sanitization',
            'konten' => '<p>Safe content</p><script>alert("xss")</script>',
            'status' => 'draft',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertStringNotContainsString('<script>', $berita->konten);
        $this->assertStringContainsString('<p>Safe content</p>', $berita->konten);
    }

    /**
     * Test create berita with image upload
     */
    public function test_create_berita_with_image_upload(): void
    {
        Storage::fake('public');

        $data = [
            'admin_id' => $this->admin->id,
            'judul' => 'Test With Image',
            'konten' => 'Content with image',
            'gambar_utama' => UploadedFile::fake()->image('test.jpg', 800, 600),
            'status' => 'draft',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertNotNull($berita->gambar_utama);
        $this->assertStringContainsString('berita/', $berita->gambar_utama);
        
        // Verify file was stored
        Storage::disk('public')->assertExists($berita->gambar_utama);
    }

    /**
     * Test update berita preserves existing data
     */
    public function test_update_berita_preserves_existing_data(): void
    {
        $berita = Berita::factory()->create([
            'admin_id' => $this->admin->id,
            'judul' => 'Original Title',
            'gambar_utama' => 'berita/original.jpg',
        ]);

        $updateData = [
            'judul' => 'Updated Title',
            'konten' => $berita->konten,
            'status' => $berita->status,
            // Not updating gambar_utama
        ];

        $updated = $this->beritaService->updateBerita($berita->id, $updateData);

        $this->assertEquals('Updated Title', $updated->judul);
        $this->assertEquals('berita/original.jpg', $updated->gambar_utama);
    }

    /**
     * Test update berita with new image replaces old image
     */
    public function test_update_berita_with_new_image_replaces_old_image(): void
    {
        Storage::fake('public');
        
        // Create berita with image
        Storage::disk('public')->put('berita/old-image.jpg', 'old content');
        
        $berita = Berita::factory()->create([
            'admin_id' => $this->admin->id,
            'gambar_utama' => 'berita/old-image.jpg',
        ]);

        $updateData = [
            'judul' => $berita->judul,
            'konten' => $berita->konten,
            'gambar_utama' => UploadedFile::fake()->image('new-image.jpg'),
            'status' => $berita->status,
        ];

        $updated = $this->beritaService->updateBerita($berita->id, $updateData);

        $this->assertNotEquals('berita/old-image.jpg', $updated->gambar_utama);
        
        // Old image should be deleted
        Storage::disk('public')->assertMissing('berita/old-image.jpg');
        
        // New image should exist
        Storage::disk('public')->assertExists($updated->gambar_utama);
    }

    /**
     * Test get published berita
     * Note: This method exists in BeritaRepository, not BeritaService
     */
    public function test_get_published_berita(): void
    {
        $this->markTestSkipped('Method getPublished() exists in BeritaRepository, not BeritaService');

        Berita::factory()->published()->count(5)->create();
        Berita::factory()->draft()->count(3)->create();

        $published = $this->beritaService->getPublished(10);

        $this->assertCount(5, $published);
        
        foreach ($published as $berita) {
            $this->assertEquals('published', $berita->status);
        }
    }

    /**
     * Test search berita
     * Note: This method exists in BeritaRepository, not BeritaService
     */
    public function test_search_berita(): void
    {
        $this->markTestSkipped('Method search() exists in BeritaRepository, not BeritaService');

        Berita::factory()->published()->create([
            'judul' => 'Pembangunan Jalan Desa',
        ]);

        Berita::factory()->published()->create([
            'judul' => 'Kegiatan Posyandu',
        ]);

        $results = $this->beritaService->search('Pembangunan', 10);

        $this->assertCount(1, $results);
        $this->assertEquals('Pembangunan Jalan Desa', $results->first()->judul);
    }

    /**
     * Test find berita by slug
     * Note: This method exists in BeritaRepository, not BeritaService
     */
    public function test_find_berita_by_slug(): void
    {
        $this->markTestSkipped('Method findBySlug() exists in BeritaRepository, not BeritaService');

        $berita = Berita::factory()->published()->create([
            'slug' => 'test-berita-slug',
        ]);

        $found = $this->beritaService->findBySlug('test-berita-slug');

        $this->assertNotNull($found);
        $this->assertEquals($berita->id, $found->id);
    }

    /**
     * Test delete berita removes image
     */
    public function test_delete_berita_removes_image(): void
    {
        Storage::fake('public');
        
        // Create image file
        Storage::disk('public')->put('berita/test-image.jpg', 'test content');
        
        $berita = Berita::factory()->create([
            'admin_id' => $this->admin->id,
            'gambar_utama' => 'berita/test-image.jpg',
        ]);

        $this->beritaService->deleteBerita($berita->id);

        // Image should be deleted
        Storage::disk('public')->assertMissing('berita/test-image.jpg');
        
        // Berita should be deleted
        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
    }

    /**
     * Test get popular berita
     * Note: This method exists in BeritaRepository, not BeritaService
     */
    public function test_get_popular_berita(): void
    {
        $this->markTestSkipped('Method getPopular() exists in BeritaRepository, not BeritaService');

        Berita::factory()->published()->create(['views' => 100]);
        Berita::factory()->published()->create(['views' => 500]);
        Berita::factory()->published()->create(['views' => 200]);

        $popular = $this->beritaService->getPopular(3);

        $this->assertCount(3, $popular);
        $this->assertEquals(500, $popular->first()->views);
        $this->assertEquals(100, $popular->last()->views);
    }
}
