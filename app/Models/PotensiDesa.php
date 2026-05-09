<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PotensiDesa extends Model
{
    use HasFactory;

    protected $table = 'potensi_desa';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'nama',
        'slug',
        'kategori',
        'deskripsi',
        'gambar',
        'lokasi',
        'info_utama',
        'nama_pengelola',
        'whatsapp',
        'link_maps',
        'status',
        'published_at',
        'urutan',
        'views',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'published_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relasi ke foto galeri (hasMany)
     */
    public function fotoGaleri()
    {
        return $this->hasMany(PotensiDesaFoto::class)->orderBy('urutan');
    }

    // ==================== ACCESSORS ====================

    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : asset('images/default-potensi.jpg');
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->deskripsi), 150);
    }

    /**
     * Format nomor WA ke link wa.me
     * Contoh: 081234567802 → wa.me/6281234567802
     */
    public function getWhatsappLinkAttribute()
    {
        $nomor = $this->whatsapp;
        if (empty($nomor)) {
            return null;
        }

        // Hilangkan karakter non-digit
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Hilangkan 0 di depan, ganti dengan 62
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        // Jika belum ada 62, tambahkan
        if (!str_starts_with($nomor, '62')) {
            $nomor = '62' . $nomor;
        }

        return 'https://wa.me/' . $nomor;
    }

    /**
     * Format nomor WA untuk display: +62xxx
     */
    public function getWhatsappFormattedAttribute()
    {
        $nomor = $this->whatsapp;
        if (empty($nomor)) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        if (!str_starts_with($nomor, '62')) {
            $nomor = '62' . $nomor;
        }

        return '+' . $nomor;
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Backward-compatible alias for scopePublished
     */
    public function scopeActive($query)
    {
        return $this->scopePublished($query);
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc');
    }



    // ==================== CONSTANTS ====================

    const KATEGORI_PERTANIAN = 'pertanian';
    const KATEGORI_PETERNAKAN = 'peternakan';
    const KATEGORI_PERIKANAN = 'perikanan';
    const KATEGORI_UMKM = 'umkm';
    const KATEGORI_WISATA = 'wisata';
    const KATEGORI_LAINNYA = 'lainnya';

    public static function getKategoriList()
    {
        return [
            self::KATEGORI_PERTANIAN => 'Pertanian',
            self::KATEGORI_PETERNAKAN => 'Peternakan',
            self::KATEGORI_PERIKANAN => 'Perikanan',
            self::KATEGORI_UMKM => 'UMKM',
            self::KATEGORI_WISATA => 'Wisata',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Warna badge untuk tiap kategori (sesuai spesifikasi)
     */
    public static function getKategoriBadgeColors()
    {
        return [
            'pertanian'  => ['bg' => '#DCFCE7', 'text' => '#16A34A'], // Green
            'peternakan' => ['bg' => '#FFEDD5', 'text' => '#EA580C'], // Orange
            'perikanan'  => ['bg' => '#CCFBF1', 'text' => '#0D9488'], // Teal
            'umkm'       => ['bg' => '#F3E8FF', 'text' => '#9333EA'], // Purple
            'wisata'     => ['bg' => '#FFE4E6', 'text' => '#E11D48'], // Rose/Pink
            'lainnya'    => ['bg' => '#F3F4F6', 'text' => '#4B5563'], // Gray
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

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($potensi) {
            if (empty($potensi->slug)) {
                $potensi->slug = Str::slug($potensi->nama);
            }

            // Ensure unique slug
            $originalSlug = $potensi->slug;
            $count = 1;
            while (static::where('slug', $potensi->slug)->exists()) {
                $potensi->slug = $originalSlug . '-' . $count;
                $count++;
            }

            // Auto set urutan if not provided
            if (empty($potensi->urutan)) {
                $maxUrutan = static::max('urutan') ?? 0;
                $potensi->urutan = $maxUrutan + 1;
            }
        });

        static::updating(function ($potensi) {
            // Update slug if nama is dirty
            if ($potensi->isDirty('nama')) {
                $potensi->slug = Str::slug($potensi->nama);
                $originalSlug = $potensi->slug;
                $count = 1;
                while (static::where('slug', $potensi->slug)->where('id', '!=', $potensi->id)->exists()) {
                    $potensi->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    // ==================== METHODS ====================

    /**
     * Method untuk increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
