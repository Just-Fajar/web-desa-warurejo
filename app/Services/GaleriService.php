<?php

namespace App\Services;

use App\Repositories\GaleriRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GaleriService
{
    protected $galeriRepository;

    /**
     * Constructor - Inject GaleriRepository
     * Service layer untuk business logic galeri/foto
     */
    public function __construct(GaleriRepository $galeriRepository)
    {
        $this->galeriRepository = $galeriRepository;
    }

    /**
     * Get semua galeri tanpa filter (untuk admin)
     */
    public function getAllGaleri()
    {
        return $this->galeriRepository->all();
    }

    /**
     * Get galeri dengan pagination untuk list di admin
     */
    public function getPaginatedGaleri($perPage = 15)
    {
        return $this->galeriRepository->paginate($perPage);
    }

    /**
     * Get hanya galeri aktif untuk ditampilkan ke public
     */
    public function getActiveGaleri($perPage = 24)
    {
        return $this->galeriRepository->getActive($perPage);
    }

    /**
     * Get galeri terbaru untuk homepage atau sidebar
     */
    public function getLatestGaleri($limit = 6)
    {
        return $this->galeriRepository->getLatest($limit);
    }

    /**
     * Get galeri berdasarkan kategori (Infrastruktur, Kegiatan, dll)
     */
    public function getGaleriByKategori($kategori, $perPage = 24)
    {
        return $this->galeriRepository->getByKategori($kategori, $perPage);
    }

    /**
     * Get single galeri by ID untuk detail atau edit
     */
    public function getGaleriById($id)
    {
        return $this->galeriRepository->find($id);
    }

    /**
     * Get daftar kategori beserta jumlah galeri per kategori
     * Untuk filter/navigation di halaman galeri
     */
    public function getCategoriesWithCount()
    {
        return $this->galeriRepository->getCategoriesWithCount();
    }

    /**
     * Create galeri baru
     * - Upload gambar jika ada
     * - Set admin_id dari user yang login
     * - Set tanggal ke now() jika kosong
     * - Clear cache homepage setelah create
     */
    public function createGaleri(array $data)
    {
        if (isset($data['gambar'])) {
            $data['gambar'] = $this->uploadImage($data['gambar']);
        }

        if (!isset($data['admin_id'])) {
            $data['admin_id'] = auth()->guard('admin')->id();
        }

        if (empty($data['tanggal'])) {
            $data['tanggal'] = now();
        }

        $galeri = $this->galeriRepository->create($data);

        // Clear cache when new galeri is created
        Cache::forget('home.galeri');

        return $galeri;
    }

    /**
     * Update galeri
     * - Jika ada gambar baru, delete gambar lama lalu upload baru
     * - Jika tidak ada gambar baru, keep gambar lama (unset dari data)
     * - Clear cache setelah update
     */
    public function updateGaleri($id, array $data)
    {
        $galeri = $this->galeriRepository->find($id);

        // Handle new image upload (check if it's a file object, not null/string)
        if (isset($data['gambar']) && is_object($data['gambar'])) {
            // Delete old image
            if ($galeri->gambar) {
                Storage::disk('public')->delete($galeri->gambar);
            }
            $data['gambar'] = $this->uploadImage($data['gambar']);
        } else {
            // Keep existing image if no new upload - REMOVE from data array
            unset($data['gambar']);
        }

        $updated = $this->galeriRepository->update($id, $data);

        // Clear cache when galeri is updated
        Cache::forget('home.galeri');

        return $updated;
    }

    /**
     * Delete galeri beserta file gambarnya
     * Clear cache setelah delete
     */
    public function deleteGaleri($id)
    {
        $galeri = $this->galeriRepository->find($id);
        
        // Delete image if exists
        if ($galeri->gambar) {
            Storage::disk('public')->delete($galeri->gambar);
        }

        $deleted = $this->galeriRepository->delete($id);

        // Clear cache when galeri is deleted
        Cache::forget('home.galeri');

        return $deleted;
    }

    /**
     * Toggle status aktif/non-aktif galeri
     * Untuk hide/show tanpa delete permanent
     */
    public function toggleActive($id)
    {
        return $this->galeriRepository->toggleActive($id);
    }

    /**
     * Get galeri dalam rentang tanggal tertentu
     * Untuk filter atau laporan
     */
    public function getByDateRange($startDate, $endDate, $perPage = 24)
    {
        return $this->galeriRepository->getByDateRange($startDate, $endDate, $perPage);
    }

    /**
     * Get galeri terbaru untuk widget atau sidebar
     */
    public function getRecentGaleri($limit = 12)
    {
        return $this->galeriRepository->getRecent($limit);
    }

    /**
     * Upload gambar ke storage/galeri
     * Generate unique filename: timestamp_random.extension
     */
    protected function uploadImage($image)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('galeri', $filename, 'public');
        return $path;
    }
}
