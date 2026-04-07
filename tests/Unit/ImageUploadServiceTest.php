<?php

namespace Tests\Unit;

use App\Services\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageService = new ImageUploadService();
        Storage::fake('public');
    }

    /**
     * Test uploads image successfully.
     */
    public function test_uploads_image_successfully(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $path = $this->imageService->upload($file, 'test');

        $this->assertNotNull($path);
        $this->assertStringStartsWith('test/', $path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    /**
     * Test generates unique filename.
     */
    public function test_generates_unique_filename(): void
    {
        $file1 = UploadedFile::fake()->image('test.jpg');
        $file2 = UploadedFile::fake()->image('test.jpg');

        $path1 = $this->imageService->upload($file1, 'test');
        $path2 = $this->imageService->upload($file2, 'test');

        $this->assertNotEquals($path1, $path2);
    }

    /**
     * Test resizes large images.
     */
    public function test_resizes_large_images(): void
    {
        $file = UploadedFile::fake()->image('large.jpg', 2000, 1500);

        $path = $this->imageService->upload($file, 'test', 1200);

        $this->assertNotNull($path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    /**
     * Test deletes image successfully.
     */
    public function test_deletes_image_successfully(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->upload($file, 'test');

        $this->assertTrue(Storage::disk('public')->exists($path));

        $result = $this->imageService->delete($path);

        $this->assertTrue($result);
        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    /**
     * Test delete returns false for non-existent file.
     */
    public function test_delete_returns_false_for_non_existent_file(): void
    {
        $result = $this->imageService->delete('non-existent/file.jpg');

        $this->assertFalse($result);
    }

    /**
     * Test delete handles null path.
     */
    public function test_delete_handles_null_path(): void
    {
        $result = $this->imageService->delete(null);

        $this->assertFalse($result);
    }

    /**
     * Test uploads multiple images.
     */
    public function test_uploads_multiple_images(): void
    {
        $files = [
            UploadedFile::fake()->image('test1.jpg'),
            UploadedFile::fake()->image('test2.jpg'),
            UploadedFile::fake()->image('test3.jpg'),
        ];

        $paths = $this->imageService->uploadMultiple($files, 'test');

        $this->assertCount(3, $paths);
        foreach ($paths as $path) {
            $this->assertTrue(Storage::disk('public')->exists($path));
        }
    }

    /**
     * Test deletes multiple images.
     */
    public function test_deletes_multiple_images(): void
    {
        $files = [
            UploadedFile::fake()->image('test1.jpg'),
            UploadedFile::fake()->image('test2.jpg'),
        ];

        $paths = $this->imageService->uploadMultiple($files, 'test');

        $deletedCount = $this->imageService->deleteMultiple($paths);

        $this->assertEquals(2, $deletedCount);
        foreach ($paths as $path) {
            $this->assertFalse(Storage::disk('public')->exists($path));
        }
    }

    /**
     * Test creates thumbnail.
     */
    public function test_creates_thumbnail(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $path = $this->imageService->createThumbnail($file, 'thumbnails', 300, 300);

        $this->assertNotNull($path);
        $this->assertStringStartsWith('thumbnails/', $path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    /**
     * Test creates thumbnail from existing path.
     */
    public function test_creates_thumbnail_from_path(): void
    {
        // First upload an image
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        $originalPath = $this->imageService->upload($file, 'images');
        
        $this->assertNotNull($originalPath, 'Original image upload failed');

        // Then create thumbnail from the uploaded image
        $thumbnailPath = $this->imageService->createThumbnailFromPath(
            $originalPath,
            'thumbnails',
            200,
            200
        );

        $this->assertNotNull($thumbnailPath, 'Thumbnail creation failed');
        $this->assertStringStartsWith('thumbnails/', $thumbnailPath);
        $this->assertTrue(Storage::disk('public')->exists($thumbnailPath));
    }

    /**
     * Test get URL returns asset path for existing file.
     */
    public function test_get_url_returns_asset_path_for_existing_file(): void
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->upload($file, 'test');

        $url = $this->imageService->getUrl($path);

        $this->assertStringContainsString('storage/' . $path, $url);
    }

    /**
     * Test get URL returns default for null path.
     */
    public function test_get_url_returns_default_for_null_path(): void
    {
        $url = $this->imageService->getUrl(null);

        $this->assertStringContainsString('default-placeholder.jpg', $url);
    }

    /**
     * Test upload handles invalid file gracefully.
     */
    public function test_upload_handles_invalid_file_gracefully(): void
    {
        // Create invalid UploadedFile (0 bytes)
        $invalidFile = UploadedFile::fake()->create('invalid.jpg', 0);
        
        $result = $this->imageService->upload($invalidFile, 'test');

        $this->assertNull($result);
    }
}
