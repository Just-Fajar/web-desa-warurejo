<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PotensiDesa extends Model
{
    use HasFactory;

    protected $table = 'potensi_desa';

    protected $fillable = [
        'nama',
        'slug',
        'kategori',
        'deskripsi',
        'gambar',
        'lokasi',
        'kontak',
        'whatsapp',
        'is_active',
        'urutan',
        'views',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Accessors
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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

    // Mutators
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Constants for categories
    const KATEGORI_PERTANIAN = 'pertanian';
    const KATEGORI_PETERNAKAN = 'peternakan';
    const KATEGORI_PERIKANAN = 'perikanan';
    const KATEGORI_UMKM = 'umkm';
    const KATEGORI_WISATA = 'wisata';
    const KATEGORI_KERAJINAN = 'kerajinan';
    const KATEGORI_LAINNYA = 'lainnya';

    public static function getKategoriList()
    {
        return [
            self::KATEGORI_PERTANIAN => 'Pertanian',
            self::KATEGORI_PETERNAKAN => 'Peternakan',
            self::KATEGORI_PERIKANAN => 'Perikanan',
            self::KATEGORI_UMKM => 'UMKM',
            self::KATEGORI_WISATA => 'Wisata',
            self::KATEGORI_KERAJINAN => 'Kerajinan',
            self::KATEGORI_LAINNYA => 'Lainnya',
        ];
    }

    // Boot method for auto-generating slug
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
    }

    // Method untuk increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}
