<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'admin_id',
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'gambar_utama',
        'status',
        'views',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Relationship: Berita belongs to Admin (author)
     * Untuk tau siapa yang buat berita ini
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Accessor: Get full URL gambar utama
     * Auto return default image jika gambar_utama null
     * Usage: $berita->gambar_utama_url
     */
    public function getGambarUtamaUrlAttribute()
    {
        return $this->gambar_utama 
            ? asset('storage/' . $this->gambar_utama) 
            : asset('images/logo-web-desa.jpg');
    }

    /**
     * Accessor: Get excerpt/ringkasan berita
     * Jika ringkasan ada, return ringkasan
     * Jika tidak, auto generate dari 150 karakter pertama konten
     * Usage: $berita->excerpt
     */
    public function getExcerptAttribute()
    {
        return $this->ringkasan 
            ? $this->ringkasan 
            : Str::limit(strip_tags($this->konten), 150);
    }

    /**
     * Accessor: Get tanggal formatted (dd MMMM yyyy)
     * Prioritas: published_at, fallback ke created_at
     * Usage: $berita->formatted_date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at 
            ? $this->published_at->format('d F Y') 
            : $this->created_at->format('d F Y');
    }

    /**
     * Scope: Get hanya berita published
     * - Status = 'published'
     * - published_at tidak null
     * - published_at <= now (tidak tampilkan scheduled post)
     * Usage: Berita::published()->get()
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Get hanya berita draft
     * Usage: Berita::draft()->get()
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Sort by published_at descending, fallback ke created_at
     * Usage: Berita::latest()->get()
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Mutator: Auto-generate slug saat set judul
     * Slug akan di-generate otomatis dari judul
     */
    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Boot method: Auto-generate unique slug saat create
     * Jika slug kosong, generate dari judul
     * Jika slug sudah ada, tambahkan suffix -1, -2, dst
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
            
            // Ensure unique slug
            $originalSlug = $berita->slug;
            $count = 1;
            while (static::where('slug', $berita->slug)->exists()) {
                $berita->slug = $originalSlug . '-' . $count;
                $count++;
            }
        });
    }
}
