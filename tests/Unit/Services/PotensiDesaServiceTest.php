<?php

namespace Tests\Unit\Services;

use App\Services\PotensiDesaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PotensiDesaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $potensiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->potensiService = app(PotensiDesaService::class);
    }

    public function test_create_potensi_generates_slug(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Potensi Wisata Test',
            'deskripsi' => '<p>Deskripsi potensi</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Test',
            'whatsapp' => '081234567890',
            'status' => 'published',
            'published_at' => now(),
        ];

        $potensi = $this->potensiService->createPotensi($data);

        $this->assertEquals('potensi-wisata-test', $potensi->slug);
    }

    public function test_create_potensi_sanitizes_html(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Test Potensi',
            'deskripsi' => '<p>Safe content</p><script>alert("xss")</script>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Test',
            'whatsapp' => '081234567890',
            'status' => 'published',
            'published_at' => now(),
        ];

        $potensi = $this->potensiService->createPotensi($data);

        $this->assertStringNotContainsString('<script>', $potensi->deskripsi);
        $this->assertStringContainsString('<p>Safe content</p>', $potensi->deskripsi);
    }

    public function test_update_potensi_sanitizes_html(): void
    {
        Storage::fake('public');

        $createData = [
            'nama' => 'Original',
            'deskripsi' => '<p>Original</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Test',
            'whatsapp' => '081234567890',
            'status' => 'published',
            'published_at' => now(),
        ];

        $potensi = $this->potensiService->createPotensi($createData);

        $updateData = [
            'nama' => 'Updated',
            'deskripsi' => '<p>Updated</p><script>malicious()</script>',
            'status' => 'published',
        ];

        $updated = $this->potensiService->updatePotensi($potensi->id, $updateData);

        $this->assertStringNotContainsString('<script>', $updated->deskripsi);
    }

    public function test_delete_potensi_removes_image(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Test',
            'deskripsi' => '<p>Test</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Test',
            'whatsapp' => '081234567890',
            'status' => 'published',
            'published_at' => now(),
        ];

        $potensi = $this->potensiService->createPotensi($data);

        $imagePath = $potensi->gambar;
        Storage::disk('public')->assertExists($imagePath);

        $this->potensiService->deletePotensi($potensi->id);

        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_get_active_potensi_only_returns_active_items(): void
    {
        Storage::fake('public');

        // Create published (active)
        $this->potensiService->createPotensi([
            'nama' => 'Active Potensi',
            'deskripsi' => '<p>Active</p>',
            'gambar' => UploadedFile::fake()->image('active.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Active',
            'whatsapp' => '081234567890',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Create draft (inactive)
        $this->potensiService->createPotensi([
            'nama' => 'Inactive Potensi',
            'deskripsi' => '<p>Inactive</p>',
            'gambar' => UploadedFile::fake()->image('inactive.jpg'),
            'kategori' => 'wisata',
            'nama_pengelola' => 'Pak Inactive',
            'whatsapp' => '081234567891',
            'status' => 'draft',
        ]);

        $activePotensi = $this->potensiService->getActivePotensi();

        $this->assertCount(1, $activePotensi);
        $this->assertEquals('Active Potensi', $activePotensi->first()->nama);
    }

    public function test_get_all_potensi(): void
    {
        \App\Models\PotensiDesa::factory()->create();
        $all = $this->potensiService->getAllPotensi();
        $this->assertGreaterThanOrEqual(1, count($all));
    }

    public function test_get_potensi_by_kategori(): void
    {
        \App\Models\PotensiDesa::factory()->create(['kategori' => 'wisata']);
        $result = $this->potensiService->getPotensiByKategori('wisata');
        $this->assertNotEmpty($result);
    }

    public function test_get_potensi_by_id(): void
    {
        $p = \App\Models\PotensiDesa::factory()->create();
        $result = $this->potensiService->getPotensiById($p->id);
        $this->assertEquals($p->id, $result->id);
    }

    public function test_get_potensi_by_slug(): void
    {
        $p = \App\Models\PotensiDesa::factory()->create(['slug' => 'test-slug', 'status' => 'published']);
        $result = $this->potensiService->getPotensiBySlug('test-slug');
        $this->assertEquals($p->id, $result->id);
    }

    public function test_get_related_potensi(): void
    {
        $p1 = \App\Models\PotensiDesa::factory()->create(['kategori' => 'wisata', 'status' => 'published']);
        $p2 = \App\Models\PotensiDesa::factory()->create(['kategori' => 'wisata', 'status' => 'published']);
        $related = $this->potensiService->getRelatedPotensi($p1);
        $this->assertCount(1, $related);
    }

    public function test_get_featured_potensi(): void
    {
        \App\Models\PotensiDesa::factory()->create(['status' => 'published']);
        $featured = $this->potensiService->getFeaturedPotensi();
        $this->assertNotEmpty($featured);
    }

    public function test_get_categories_with_count(): void
    {
        \App\Models\PotensiDesa::factory()->create(['kategori' => 'wisata', 'status' => 'published']);
        $cats = $this->potensiService->getCategoriesWithCount();
        $this->assertNotEmpty($cats);
    }

    public function test_create_potensi_with_gallery_photos(): void
    {
        Storage::fake('public');
        $data = [
            'nama' => 'Potensi Gallery',
            'deskripsi' => 'test description',
            'kategori' => 'wisata',
            'status' => 'published',
        ];
        $files = [
            UploadedFile::fake()->image('img1.jpg'),
            UploadedFile::fake()->image('img2.jpg'),
        ];
        $potensi = $this->potensiService->createPotensi($data, $files);
        $this->assertCount(2, $potensi->fotoGaleri);
    }

    public function test_update_potensi_with_new_image_and_gallery(): void
    {
        Storage::fake('public');
        $potensi = \App\Models\PotensiDesa::factory()->create([
            'gambar' => 'potensi/old.jpg',
        ]);
        $updateData = [
            'nama' => 'Updated Name',
            'gambar' => UploadedFile::fake()->image('new_main.jpg'),
        ];
        $files = [
            UploadedFile::fake()->image('img1.jpg'),
        ];
        $updated = $this->potensiService->updatePotensi($potensi->id, $updateData, $files);
        $this->assertInstanceOf(\App\Models\PotensiDesa::class, $updated);
        $this->assertNotEquals('potensi/old.jpg', $potensi->fresh()->gambar);
    }

    public function test_delete_foto_galeri(): void
    {
        Storage::fake('public');
        $potensi = \App\Models\PotensiDesa::factory()->create();
        $foto = \App\Models\PotensiDesaFoto::create([
            'potensi_desa_id' => $potensi->id,
            'foto' => 'potensi/galeri/test.jpg',
            'urutan' => 1,
        ]);
        Storage::disk('public')->put('potensi/galeri/test.jpg', 'dummy');
        $this->potensiService->deleteFotoGaleri($foto->id);
        $this->assertDatabaseMissing('potensi_desa_foto', ['id' => $foto->id]);
    }

    public function test_reorder_potensi(): void
    {
        $p1 = \App\Models\PotensiDesa::factory()->create(['urutan' => 1]);
        $p2 = \App\Models\PotensiDesa::factory()->create(['urutan' => 2]);
        $this->potensiService->reorderPotensi([$p2->id, $p1->id]);
        $this->assertEquals(1, $p2->fresh()->urutan);
        $this->assertEquals(2, $p1->fresh()->urutan);
    }

    public function test_search_potensi(): void
    {
        \App\Models\PotensiDesa::factory()->create(['nama' => 'Cari Saya', 'status' => 'published']);
        $results = $this->potensiService->searchPotensi('Cari Saya');
        $this->assertCount(1, $results);
    }

    public function test_search_with_filters(): void
    {
        \App\Models\PotensiDesa::factory()->create(['nama' => 'Filtered', 'kategori' => 'wisata', 'status' => 'published']);
        $filters = [
            'search' => 'Filtered',
            'kategori' => 'wisata',
            'urutkan' => 'terpopuler',
        ];
        $results = $this->potensiService->searchWithFilters($filters);
        $this->assertCount(1, $results);

        $filters['urutkan'] = 'terlama';
        $results = $this->potensiService->searchWithFilters($filters);
        $this->assertCount(1, $results);
    }
}
