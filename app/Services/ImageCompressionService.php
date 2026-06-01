<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageCompressionService
{
    protected ImageManager $manager;

    public function __construct()
    {
        // Initialize ImageManager with GD driver
        $this->manager = new ImageManager(new Driver);
    }

    /**
     * Compress dan optimize gambar
     *
     * @return string path file
     */
    public function compressAndStore(UploadedFile $file, string $path = 'images', int $maxWidth = 1920, int $quality = 85): string
    {
        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = time().'_'.uniqid().'.'.$extension;
        $fullPath = $path.'/'.$filename;

        // Load image
        $image = $this->manager->read($file);

        // Get original dimensions
        $originalWidth = $image->width();

        // Resize jika lebih besar dari maxWidth, maintain aspect ratio
        if ($originalWidth > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Optimize berdasarkan tipe file
        $extension = strtolower($extension);

        // Encode dengan quality setting
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $encoded = $image->toJpeg($quality);
                break;
            case 'png':
                // PNG menggunakan quality 0-9 (compression level)
                $pngQuality = (int) (9 - ($quality / 100 * 9));
                $encoded = $image->toPng();
                break;
            case 'webp':
                $encoded = $image->toWebp($quality);
                break;
            default:
                $encoded = $image->encode();
        }

        // Save ke storage
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }

    /**
     * Convert ke WebP untuk better compression
     *
     * @return string path file
     */
    public function convertToWebP(mixed $file, string $path = 'images', int $maxWidth = 1920, int $quality = 85): string
    {
        if (is_string($file)) {
            $pathInfo = pathinfo($file);
            $filename = $pathInfo['filename'].'_'.time().'.webp';
            $targetFolder = $pathInfo['dirname'] === '.' ? $path : $pathInfo['dirname'];
            $fullPath = $targetFolder.'/'.$filename;

            $absolutePath = Storage::disk('public')->path($file);
            $image = $this->manager->read($absolutePath);
        } else {
            $filename = time().'_'.uniqid().'.webp';
            $fullPath = $path.'/'.$filename;
            $image = $this->manager->read($file);
        }

        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Encode to WebP
        $encoded = $image->toWebp($quality);

        // Save to storage
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }

    /**
     * Generate thumbnail
     *
     * @return string path file
     */
    public function createThumbnail(UploadedFile $file, string $path = 'thumbnails', int $width = 300, int $height = 300, int $quality = 80): string
    {
        $filename = time().'_thumb_'.uniqid().'.webp';
        $fullPath = $path.'/'.$filename;

        // Load image
        $image = $this->manager->read($file);

        // Cover fit (crop to exact dimensions while maintaining aspect ratio)
        $image->cover($width, $height);

        // Encode to WebP
        $encoded = $image->toWebp($quality);

        // Save to storage
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }

    /**
     * Resize image with aspect ratio
     *
     * @return string path file
     */
    public function resizeWithAspectRatio(UploadedFile $file, string $path = 'images', int $maxWidth = 1920, ?int $maxHeight = null, int $quality = 85): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time().'_'.uniqid().'.'.$extension;
        $fullPath = $path.'/'.$filename;

        // Load image
        $image = $this->manager->read($file);

        // Scale to fit within maxWidth and maxHeight while maintaining aspect ratio
        if ($maxHeight) {
            $image->scale(width: $maxWidth, height: $maxHeight);
        } else {
            $image->scale(width: $maxWidth);
        }

        // Encode based on extension
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $encoded = $image->toJpeg($quality);
                break;
            case 'png':
                $encoded = $image->toPng();
                break;
            case 'webp':
                $encoded = $image->toWebp($quality);
                break;
            default:
                $encoded = $image->encode();
        }

        // Save to storage
        Storage::disk('public')->put($fullPath, (string) $encoded);

        return $fullPath;
    }

    /**
     * Delete image from storage
     */
    public function deleteImage(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get image file size in KB
     */
    public function getFileSize(string $path): ?float
    {
        if (Storage::disk('public')->exists($path)) {
            $sizeInBytes = Storage::disk('public')->size($path);

            return round($sizeInBytes / 1024, 2); // Convert to KB
        }

        return null;
    }

    /**
     * Batch compress multiple images
     *
     * @param  array  $files  Array of UploadedFile
     * @return array Array of file paths
     */
    public function batchCompress(array $files, string $path = 'images', int $maxWidth = 1920, int $quality = 85): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->compressAndStore($file, $path, $maxWidth, $quality);
            }
        }

        return $paths;
    }
}
