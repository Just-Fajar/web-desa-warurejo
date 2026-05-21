<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_DRAFT = 'draft';

    const STATUS_SCHEDULED = 'scheduled';

    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'judul',
        'kategori',
        'tahun',
        'deskripsi',
        'file_dokumen',
        'thumbnail',
        'tanggal_publikasi',
        'status',
        'published_at',
        'jumlah_download',
        'views',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
        'published_at' => 'datetime',
        'tahun' => 'integer',
        'jumlah_download' => 'integer',
    ];

    /**
     * Scope untuk publikasi yang sudah published
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('tanggal_publikasi')
                    ->orWhere('tanggal_publikasi', '<=', now());
            });
    }

    /**
     * Scope untuk publikasi draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope untuk publikasi yang dijadwalkan
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    /**
     * Scope: Get konten yang sudah waktunya dipublish
     */
    public function scopeDueForPublishing($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope untuk filter by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter by tahun
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk ordering terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/'.$this->file_dokumen);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/'.$this->thumbnail);
        }

        return asset('images/default-document.jpg');
    }

    /**
     * Get daftar status yang tersedia
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SCHEDULED => 'Dijadwalkan',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    /**
     * Get warna badge untuk status
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_DRAFT => ['color' => 'yellow', 'label' => 'Draft'],
            self::STATUS_SCHEDULED => ['color' => 'blue', 'label' => 'Dijadwalkan'],
            self::STATUS_PUBLISHED => ['color' => 'green', 'label' => 'Published'],
            default => ['color' => 'gray', 'label' => 'Unknown'],
        };
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('jumlah_download');
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
