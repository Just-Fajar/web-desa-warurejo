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
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Relationship: Balasan belongs to Pengaduan
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
