<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ImageCompressionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageCompressionServiceTest extends TestCase
{
    protected ImageCompressionService $compressionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compressionService = app(ImageCompressionService::class);
    }

    public function test_compress_and_store_jpeg(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

        $path = $this->compressionService->compressAndStore($file, 'images', 1920, 85);

        $this->assertNotNull($path);
        $this->assertStringContainsString('images/', $path);
        $this->assertStringEndsWith('.jpg', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_compress_and_store_png(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.png', 100, 100);

        $path = $this->compressionService->compressAndStore($file, 'images', 1920, 85);

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.png', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_compress_and_store_webp(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.webp', 100, 100);

        $path = $this->compressionService->compressAndStore($file, 'images', 1920, 85);

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_compress_and_store_other(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.gif', 100, 100);

        $path = $this->compressionService->compressAndStore($file, 'images', 1920, 85);

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.gif', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_convert_to_webp(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

        $path = $this->compressionService->convertToWebP($file, 'images', 1920, 85);

        $this->assertNotNull($path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_create_thumbnail(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg', 500, 500);

        $path = $this->compressionService->createThumbnail($file, 'thumbnails', 150, 150, 80);

        $this->assertNotNull($path);
        $this->assertStringContainsString('thumbnails/', $path);
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_resize_with_aspect_ratio(): void
    {
        Storage::fake('public');
        
        // Without maxHeight
        $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);
        $path1 = $this->compressionService->resizeWithAspectRatio($file, 'images', 1000);
        $this->assertNotNull($path1);
        Storage::disk('public')->assertExists($path1);

        // With maxHeight
        $file2 = UploadedFile::fake()->image('test.png', 2000, 1500);
        $path2 = $this->compressionService->resizeWithAspectRatio($file2, 'images', 1000, 800);
        $this->assertNotNull($path2);
        Storage::disk('public')->assertExists($path2);

        // WebP
        $file3 = UploadedFile::fake()->image('test.webp', 100, 100);
        $path3 = $this->compressionService->resizeWithAspectRatio($file3, 'images', 1000);
        $this->assertNotNull($path3);
        Storage::disk('public')->assertExists($path3);

        // GIF
        $file4 = UploadedFile::fake()->image('test.gif', 100, 100);
        $path4 = $this->compressionService->resizeWithAspectRatio($file4, 'images', 1000);
        $this->assertNotNull($path4);
        Storage::disk('public')->assertExists($path4);
    }

    public function test_delete_image(): void
    {
        Storage::fake('public');
        
        // Null path
        $this->assertFalse($this->compressionService->deleteImage(null));

        // Non-existent path
        $this->assertFalse($this->compressionService->deleteImage('images/non-existent.jpg'));

        // Existent path
        Storage::disk('public')->put('images/to-delete.jpg', 'content');
        $this->assertTrue($this->compressionService->deleteImage('images/to-delete.jpg'));
        Storage::disk('public')->assertMissing('images/to-delete.jpg');
    }

    public function test_get_file_size(): void
    {
        Storage::fake('public');

        // Non-existent path
        $this->assertNull($this->compressionService->getFileSize('images/non-existent.jpg'));

        // Existent path
        Storage::disk('public')->put('images/test-size.jpg', 'content');
        $size = $this->compressionService->getFileSize('images/test-size.jpg');
        $this->assertNotNull($size);
        $this->assertGreaterThan(0, $size);
    }

    public function test_batch_compress(): void
    {
        Storage::fake('public');
        $files = [
            UploadedFile::fake()->image('img1.jpg', 100, 100),
            UploadedFile::fake()->image('img2.png', 100, 100),
            'not-a-file'
        ];

        $paths = $this->compressionService->batchCompress($files, 'images');
        $this->assertCount(2, $paths);
        foreach ($paths as $path) {
            $this->assertNotNull($path);
            Storage::disk('public')->assertExists($path);
        }
    }
}
