<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaduanBalasan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan_balasan';

    protected $fillable = [
        'pengaduan_id',
        'isi',
        'is_admin',
        'lampiran',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Accessor: Get URL publik lampiran balasan
     */
    public function getLampiranUrlAttribute()
    {
        return $this->lampiran ? asset('storage/'.$this->lampiran) : null;
    }

    /**
     * Check apakah lampiran berupa gambar
     */
    public function isImage()
    {
        if (! $this->lampiran) {
            return false;
        }
        $ext = strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }

    /**
     * Relationship: Balasan belongs to Pengaduan
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
