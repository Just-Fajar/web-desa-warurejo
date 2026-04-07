<?php

namespace App\Repositories;

use App\Models\Berita;

class BeritaRepository extends BaseRepository
{
    /**
     * Constructor - Inject Berita model
     * Repository layer untuk database queries berita
     */
    public function __construct(Berita $model)
    {
        parent::__construct($model);
    }

    /**
     * Get published berita dengan pagination untuk halaman public
     * Eager load admin untuk prevent N+1 query
     */
    public function getPublished($perPage = 10)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->published()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get berita terbaru untuk homepage atau sidebar
     * Limited untuk performance
     */
    public function getLatest($limit = 5)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->published()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Find berita by slug untuk detail page (SEO-friendly URL)
     * Throw 404 jika tidak ditemukan atau belum published
     */
    public function findBySlug($slug)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
    }

    /**
     * Increment views counter untuk tracking popularitas
     * Direct DB query untuk performance (tidak load full model)
     */
    public function incrementViews($id)
    {
        return $this->model
            ->where('id', $id)
            ->increment('views');
    }

    /**
     * Get berita by status (published/draft) untuk admin list
     * Sort: published by latest, draft by created_at
     */
    public function getByStatus($status, $perPage = 15)
    {
        $query = $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->where('status', $status);
        
        if ($status === 'published') {
            $query->latest();
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Simple search berita by keyword di judul, ringkasan, atau konten
     * Hanya published untuk public
     */
    public function search($keyword, $perPage = 10)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->where(function($query) use ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('ringkasan', 'like', "%{$keyword}%")
                    ->orWhere('konten', 'like', "%{$keyword}%");
            })
            ->published()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get berita populer berdasarkan jumlah views
     * Untuk widget "Berita Populer" di sidebar
     */
    public function getPopular($limit = 5)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->published()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get berita by admin tertentu (all status)
     * Untuk filter "Berita Saya" di admin
     */
    public function getByAdmin($adminId, $perPage = 15)
    {
        return $this->model
            ->with('admin') // Eager load admin to prevent N+1
            ->where('admin_id', $adminId)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Advanced search dengan multiple filters
     * Filters: keyword, date_from, date_to, sort (latest/popular/oldest)
     * Untuk halaman pencarian dengan filter lengkap
     */
    public function advancedSearch(array $filters, $perPage = 12)
    {
        $query = $this->model->with('admin')->published();
        
        // Search by keyword
        if (!empty($filters['search'])) {
            $keyword = $filters['search'];
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('ringkasan', 'like', "%{$keyword}%")
                  ->orWhere('konten', 'like', "%{$keyword}%");
            });
        }
        
        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('published_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->whereDate('published_at', '<=', $filters['date_to']);
        }
        
        // Sort by
        $sortBy = $filters['sort'] ?? 'latest';
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        return $query->paginate($perPage);
    }
    
    /**
     * Get search suggestions untuk autocomplete dropdown
     * Return array dengan title dan URL untuk quick navigation
     */
    public function getSearchSuggestions($query, $limit = 5)
    {
        return $this->model
            ->published()
            ->where('judul', 'like', "%{$query}%")
            ->select('judul', 'slug')
            ->limit($limit)
            ->get()
            ->map(function($berita) {
                return [
                    'title' => $berita->judul,
                    'url' => route('berita.show', $berita->slug)
                ];
            });
    }
}
