<?php

namespace Tests\Unit\Services;

use App\Services\ImageUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

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

    /**
     * Test upload multiple images
     */
    public function test_upload_multiple_images(): void
    {
        Storage::fake('public');

        $files = [
            UploadedFile::fake()->image('image1.jpg', 100, 100),
            UploadedFile::fake()->image('image2.png', 100, 100),
            UploadedFile::fake()->image('image3.webp', 100, 100),
        ];

        $paths = $this->imageService->uploadMultiple($files, 'gallery');

        $this->assertCount(3, $paths);
        foreach ($paths as $path) {
            $this->assertNotNull($path);
            $this->assertStringContainsString('gallery/', $path);
            Storage::disk('public')->assertExists($path);
        }
    }

    /**
     * Test delete multiple images
     */
    public function test_delete_multiple_images(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('gallery/image1.jpg', 'content');
        Storage::disk('public')->put('gallery/image2.jpg', 'content');

        $this->assertTrue(Storage::disk('public')->exists('gallery/image1.jpg'));
        $this->assertTrue(Storage::disk('public')->exists('gallery/image2.jpg'));

        $deletedCount = $this->imageService->deleteMultiple(['gallery/image1.jpg', 'gallery/image2.jpg', 'gallery/non-existent.jpg']);

        $this->assertEquals(2, $deletedCount);
        $this->assertFalse(Storage::disk('public')->exists('gallery/image1.jpg'));
        $this->assertFalse(Storage::disk('public')->exists('gallery/image2.jpg'));
    }

    /**
     * Test getUrl method
     */
    public function test_get_url(): void
    {
        Storage::fake('public');

        // Path is null or empty
        $url1 = $this->imageService->getUrl(null);
        $this->assertStringContainsString('images/default-placeholder.jpg', $url1);

        $url2 = $this->imageService->getUrl('', 'custom-default.jpg');
        $this->assertEquals('custom-default.jpg', $url2);

        // Path exists
        Storage::disk('public')->put('uploads/image.jpg', 'content');
        $url3 = $this->imageService->getUrl('uploads/image.jpg');
        $this->assertStringContainsString('storage/uploads/image.jpg', $url3);

        // Path does not exist
        $url4 = $this->imageService->getUrl('uploads/non-existent.jpg');
        $this->assertStringContainsString('images/default-placeholder.jpg', $url4);
    }

    /**
     * Test createThumbnail method
     */
    public function test_create_thumbnail(): void
    {
        Storage::fake('public');

        // Invalid file
        $invalidFile = UploadedFile::fake()->create('doc.pdf', 100);
        $result = $this->imageService->createThumbnail($invalidFile);
        $this->assertNull($result);

        // Valid image
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        $thumbPath = $this->imageService->createThumbnail($file, 'thumbnails', 150, 150, 80);

        $this->assertNotNull($thumbPath);
        $this->assertStringContainsString('thumbnails/', $thumbPath);
        Storage::disk('public')->assertExists($thumbPath);
    }

    /**
     * Test createThumbnailFromPath method
     */
    public function test_create_thumbnail_from_path(): void
    {
        Storage::fake('public');

        // Source path does not exist
        $result = $this->imageService->createThumbnailFromPath('uploads/non-existent.jpg');
        $this->assertNull($result);

        // Source path exists
        $file = UploadedFile::fake()->image('original.jpg', 800, 600);
        $originalPath = $this->imageService->upload($file, 'uploads');
        $this->assertNotNull($originalPath);

        $thumbPath = $this->imageService->createThumbnailFromPath($originalPath, 'thumbnails', 150, 150, 80);
        $this->assertNotNull($thumbPath);
        $this->assertStringContainsString('thumbnails/', $thumbPath);
        Storage::disk('public')->assertExists($thumbPath);
    }
}
