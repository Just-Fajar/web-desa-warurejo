<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadServiceTest extends TestCase
{
    protected ImageUploadService $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageService = app(ImageUploadService::class);
    }

    /**
     * Test upload image to berita directory
     */
    public function test_upload_image_to_berita_directory(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $path = $this->imageService->upload($file, 'berita');

        $this->assertNotNull($path);
        $this->assertStringContainsString('berita/', $path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test upload image resizes large images
     */
    public function test_upload_image_resizes_large_images(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('large.jpg', 3000, 2000);

        $path = $this->imageService->upload($file, 'berita', 1200);

        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test upload image with custom quality
     */
    public function test_upload_image_with_custom_quality(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $path = $this->imageService->upload($file, 'galeri', 1200, null, 90);

        $this->assertNotNull($path);
        $this->assertStringContainsString('galeri/', $path);
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Test delete existing image
     */
    public function test_delete_existing_image(): void
    {
        Storage::fake('public');

        // Create a test image
        Storage::disk('public')->put('berita/test-delete.jpg', 'test content');

        $this->assertTrue(Storage::disk('public')->exists('berita/test-delete.jpg'));

        $this->imageService->delete('berita/test-delete.jpg');

        $this->assertFalse(Storage::disk('public')->exists('berita/test-delete.jpg'));
    }

    /**
     * Test delete non existent image does not throw error
     */
    public function test_delete_non_existent_image_does_not_throw_error(): void
    {
        Storage::fake('public');

        // This should not throw an exception
        $result = $this->imageService->delete('berita/non-existent.jpg');

        $this->assertFalse($result);
    }

    /**
     * Test upload generates unique filename
     */
    public function test_upload_generates_unique_filename(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('test.jpg', 100, 100);
        $file2 = UploadedFile::fake()->image('test.jpg', 100, 100);

        $path1 = $this->imageService->upload($file1, 'berita');
        // Small delay to ensure different timestamp
        usleep(10000);
        $path2 = $this->imageService->upload($file2, 'berita');

        $this->assertNotNull($path1);
        $this->assertNotNull($path2);
        $this->assertNotEquals($path1, $path2);

        Storage::disk('public')->assertExists($path1);
        Storage::disk('public')->assertExists($path2);
    }

    /**
     * Test upload only accepts image files
     */
    public function test_upload_validates_image_type(): void
    {
        Storage::fake('public');

        // Try to upload non-image file
        $file = UploadedFile::fake()->create('document.pdf', 100);

        // Service akan return null untuk file yang tidak valid
        $result = $this->imageService->upload($file, 'berita');

        $this->assertNull($result, 'Non-image file should return null');
    }
}
