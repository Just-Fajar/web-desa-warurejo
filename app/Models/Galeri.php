<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'admin_id',
        'judul',
        'deskripsi',
        'gambar',
        'kategori',
        'tanggal',
        'is_active',
        'views',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'is_active' => 'boolean',
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('tanggal')
                    ->orWhere('tanggal', '<=', now());
            });
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
    const KATEGORI_INFRASTRUKTUR = 'infrastruktur';
    const KATEGORI_BUDAYA = 'budaya';
    const KATEGORI_UMKM = 'umkm';
    const KATEGORI_LAINNYA = 'lainnya';

    public static function getKategoriList()
    {
        return [
            self::KATEGORI_KEGIATAN => 'Kegiatan Desa',
            self::KATEGORI_INFRASTRUKTUR => 'Infrastruktur',
            self::KATEGORI_BUDAYA => 'Budaya',
            self::KATEGORI_UMKM => 'UMKM',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
    }

    // Method untuk increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}
