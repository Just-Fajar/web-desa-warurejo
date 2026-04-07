<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    protected $manager;

    /**
     * Constructor - Initialize Intervention Image dengan GD driver
     * GD driver lebih ringan dan tersedia di hampir semua PHP installation
     */
    public function __construct()
    {
        // Initialize ImageManager with GD driver (or Imagick if available)
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload dan optimize image dengan resize otomatis
     * - Generate unique filename untuk prevent conflict
     * - Resize sesuai maxWidth/maxHeight (maintain aspect ratio)
     * - Optimize berdasarkan format (JPEG/PNG/WebP)
     * - Simpan ke storage Laravel
     * 
     * @param \Illuminate\Http\UploadedFile $image - file gambar dari form
     * @param string $folder - folder tujuan di storage (default: 'uploads')
     * @param int|null $maxWidth - lebar maksimal pixel (default: 1200)
     * @param int|null $maxHeight - tinggi maksimal pixel (default: null = auto)
     * @param int $quality - kualitas JPEG/WebP 0-100 (default: 85)
     * @return string|null - path file yang tersimpan atau null jika gagal
     */
    public function upload($image, $folder = 'uploads', $maxWidth = 1200, $maxHeight = null, $quality = 85)
    {
        if (!$image || !$image->isValid()) {
            return null;
        }

        try {
            // Generate unique filename
            $filename = $this->generateFilename($image);
            
            // Full path
            $path = $folder . '/' . $filename;
            
            // Read and process image
            $imageResource = $this->manager->read($image->getRealPath());
            
            // Get original dimensions for aspect ratio calculation
            $originalWidth = $imageResource->width();
            $originalHeight = $imageResource->height();
            
            // Resize if needed (maintain aspect ratio)
            if ($maxWidth && $originalWidth > $maxWidth) {
                if ($maxHeight) {
                    // Scale to fit within both dimensions
                    $imageResource->scale(width: $maxWidth, height: $maxHeight);
                } else {
                    // Scale width only, height auto
                    $imageResource->scale(width: $maxWidth);
                }
            }
            
            // Encode with quality optimization
            $extension = strtolower($image->getClientOriginalExtension());

            switch ($extension) {
                case 'png':
                    // PNG: use optimization level (0-9)
                    $encoded = $imageResource->toPng();
                    break;
                case 'webp':
                    // WebP: excellent compression with good quality
                    $encoded = $imageResource->toWebp(quality: $quality);
                    break;
                case 'jpg':
                case 'jpeg':
                    // JPEG: standard compression
                    $encoded = $imageResource->toJpeg(quality: $quality);
                    break;
                default:
                    // Default to JPEG for unknown formats
                    $encoded = $imageResource->toJpeg(quality: $quality);
                    break;
            }
            
            // Save to storage
            Storage::disk('public')->put($path, (string) $encoded);
            
            Log::info("Image uploaded successfully: {$path} (Original: {$originalWidth}x{$originalHeight}, Quality: {$quality})");
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple images sekaligus
     * Berguna untuk galeri dengan banyak foto
     * Jika ada gambar yang gagal, gambar lain tetap diproses
     * 
     * @param array $images - array of UploadedFile objects
     * @param string $folder - folder tujuan
     * @return array - array of paths yang berhasil di-upload
     */
    public function uploadMultiple(array $images, $folder = 'uploads')
    {
        $uploadedPaths = [];
        
        foreach ($images as $image) {
            $path = $this->upload($image, $folder);
            if ($path) {
                $uploadedPaths[] = $path;
            }
        }
        
        return $uploadedPaths;
    }

    /**
     * Delete image dari storage
     * Safe delete - cek dulu apakah file exist sebelum dihapus
     * Biasanya dipanggil saat update/delete content
     * 
     * @param string|null $path - path file relatif dari storage/app/public
     * @return bool - true jika berhasil dihapus, false jika gagal atau file tidak ada
     */
    public function delete($path)
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Image delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete multiple images sekaligus
     * Berguna untuk hapus semua foto galeri atau cleanup
     * Tidak stop jika ada file yang gagal dihapus
     * 
     * @param array $paths - array of file paths
     * @return int - jumlah file yang berhasil dihapus
     */
    public function deleteMultiple(array $paths)
    {
        $deletedCount = 0;
        
        foreach ($paths as $path) {
            if ($this->delete($path)) {
                $deletedCount++;
            }
        }
        
        return $deletedCount;
    }

    /**
     * Generate unique filename untuk prevent conflict
     * Format: timestamp_random10karakter.extension
     * Contoh: 1703145234_aBcDeFgHiJ.jpg
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @return string - nama file yang unik
     */
    protected function generateFilename($image)
    {
        $extension = $image->getClientOriginalExtension();
        $timestamp = time();
        $random = Str::random(10);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get public URL dari image path untuk ditampilkan di view
     * Cek dulu apakah file exist, jika tidak return default placeholder
     * 
     * @param string|null $path - path relatif dari storage/app/public
     * @param string|null $default - URL gambar default jika file tidak ada
     * @return string - full public URL yang bisa diakses browser
     */
    public function getUrl($path, $default = null)
    {
        if (!$path) {
            return $default ?? asset('images/default-placeholder.jpg');
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return $default ?? asset('images/default-placeholder.jpg');
    }

    /**
     * Create thumbnail dari uploaded file (untuk preview/listing)
     * - Crop to fit (cover) agar tidak ada whitespace
     * - Always save as WebP untuk ukuran lebih kecil
     * - Default 300x300 dengan quality 80
     * 
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folder - folder tujuan (default: 'thumbnails')
     * @param int $width - lebar thumbnail pixel
     * @param int $height - tinggi thumbnail pixel
     * @param int $quality - kualitas WebP 0-100
     * @return string|null - path thumbnail atau null jika gagal
     */
    public function createThumbnail($image, $folder = 'thumbnails', $width = 300, $height = 300, $quality = 80)
    {
        if (!$image || !$image->isValid()) {
            return null;
        }

        try {
            $filename = $this->generateFilename($image);
            $path = $folder . '/' . $filename;
            
            $imageResource = $this->manager->read($image->getRealPath());
            
            // Create thumbnail with cover (crop to fit)
            $imageResource->cover($width, $height);
            
            // Save as WebP for better compression
            $encoded = $imageResource->toWebp(quality: $quality);
            
            Storage::disk('public')->put($path, (string) $encoded);
            
            Log::info("Thumbnail created successfully: {$path}");
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create thumbnail dari file yang sudah tersimpan di storage
     * Berguna saat regenerate thumbnail atau batch processing
     * Cek dulu apakah source file exist, baru buat thumbnail
     * 
     * @param string $imagePath - path file sumber relatif dari storage/app/public
     * @param string $folder - folder tujuan thumbnail
     * @param int $width - lebar thumbnail
     * @param int $height - tinggi thumbnail
     * @param int $quality - kualitas WebP
     * @return string|null - path thumbnail baru atau null jika gagal
     */
    public function createThumbnailFromPath($imagePath, $folder = 'thumbnails', $width = 300, $height = 300, $quality = 80)
    {
        try {
            // Try to get file content from Storage facade (works with fake storage in tests)
            if (!Storage::disk('public')->exists($imagePath)) {
                Log::error("Thumbnail source file not found in storage: {$imagePath}");
                return null;
            }

            // Get file content from storage
            $fileContent = Storage::disk('public')->get($imagePath);
            
            // Load image from content
            $imageResource = $this->manager->read($fileContent);
            
            // Generate unique thumbnail filename (use WebP for better compression)
            $timestamp = time();
            $random = Str::random(8);
            $filename = "thumb_{$timestamp}_{$random}.webp";
            $thumbnailPath = $folder . '/' . $filename;
            
            // Create thumbnail with cover
            $imageResource->cover($width, $height);
            $encoded = $imageResource->toWebp(quality: $quality);
            
            // Save thumbnail
            Storage::disk('public')->put($thumbnailPath, (string) $encoded);
            
            Log::info("Thumbnail created from path: {$thumbnailPath}");
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail from path creation failed: ' . $e->getMessage());
            return null;
        }
    }
}
