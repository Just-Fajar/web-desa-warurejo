<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'admin_id',
        'judul',
        'deskripsi',
        'gambar',
        'kategori',
        'status',
        'published_at',
        'tanggal',
        'views',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function images()
    {
        return $this->hasMany(GaleriImage::class)->orderBy('urutan');
    }

    // Accessors
    public function getGambarUrlAttribute()
    {
        // Jika ada multiple images, ambil yang pertama
        if ($this->images && $this->images->count() > 0) {
            return $this->images->first()->image_url;
        }

        // Fallback ke gambar single jika ada
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : asset('images/default-gallery.jpg');
    }

    public function getFormattedDateAttribute()
    {
        return $this->tanggal
            ? $this->tanggal->format('d F Y')
            : $this->created_at->format('d F Y');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    public function scopeDueForPublishing($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');
    }

    // Constants for categories
    const KATEGORI_KEGIATAN = 'kegiatan';
    const KATEGORI_PEMBANGUNAN = 'pembangunan';
    const KATEGORI_BUDAYA = 'budaya';
    const KATEGORI_KEAGAMAAN = 'keagamaan';
    const KATEGORI_SOSIAL = 'sosial';
    const KATEGORI_LAINNYA = 'lainnya';

    public static function getKategoriList()
    {
        return [
            self::KATEGORI_KEGIATAN => 'Kegiatan',
            self::KATEGORI_PEMBANGUNAN => 'Pembangunan',
            self::KATEGORI_BUDAYA => 'Budaya',
            self::KATEGORI_KEAGAMAAN => 'Keagamaan',
            self::KATEGORI_SOSIAL => 'Sosial',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
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

    // Method untuk increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}
