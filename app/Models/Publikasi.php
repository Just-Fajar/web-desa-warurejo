<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'tahun',
        'deskripsi',
        'file_dokumen',
        'thumbnail',
        'tanggal_publikasi',
        'status',
        'jumlah_download',
        'views',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'date',
        'tahun' => 'integer',
        'jumlah_download' => 'integer',
    ];

    /**
     * Scope untuk publikasi yang sudah published
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
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
        return asset('storage/' . $this->file_dokumen);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/default-document.jpg');
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
