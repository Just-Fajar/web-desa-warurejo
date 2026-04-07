<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Galeri Model
 * 
 * Model untuk photo gallery dengan multi-image support
 * 
 * FILLABLE:
 * - admin_id: Foreign key ke admins table
 * - judul: Judul galeri/album
 * - deskripsi: Deskripsi galeri
 * - gambar: Thumbnail galeri (legacy, prefer images relationship)
 * - kategori: Kategori (kegiatan/infrastruktur/budaya/umum)
 * - tanggal: Tanggal event/foto diambil
 * - is_active: Published status (true/false)
 * - views: Counter jumlah view
 * 
 * RELATIONSHIPS:
 * - admin(): belongsTo - Galeri dibuat oleh admin
 * - images(): hasMany GaleriImage - Multi foto dalam satu galeri
 * 
 * ACCESSORS:
 * - gambar_url: Full URL ke gambar (fallback dari images atau gambar field)
 * - formatted_date: Format tanggal Indonesia (01 Januari 2024)
 * 
 * SCOPES:
 * - active(): Filter is_active = true
 * - published(): Filter active + tanggal <= now
 * - byKategori($kategori): Filter by kategori
 * - latest(): Order by tanggal DESC
 * 
 * CONSTANTS:
 * KATEGORI_KEGIATAN, KATEGORI_INFRASTRUKTUR, KATEGORI_BUDAYA, KATEGORI_UMUM
 * 
 * METHODS:
 * - getKategoriList(): Array kategori options untuk dropdown
 * - incrementViews(): Increment view counter
 * 
 * MULTI-IMAGE FEATURE:
 * Galeri punya banyak GaleriImage (hasMany relationship)
 * Images di-sort by 'urutan' field
 * Implementasi di: GaleriImage model, GaleriController multi-upload
 */
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
            ->where(function($q) {
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
    const KATEGORI_UMUM = 'umum';

    public static function getKategoriList()
    {
        return [
            self::KATEGORI_KEGIATAN => 'Kegiatan',
            self::KATEGORI_INFRASTRUKTUR => 'Infrastruktur',
            self::KATEGORI_BUDAYA => 'Budaya',
            self::KATEGORI_UMUM => 'Umum',
        ];
    }

    // Method untuk increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}
