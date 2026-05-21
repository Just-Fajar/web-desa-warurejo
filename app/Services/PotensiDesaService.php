<?php

namespace App\Services;

use App\Models\PotensiDesaFoto;
use App\Repositories\PotensiDesaRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PotensiDesaService
{
    protected $potensiRepository;

    protected $htmlSanitizer;

    /**
     * Constructor - Inject repository dan HTML sanitizer
     * Service untuk business logic Potensi Desa (wisata, UMKM, pertanian, dll)
     */
    public function __construct(
        PotensiDesaRepository $potensiRepository,
        HtmlSanitizerService $htmlSanitizer
    ) {
        $this->potensiRepository = $potensiRepository;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    /**
     * Get semua potensi desa tanpa filter (untuk admin)
     */
    public function getAllPotensi()
    {
        return $this->potensiRepository->all();
    }

    /**
     * Get hanya potensi aktif untuk ditampilkan ke public
     */
    public function getActivePotensi()
    {
        return $this->potensiRepository->getActive();
    }

    /**
     * Get potensi berdasarkan kategori (Wisata, UMKM, Pertanian, dll)
     */
    public function getPotensiByKategori($kategori)
    {
        return $this->potensiRepository->getByKategori($kategori);
    }

    /**
     * Get single potensi by ID untuk edit di admin
     */
    public function getPotensiById($id)
    {
        return $this->potensiRepository->find($id);
    }

    /**
     * Get potensi by slug untuk detail page public (SEO-friendly URL)
     */
    public function getPotensiBySlug($slug)
    {
        return $this->potensiRepository->findBySlug($slug);
    }

    /**
     * Get potensi terkait berdasarkan kategori yang sama
     * Untuk "Potensi Lainnya" di detail page
     */
    public function getRelatedPotensi($potensi, $limit = 3)
    {
        return $this->potensiRepository->getRelated($potensi, $limit);
    }

    /**
     * Get potensi unggulan untuk homepage
     */
    public function getFeaturedPotensi($limit = 6)
    {
        return $this->potensiRepository->getFeatured($limit);
    }

    /**
     * Get daftar kategori beserta jumlah potensi per kategori
     * Untuk filter/navigation di halaman potensi
     */
    public function getCategoriesWithCount()
    {
        return $this->potensiRepository->getCategoriesWithCount();
    }

    /**
     * Create potensi baru
     * - Sanitize HTML deskripsi untuk prevent XSS
     * - Upload gambar jika ada
     * - Auto generate slug dari nama jika kosong
     * - Handle foto galeri jika ada
     * - Clear cache homepage setelah create
     */
    public function createPotensi(array $data, $fotoGaleri = null)
    {
        // Sanitize HTML content
        if (isset($data['deskripsi'])) {
            $data['deskripsi'] = $this->htmlSanitizer->sanitize($data['deskripsi']);
        }

        if (isset($data['gambar'])) {
            $data['gambar'] = $this->uploadImage($data['gambar']);
        }

        // Auto generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['nama']);
        }

        $potensi = $this->potensiRepository->create($data);

        // Handle foto galeri upload
        if ($fotoGaleri && is_array($fotoGaleri)) {
            $this->uploadFotoGaleri($potensi, $fotoGaleri);
        }

        // Clear cache when new potensi is created
        $this->clearCache();

        return $potensi;
    }

    /**
     * Update potensi
     * - Sanitize HTML deskripsi
     * - Jika ada gambar baru, delete lama lalu upload baru
     * - Update slug jika nama berubah
     * - Handle foto galeri baru
     * - Clear cache setelah update
     */
    public function updatePotensi($id, array $data, $fotoGaleri = null)
    {
        $potensi = $this->potensiRepository->find($id);

        // Sanitize HTML content
        if (isset($data['deskripsi'])) {
            $data['deskripsi'] = $this->htmlSanitizer->sanitize($data['deskripsi']);
        }

        // Handle new image upload (check if it's a file object, not null/string)
        if (isset($data['gambar']) && is_object($data['gambar'])) {
            // Delete old image
            if ($potensi->gambar) {
                Storage::disk('public')->delete($potensi->gambar);
            }
            $data['gambar'] = $this->uploadImage($data['gambar']);
        } else {
            // Keep existing image if no new upload - REMOVE from data array
            unset($data['gambar']);
        }

        // Update slug if nama changed
        if (isset($data['nama']) && $data['nama'] !== $potensi->nama) {
            $data['slug'] = Str::slug($data['nama']);
        }

        $updated = $this->potensiRepository->update($id, $data);

        // Handle foto galeri upload
        if ($fotoGaleri && is_array($fotoGaleri)) {
            $this->uploadFotoGaleri($potensi, $fotoGaleri);
        }

        // Clear cache when potensi is updated
        $this->clearCache();

        return $updated;
    }

    /**
     * Delete potensi beserta file gambar dan foto galeri
     * Clear cache setelah delete
     */
    public function deletePotensi($id)
    {
        $potensi = $this->potensiRepository->find($id);

        // Delete main image if exists
        if ($potensi->gambar) {
            Storage::disk('public')->delete($potensi->gambar);
        }

        // Delete all gallery photos from storage
        foreach ($potensi->fotoGaleri as $foto) {
            Storage::disk('public')->delete($foto->foto);
        }

        $deleted = $this->potensiRepository->delete($id);

        // Clear cache when potensi is deleted
        $this->clearCache();

        return $deleted;
    }

    /**
     * Upload multiple foto galeri untuk sebuah potensi
     */
    public function uploadFotoGaleri($potensi, array $files)
    {
        // Get current max urutan
        $maxUrutan = $potensi->fotoGaleri()->max('urutan') ?? 0;

        foreach ($files as $index => $file) {
            $filename = time().'_galeri_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('potensi/galeri', $filename, 'public');

            PotensiDesaFoto::create([
                'potensi_desa_id' => $potensi->id,
                'foto' => $path,
                'urutan' => $maxUrutan + $index + 1,
            ]);
        }
    }

    /**
     * Hapus satu foto galeri by ID
     */
    public function deleteFotoGaleri($fotoId)
    {
        $foto = PotensiDesaFoto::findOrFail($fotoId);
        Storage::disk('public')->delete($foto->foto);
        $foto->delete();

        $this->clearCache();
    }

    /**
     * Reorder urutan tampilan potensi
     * Untuk drag & drop sorting di admin
     */
    public function reorderPotensi(array $order)
    {
        return $this->potensiRepository->reorder($order);
    }

    /**
     * Search potensi by nama atau deskripsi
     * Simple search untuk admin
     */
    public function searchPotensi($keyword)
    {
        return $this->potensiRepository->search($keyword);
    }

    /**
     * Advanced search dengan multiple filters
     * Filters: keyword, kategori, sorting, dll
     * Untuk halaman public dengan filter lengkap
     */
    public function searchWithFilters(array $filters)
    {
        return $this->potensiRepository->searchWithFilters($filters);
    }

    /**
     * Upload gambar ke storage/potensi
     * Generate unique filename: timestamp_random.extension
     */
    protected function uploadImage($image)
    {
        $filename = time().'_'.Str::random(10).'.'.$image->getClientOriginalExtension();
        $path = $image->storeAs('potensi', $filename, 'public');

        return $path;
    }

    /**
     * Clear all related caches
     */
    protected function clearCache()
    {
        Cache::forget('home.potensi');
        Cache::forget('home.total_potensi');
    }
}
