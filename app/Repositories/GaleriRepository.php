<?php

namespace App\Repositories;

use App\Models\Galeri;

class GaleriRepository extends BaseRepository
{
    /**
     * Constructor - inject Galeri model
     */
    public function __construct(Galeri $model)
    {
        parent::__construct($model);
    }

    /**
     * Mengambil galeri yang aktif dengan pagination
     * - Include relasi admin dan images untuk prevent N+1 query
     * - Filter hanya yang published (is_active = true)
     * - Sort terbaru dahulu
     * 
     * @param int $perPage - jumlah item per halaman (default: 24)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getActive($perPage = 24)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->published()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mengambil galeri terbaru untuk homepage atau widget
     * - Include relasi admin dan images
     * - Filter published
     * - Limit jumlah hasil
     * 
     * @param int $limit - jumlah galeri yang diambil (default: 6)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatest($limit = 6)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->published()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Mengambil galeri berdasarkan kategori tertentu
     * - Filter by kategori (kegiatan, infrastruktur, budaya, umum)
     * - Include relasi dan filter published
     * 
     * @param string $kategori - nama kategori
     * @param int $perPage - jumlah item per halaman
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByKategori($kategori, $perPage = 24)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->published()
            ->byKategori($kategori)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mengambil daftar kategori beserta jumlah galeri per kategori
     * Untuk filter/navigation di halaman public
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesWithCount()
    {
        return $this->model
            ->published()
            ->selectRaw('kategori, COUNT(*) as count')
            ->groupBy('kategori')
            ->get();
    }

    /**
     * Mengambil galeri berdasarkan admin yang upload
     * Untuk halaman "Galeri Saya" di admin panel
     * 
     * @param int $adminId - ID admin
     * @param int $perPage - jumlah item per halaman
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByAdmin($adminId, $perPage = 15)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->where('admin_id', $adminId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mengambil galeri dalam rentang tanggal tertentu
     * Untuk filter berdasarkan periode waktu
     * 
     * @param string $startDate - tanggal mulai
     * @param string $endDate - tanggal akhir
     * @param int $perPage - jumlah item per halaman
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getByDateRange($startDate, $endDate, $perPage = 24)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->published()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mengambil galeri terbaru yang memiliki tanggal
     * Sort berdasarkan tanggal galeri (bukan created_at)
     * 
     * @param int $limit - jumlah galeri yang diambil
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecent($limit = 12)
    {
        return $this->model
            ->with(['admin', 'images']) // Eager load admin and images to prevent N+1
            ->published()
            ->whereNotNull('tanggal')
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Toggle status aktif/tidak aktif galeri
     * Untuk enable/disable galeri tanpa menghapusnya
     * 
     * @param int $id - ID galeri
     * @return \App\Models\Galeri
     */
    public function toggleActive($id)
    {
        $galeri = $this->find($id);
        $galeri->is_active = !$galeri->is_active;
        $galeri->save();
        
        return $galeri;
    }
}
