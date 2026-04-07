<?php

namespace App\Repositories;

use App\Models\PotensiDesa;

class PotensiDesaRepository extends BaseRepository
{
    /**
     * Constructor - Inject PotensiDesa model
     * Repository untuk database queries potensi desa
     */
    public function __construct(PotensiDesa $model)
    {
        parent::__construct($model);
    }

    /**
     * Get hanya potensi aktif dengan pagination
     * Untuk halaman list potensi di public
     */
    public function getActive()
    {
        return $this->model
            ->active()
            ->latest()
            ->paginate(12);
    }

    /**
     * Get potensi by kategori (Wisata, UMKM, Pertanian, dll)
     * Untuk filter kategori di halaman potensi
     */
    public function getByKategori($kategori)
    {
        return $this->model
            ->active()
            ->byKategori($kategori)
            ->latest()
            ->paginate(12);
    }

    /**
     * Find potensi by slug untuk detail page (SEO-friendly URL)
     * Throw 404 jika tidak ditemukan atau tidak aktif
     */
    public function findBySlug($slug)
    {
        return $this->model
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();
    }

    /**
     * Get related potensi (same category, different ID)
     * Untuk "Potensi Lainnya" di detail page
     */
    public function getRelated($potensi, $limit = 3)
    {
        return $this->model
            ->active()
            ->where('id', '!=', $potensi->id)
            ->where('kategori', $potensi->kategori)
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Get daftar kategori beserta jumlah potensi per kategori
     * Untuk navigation filter di halaman potensi
     */
    public function getCategoriesWithCount()
    {
        return $this->model
            ->active()
            ->selectRaw('kategori, COUNT(*) as count')
            ->groupBy('kategori')
            ->get();
    }

    /**
     * Reorder urutan tampilan potensi
     * Array $order: [position => id], update kolom 'urutan'
     */
    public function reorder(array $order)
    {
        foreach ($order as $position => $id) {
            $this->model
                ->where('id', $id)
                ->update(['urutan' => $position + 1]);
        }
        
        return true;
    }

    /**
     * Toggle status aktif/non-aktif potensi
     * Untuk hide/show tanpa delete permanent
     */
    public function toggleActive($id)
    {
        $potensi = $this->find($id);
        $potensi->is_active = !$potensi->is_active;
        $potensi->save();
        
        return $potensi;
    }

    /**
     * Get featured potensi (first N items by urutan)
     * Untuk homepage showcase potensi unggulan
     */
    public function getFeatured($limit = 6)
    {
        return $this->model
            ->active()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Simple search potensi by nama atau deskripsi
     * Return collection (tidak paginate) untuk autocomplete
     */
    public function search($keyword)
    {
        return $this->model
            ->active()
            ->where(function($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            })
            ->ordered()
            ->get();
    }
    
    /**
     * Advanced search dengan multiple filters
     * Filters: search (keyword), kategori, urutkan (terbaru/terpopuler/terlama)
     * Untuk halaman pencarian dengan filter lengkap
     */
    public function searchWithFilters(array $filters)
    {
        $query = $this->model->active();
        
        // Search by keyword
        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }
        
        // Filter by kategori
        if (!empty($filters['kategori'])) {
            $query->byKategori($filters['kategori']);
        }
        
        // Sort by
        $sortBy = $filters['urutkan'] ?? 'terbaru';
        switch ($sortBy) {
            case 'terpopuler':
                $query->orderBy('views', 'desc');
                break;
            case 'terlama':
                $query->oldest();
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }
        
        return $query->paginate(12);
    }
}
