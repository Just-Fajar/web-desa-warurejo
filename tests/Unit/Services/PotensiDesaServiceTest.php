<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PotensiDesaService;
use App\Repositories\PotensiDesaRepository;
use App\Services\HtmlSanitizerService;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PotensiDesaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $potensiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->potensiService = app(PotensiDesaService::class);
    }

    /**
     * Test create potensi generates slug
     */
    public function test_create_potensi_generates_slug(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Potensi Wisata Test',
            'deskripsi' => '<p>Deskripsi potensi</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'is_active' => true,
        ];

        $potensi = $this->potensiService->createPotensi($data);

        $this->assertEquals('potensi-wisata-test', $potensi->slug);
    }

    /**
     * Test create potensi sanitizes HTML
     */
    public function test_create_potensi_sanitizes_html(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Test Potensi',
            'deskripsi' => '<p>Safe content</p><script>alert("xss")</script>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'is_active' => true,
        ];

        $potensi = $this->potensiService->createPotensi($data);

        $this->assertStringNotContainsString('<script>', $potensi->deskripsi);
        $this->assertStringContainsString('<p>Safe content</p>', $potensi->deskripsi);
    }

    /**
     * Test update potensi sanitizes HTML
     */
    public function test_update_potensi_sanitizes_html(): void
    {
        Storage::fake('public');

        $createData = [
            'nama' => 'Original',
            'deskripsi' => '<p>Original</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'is_active' => true,
        ];

        $potensi = $this->potensiService->createPotensi($createData);

        $updateData = [
            'nama' => 'Updated',
            'deskripsi' => '<p>Updated</p><script>malicious()</script>',
            'is_active' => true,
        ];

        $updated = $this->potensiService->updatePotensi($potensi->id, $updateData);

        $this->assertStringNotContainsString('<script>', $updated->deskripsi);
    }

    /**
     * Test delete potensi removes image
     */
    public function test_delete_potensi_removes_image(): void
    {
        Storage::fake('public');

        $data = [
            'nama' => 'Test',
            'deskripsi' => '<p>Test</p>',
            'gambar' => UploadedFile::fake()->image('test.jpg'),
            'is_active' => true,
        ];

        $potensi = $this->potensiService->createPotensi($data);
        
        // gambar field already contains full path (potensi/filename.jpg)
        $imagePath = $potensi->gambar;
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($imagePath);

        $this->potensiService->deletePotensi($potensi->id);

        \Illuminate\Support\Facades\Storage::disk('public')->assertMissing($imagePath);
    }

    /**
     * Test get active potensi only returns active items
     */
    public function test_get_active_potensi_only_returns_active_items(): void
    {
        Storage::fake('public');

        // Create active
        $this->potensiService->createPotensi([
            'nama' => 'Active Potensi',
            'deskripsi' => '<p>Active</p>',
            'gambar' => UploadedFile::fake()->image('active.jpg'),
            'is_active' => true,
        ]);

        // Create inactive
        $this->potensiService->createPotensi([
            'nama' => 'Inactive Potensi',
            'deskripsi' => '<p>Inactive</p>',
            'gambar' => UploadedFile::fake()->image('inactive.jpg'),
            'is_active' => false,
        ]);

        $activePotensi = $this->potensiService->getActivePotensi();

        $this->assertCount(1, $activePotensi);
        $this->assertEquals('Active Potensi', $activePotensi->first()->nama);
    }
}
