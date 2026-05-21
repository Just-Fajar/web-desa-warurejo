<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotensiDesaFoto extends Model
{
    use HasFactory;

    protected $table = 'potensi_desa_foto';

    protected $fillable = [
        'potensi_desa_id',
        'foto',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke PotensiDesa (belongsTo)
     */
    public function potensiDesa()
    {
        return $this->belongsTo(PotensiDesa::class);
    }

    /**
     * Accessor: URL lengkap foto
     */
    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? asset('storage/'.$this->foto)
            : null;
    }
}
