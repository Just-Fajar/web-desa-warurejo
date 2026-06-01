<?php

namespace App\Console\Commands;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\GaleriImage;
use App\Models\PotensiDesa;
use App\Models\PotensiDesaFoto;
use App\Models\Publikasi;
use App\Models\StrukturOrganisasi;
use App\Services\ImageCompressionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ConvertImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all existing JPG/PNG database-tracked images to WebP and update paths in database';

    protected $compressionService;

    /**
     * Create a new command instance.
     */
    public function __construct(ImageCompressionService $compressionService)
    {
        parent::__construct();
        $this->compressionService = $compressionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting WebP conversion for all database-tracked images...');
        
        $totalConverted = 0;
        $totalSkipped = 0;
        $totalFailed = 0;

        // 1. Berita: gambar_utama
        $this->info('Converting Berita (gambar_utama)...');
        $beritas = Berita::whereNotNull('gambar_utama')->get();
        if ($beritas->isEmpty()) {
            $this->line('No news images to convert.');
        } else {
            $this->withProgressBar($beritas, function ($berita) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($berita, 'gambar_utama', 'berita');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 2. PotensiDesa: gambar
        $this->info('Converting PotensiDesa (gambar)...');
        $potensis = PotensiDesa::whereNotNull('gambar')->get();
        if ($potensis->isEmpty()) {
            $this->line('No village potential images to convert.');
        } else {
            $this->withProgressBar($potensis, function ($potensi) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($potensi, 'gambar', 'potensi');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 3. PotensiDesaFoto: foto
        $this->info('Converting PotensiDesaFoto (foto)...');
        $potensiFotos = PotensiDesaFoto::whereNotNull('foto')->get();
        if ($potensiFotos->isEmpty()) {
            $this->line('No village potential gallery images to convert.');
        } else {
            $this->withProgressBar($potensiFotos, function ($foto) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($foto, 'foto', 'potensi');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 4. Galeri: gambar
        $this->info('Converting Galeri (gambar)...');
        $galeris = Galeri::whereNotNull('gambar')->get();
        if ($galeris->isEmpty()) {
            $this->line('No gallery cover images to convert.');
        } else {
            $this->withProgressBar($galeris, function ($galeri) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($galeri, 'gambar', 'galeri');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 5. GaleriImage: image_path
        $this->info('Converting GaleriImage (image_path)...');
        $galeriImages = GaleriImage::whereNotNull('image_path')->get();
        if ($galeriImages->isEmpty()) {
            $this->line('No gallery images to convert.');
        } else {
            $this->withProgressBar($galeriImages, function ($galeriImage) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($galeriImage, 'image_path', 'galeri');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 6. StrukturOrganisasi: foto
        $this->info('Converting StrukturOrganisasi (foto)...');
        $strukturs = StrukturOrganisasi::whereNotNull('foto')->get();
        if ($strukturs->isEmpty()) {
            $this->line('No organization structure photos to convert.');
        } else {
            $this->withProgressBar($strukturs, function ($struktur) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($struktur, 'foto', 'struktur_organisasi');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        // 7. Publikasi: thumbnail
        $this->info('Converting Publikasi (thumbnail)...');
        $publikasis = Publikasi::whereNotNull('thumbnail')->get();
        if ($publikasis->isEmpty()) {
            $this->line('No publication thumbnails to convert.');
        } else {
            $this->withProgressBar($publikasis, function ($publikasi) use (&$totalConverted, &$totalSkipped, &$totalFailed) {
                $result = $this->convertField($publikasi, 'thumbnail', 'publikasi');
                if ($result === true) $totalConverted++;
                elseif ($result === false) $totalFailed++;
                else $totalSkipped++;
            });
            $this->newLine();
        }

        $this->newLine();
        $this->info('WebP Image Conversion Completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Successfully Converted', $totalConverted],
                ['Skipped (Already WebP or not found)', $totalSkipped],
                ['Failed', $totalFailed],
            ]
        );

        return 0;
    }

    /**
     * Convert field value and update model
     *
     * @return bool|null true = converted, false = failed, null = skipped
     */
    protected function convertField($model, string $field, string $folder)
    {
        $oldPath = $model->$field;

        if (!$oldPath) {
            return null;
        }

        // Skip if already webp
        if (strtolower(pathinfo($oldPath, PATHINFO_EXTENSION)) === 'webp') {
            return null;
        }

        if (!Storage::disk('public')->exists($oldPath)) {
            Log::warning("File not found in storage: {$oldPath}");
            return null;
        }

        try {
            // Convert to webp
            $newPath = $this->compressionService->convertToWebP($oldPath, $folder);

            // Update database field
            $model->$field = $newPath;
            $model->save();

            // Delete original file
            Storage::disk('public')->delete($oldPath);

            Log::info("Converted {$oldPath} to {$newPath} and updated database.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to convert image {$oldPath}: " . $e->getMessage());
            return false;
        }
    }
}
