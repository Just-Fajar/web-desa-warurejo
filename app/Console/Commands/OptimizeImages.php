<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Services\ImageUploadService;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Support\Facades\Storage;

/**
 * @phpstan-ignore-next-line
 */
class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize 
                            {--type=all : Type of images to optimize (all, berita, potensi, galeri)}
                            {--max-width=1200 : Maximum width for images}
                            {--quality=85 : JPEG quality (1-100)}
                            {--webp : Generate WebP versions of images}
                            {--webp-quality=80 : WebP quality (1-100)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize existing images by resizing, compressing, and optionally generating WebP versions';

    protected $imageService;
    protected $optimizedCount = 0;
    protected $failedCount = 0;
    protected $skippedCount = 0;
    protected $webpCount = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->imageService = app(ImageUploadService::class);
        
        $type = $this->option('type');
        $maxWidth = (int) $this->option('max-width');
        $quality = (int) $this->option('quality');
        $generateWebp = $this->option('webp');
        $webpQuality = (int) $this->option('webp-quality');

        $this->info("Starting image optimization...");
        $this->info("Type: {$type} | Max Width: {$maxWidth}px | Quality: {$quality}%");
        if ($generateWebp) {
            $this->info("WebP generation: ENABLED | WebP Quality: {$webpQuality}%");
        }
        $this->newLine();

        if ($type === 'all' || $type === 'berita') {
            $this->optimizeBeritaImages($maxWidth, $quality, $generateWebp, $webpQuality);
        }

        if ($type === 'all' || $type === 'potensi') {
            $this->optimizePotensiImages($maxWidth, $quality, $generateWebp, $webpQuality);
        }

        if ($type === 'all' || $type === 'galeri') {
            $this->optimizeGaleriImages($maxWidth, $quality, $generateWebp, $webpQuality);
        }

        $this->newLine();
        $this->info("Optimization complete!");
        
        $tableData = [
            ['Optimized', $this->optimizedCount],
            ['Skipped', $this->skippedCount],
            ['Failed', $this->failedCount],
        ];
        
        if ($generateWebp) {
            $tableData[] = ['WebP Generated', $this->webpCount];
        }
        
        $this->table(['Status', 'Count'], $tableData);

        return 0;
    }

    protected function optimizeBeritaImages($maxWidth, $quality, $generateWebp = false, $webpQuality = 80)
    {
        $this->info("📰 Optimizing Berita images...");
        
        Berita::whereNotNull('gambar_utama')
            ->chunk(50, function ($beritas) use ($maxWidth, $quality, $generateWebp, $webpQuality) {
                foreach ($beritas as $berita) {
                    $this->optimizeImage(
                        $berita->gambar_utama,
                        "Berita: {$berita->judul}",
                        $maxWidth,
                        $quality,
                        $generateWebp,
                        $webpQuality
                    );
                }
            });
    }

    protected function optimizePotensiImages($maxWidth, $quality, $generateWebp = false, $webpQuality = 80)
    {
        $this->info("🌾 Optimizing Potensi images...");
        
        PotensiDesa::whereNotNull('gambar')
            ->chunk(50, function ($potensis) use ($maxWidth, $quality, $generateWebp, $webpQuality) {
                foreach ($potensis as $potensi) {
                    $this->optimizeImage(
                        $potensi->gambar,
                        "Potensi: {$potensi->nama}",
                        $maxWidth,
                        $quality,
                        $generateWebp,
                        $webpQuality
                    );
                }
            });
    }

    protected function optimizeGaleriImages($maxWidth, $quality, $generateWebp = false, $webpQuality = 80)
    {
        $this->info("📸 Optimizing Galeri images...");
        
        Galeri::whereNotNull('gambar')
            ->chunk(50, function ($galeris) use ($maxWidth, $quality, $generateWebp, $webpQuality) {
                foreach ($galeris as $galeri) {
                    $this->optimizeImage(
                        $galeri->gambar,
                        "Galeri: {$galeri->judul}",
                        $maxWidth,
                        $quality,
                        $generateWebp,
                        $webpQuality
                    );
                }
            });
    }

    protected function optimizeImage($imagePath, $title, $maxWidth, $quality, $generateWebp = false, $webpQuality = 80)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            // Check if file exists
            if (!file_exists($fullPath)) {
                $this->warn("  ⚠️  File not found: {$title}");
                $this->skippedCount++;
                return;
            }

            // Get original file size
            $originalSize = filesize($fullPath);
            
            // Load image
            /** @var \Intervention\Image\Interfaces\ImageInterface $image */
            /** @phpstan-ignore-next-line */
            // @phpcs:ignore
            $image = Image::read($fullPath);
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Skip if already smaller than max width
            if ($originalWidth <= $maxWidth) {
                $this->line("  ⏭️  Already optimized: {$title} ({$originalWidth}x{$originalHeight})");
                $this->skippedCount++;
                
                // Still generate WebP if requested
                if ($generateWebp) {
                    $this->generateWebP($fullPath, $imagePath, $webpQuality, $title);
                }
                
                return;
            }

            // Resize image maintaining aspect ratio
            $image->scale(width: $maxWidth);
            
            // Save with compression
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                $image->toJpeg($quality)->save($fullPath);
            } elseif (strtolower($extension) === 'png') {
                // PNG compression (0-9, where 9 is maximum compression)
                $pngQuality = (int) (9 - ($quality / 11));
                $image->toPng()->save($fullPath);
            } else {
                $image->save($fullPath);
            }

            // Get new file size
            $newSize = filesize($fullPath);
            $savedBytes = $originalSize - $newSize;
            $savedPercent = round(($savedBytes / $originalSize) * 100, 1);
            
            $this->info("  ✅ Optimized: {$title}");
            $this->line("      {$originalWidth}x{$originalHeight} → {$maxWidth}x{$image->height()}");
            $this->line("      " . $this->formatBytes($originalSize) . " → " . $this->formatBytes($newSize) . " (saved {$savedPercent}%)");
            
            $this->optimizedCount++;
            
            // Generate WebP version if requested
            if ($generateWebp) {
                $this->generateWebP($fullPath, $imagePath, $webpQuality, $title);
            }

        } catch (\Exception $e) {
            $this->error("  ❌ Failed: {$title}");
            $this->error("      " . $e->getMessage());
            $this->failedCount++;
        }
    }

    /**
     * Generate WebP version of an image
     */
    protected function generateWebP($fullPath, $imagePath, $quality, $title)
    {
        try {
            // Create WebP filename
            $pathInfo = pathinfo($imagePath);
            $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
            $fullWebpPath = storage_path('app/public/' . $webpPath);
            
            // Check if WebP already exists
            if (file_exists($fullWebpPath)) {
                $this->line("      🔄 WebP already exists");
                return;
            }
            
            // Load image and convert to WebP
            /** @var \Intervention\Image\Interfaces\ImageInterface $image */
            /** @phpstan-ignore-next-line */
            // @phpcs:ignore
            $image = Image::read($fullPath);
            $image->toWebp($quality)->save($fullWebpPath);
            
            // Get file sizes
            $originalSize = filesize($fullPath);
            $webpSize = filesize($fullWebpPath);
            $savedPercent = round((($originalSize - $webpSize) / $originalSize) * 100, 1);
            
            $this->line("      🌐 WebP created: " . $this->formatBytes($webpSize) . " (saved {$savedPercent}%)");
            $this->webpCount++;
            
        } catch (\Exception $e) {
            $this->warn("      ⚠️  WebP generation failed: " . $e->getMessage());
        }
    }

    protected function formatBytes($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}
