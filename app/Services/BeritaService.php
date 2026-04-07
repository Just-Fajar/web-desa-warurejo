<?php

namespace App\Services;

use App\Repositories\BeritaRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BeritaService
{
    protected $beritaRepository;
    protected $imageUploadService;
    protected $htmlSanitizer;

    /**
     * Constructor - inject dependencies untuk handle berita operations
     * @param BeritaRepository $beritaRepository - untuk database operations
     * @param ImageUploadService $imageUploadService - untuk handle upload & resize image
     * @param HtmlSanitizerService $htmlSanitizer - untuk sanitize HTML content
     */
    public function __construct(
        BeritaRepository $beritaRepository,
        ImageUploadService $imageUploadService,
        HtmlSanitizerService $htmlSanitizer
    ) {
        $this->beritaRepository = $beritaRepository;
        $this->imageUploadService = $imageUploadService;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    /**
     * Mengambil semua berita tanpa filter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBerita()
    {
        return $this->beritaRepository->all();
    }

    /**
     * Mengambil berita dengan pagination untuk halaman admin
     * @param int $perPage - jumlah item per halaman (default: 15)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedBerita($perPage = 15)
    {
        return $this->beritaRepository->paginate($perPage);
    }

    /**
     * Mengambil berita yang sudah published untuk halaman public
     * @param int $perPage - jumlah item per halaman (default: 10)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPublishedBerita($perPage = 10)
    {
        return $this->beritaRepository->getPublished($perPage);
    }

    /**
     * Mengambil satu berita berdasarkan ID
     * @param int $id - ID berita
     * @return \App\Models\Berita
     */
    public function getBeritaById($id)
    {
        return $this->beritaRepository->find($id);
    }

    /**
     * Mengambil berita berdasarkan slug untuk halaman detail public
     * Otomatis increment views count saat berita dilihat
     * @param string $slug - slug berita
     * @return \App\Models\Berita
     */
    public function getBeritaBySlug($slug)
    {
        $berita = $this->beritaRepository->findBySlug($slug);
        $this->beritaRepository->incrementViews($berita->id);
        return $berita;
    }

    /**
     * Membuat berita baru
     * - Sanitize HTML content untuk keamanan
     * - Upload dan resize gambar utama
     * - Generate thumbnail untuk performa loading
     * - Set published_at jika status published
     * - Clear cache terkait
     * 
     * @param array $data - data berita dari form
     * @return \App\Models\Berita
     */
    public function createBerita(array $data)
    {
        // Sanitize HTML content
        if (isset($data['konten'])) {
            $data['konten'] = $this->htmlSanitizer->sanitize($data['konten']);
        }
        if (isset($data['ringkasan'])) {
            $data['ringkasan'] = $this->htmlSanitizer->sanitize($data['ringkasan']);
        }

        if (isset($data['gambar_utama'])) {
            $data['gambar_utama'] = $this->uploadImage($data['gambar_utama']);
            
            // Generate thumbnail (optional)
            if ($data['gambar_utama']) {
                $this->generateThumbnail($data['gambar_utama']);
            }
        }

        if ($data['status'] === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        $berita = $this->beritaRepository->create($data);

        // Clear cache when new berita is created
        Cache::forget('home.latest_berita');
        Cache::forget('berita.published');
        Cache::forget('home.seo_data');

        return $berita;
    }

    /**
     * Update berita yang sudah ada
     * - Sanitize HTML content
     * - Handle remove image (hapus gambar lama jika checkbox di centang)
     * - Handle upload gambar baru (hapus lama, upload baru, generate thumbnail)
     * - Jika tidak ada perubahan gambar, tetap gunakan gambar lama
     * - Set published_at jika status berubah ke published
     * - Clear cache terkait
     * 
     * @param int $id - ID berita yang akan diupdate
     * @param array $data - data berita dari form
     * @return \App\Models\Berita
     */
    public function updateBerita($id, array $data)
    {
        $berita = $this->beritaRepository->find($id);

        // Sanitize HTML content
        if (isset($data['konten'])) {
            $data['konten'] = $this->htmlSanitizer->sanitize($data['konten']);
        }
        if (isset($data['ringkasan'])) {
            $data['ringkasan'] = $this->htmlSanitizer->sanitize($data['ringkasan']);
        }

        // Handle remove image checkbox
        if (isset($data['remove_image']) && $data['remove_image']) {
            if ($berita->gambar_utama) {
                Storage::disk('public')->delete($berita->gambar_utama);
            }
            $data['gambar_utama'] = null;
            unset($data['remove_image']);
        }
        // Handle new image upload (check if it's a file object, not null/string)
        elseif (isset($data['gambar_utama']) && is_object($data['gambar_utama'])) {
            // Delete old image
            if ($berita->gambar_utama) {
                $this->imageUploadService->delete($berita->gambar_utama);
                
                // Delete old thumbnail if exists
                $oldThumbnail = 'thumbnails/berita/' . pathinfo($berita->gambar_utama, PATHINFO_FILENAME) . '_thumb.' . pathinfo($berita->gambar_utama, PATHINFO_EXTENSION);
                $this->imageUploadService->delete($oldThumbnail);
            }
            
            $data['gambar_utama'] = $this->uploadImage($data['gambar_utama']);
            
            // Generate new thumbnail
            if ($data['gambar_utama']) {
                $this->generateThumbnail($data['gambar_utama']);
            }
        } else {
            // Keep existing image if no new upload - REMOVE from data array
            unset($data['gambar_utama']);
        }

        // Remove remove_image field if exists
        if (isset($data['remove_image'])) {
            unset($data['remove_image']);
        }

        if (isset($data['status']) && $data['status'] === 'published' && !$berita->published_at) {
            $data['published_at'] = now();
        }

        $updatedBerita = $this->beritaRepository->update($id, $data);

        // Clear cache when berita is updated
        Cache::forget('home.latest_berita');
        Cache::forget('berita.published');
        Cache::forget('berita.' . $id);
        Cache::forget('home.seo_data');

        return $updatedBerita;
    }

    /**
     * Hapus berita beserta file gambar dan thumbnail nya
     * - Delete gambar utama dari storage
     * - Delete thumbnail dari storage
     * - Delete record dari database
     * - Clear cache terkait
     * 
     * @param int $id - ID berita yang akan dihapus
     * @return bool
     */
    public function deleteBerita($id)
    {
        $berita = $this->beritaRepository->find($id);
        
        // Delete image if exists
        if ($berita->gambar_utama) {
            $this->imageUploadService->delete($berita->gambar_utama);
            
            // Delete thumbnail if exists
            $thumbnailPath = 'thumbnails/berita/' . pathinfo($berita->gambar_utama, PATHINFO_FILENAME) . '_thumb.' . pathinfo($berita->gambar_utama, PATHINFO_EXTENSION);
            $this->imageUploadService->delete($thumbnailPath);
        }

        $deleted = $this->beritaRepository->delete($id);

        // Clear cache when berita is deleted
        Cache::forget('home.latest_berita');
        Cache::forget('berita.published');
        Cache::forget('berita.' . $id);
        Cache::forget('home.seo_data');

        return $deleted;
    }

    /**
     * Mengambil berita terbaru untuk homepage atau widget
     * @param int $limit - jumlah berita yang diambil (default: 5)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestBerita($limit = 5)
    {
        return $this->beritaRepository->getLatest($limit);
    }

    /**
     * Search berita berdasarkan keyword di judul atau konten
     * @param string $keyword - kata kunci pencarian
     * @param int $perPage - jumlah item per halaman
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchBerita($keyword, $perPage = 12)
    {
        return $this->beritaRepository->search($keyword, $perPage);
    }
    
    /**
     * Advanced search dengan multiple filters (kategori, status, tanggal, dll)
     * @param array $filters - array filter yang akan diaplikasikan
     * @param int $perPage - jumlah item per halaman
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchWithFilters(array $filters, $perPage = 12)
    {
        return $this->beritaRepository->advancedSearch($filters, $perPage);
    }
    
    /**
     * Mendapatkan saran pencarian untuk autocomplete
     * @param string $query - partial keyword dari user
     * @param int $limit - jumlah saran maksimal
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSearchSuggestions($query, $limit = 5)
    {
        return $this->beritaRepository->getSearchSuggestions($query, $limit);
    }

    /**
     * Protected: Upload dan optimize gambar berita
     * - Resize ke max width 1200px untuk performa
     * - Compress dan optimize gambar
     * - Simpan ke folder berita di storage
     * 
     * @param \Illuminate\Http\UploadedFile $image - file gambar dari form
     * @return string - path gambar yang tersimpan
     */
    protected function uploadImage($image)
    {
        // Gunakan ImageUploadService untuk resize & optimize
        $path = $this->imageUploadService->upload(
            $image,
            'berita',      // folder
            1200,          // max width
            null           // max height (auto aspect ratio)
        );
        
        return $path;
    }

    /**
     * Protected: Generate thumbnail dari gambar yang sudah diupload
     * - Buat versi kecil (400x300) untuk card/preview
     * - Simpan di folder thumbnails/berita
     * - Return null jika gagal (tidak critical)
     * 
     * @param string $imagePath - path gambar utama
     * @return string|null - path thumbnail atau null jika gagal
     */
    protected function generateThumbnail($imagePath)
    {
        try {
            // Generate thumbnail using ImageUploadService
            $thumbnailPath = $this->imageUploadService->createThumbnailFromPath(
                $imagePath,
                'thumbnails/berita',  // folder
                400,                   // width
                300                    // height
            );
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
}