<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    // Status constants
    const STATUS_MENUNGGU = 'Menunggu';
    const STATUS_DIPROSES = 'Diproses';
    const STATUS_SELESAI = 'Selesai';
    const STATUS_DITOLAK = 'Ditolak';

    protected $fillable = [
        'nama_pelapor',
        'nomor_wa',
        'judul',
        'isi',
        'lokasi_kejadian',
        'lampiran',
        'status',
        'alasan_penolakan',
    ];

    /**
     * Relationship: Pengaduan has many balasan
     */
    public function balasan()
    {
        return $this->hasMany(PengaduanBalasan::class);
    }

    /**
     * Accessor: Sensor nama pelapor untuk tampilan publik
     * Contoh: "Budi Santoso" → "Bu** Sa*****"
     * Tampilkan 2 huruf pertama tiap kata, sisanya ganti '*'
     */
    public function getNamaSensorAttribute()
    {
        $words = explode(' ', $this->nama_pelapor);
        $censored = array_map(function ($word) {
            $length = mb_strlen($word);
            if ($length <= 2) {
                return $word; // kata pendek tidak disensor
            }
            $visible = mb_substr($word, 0, 2);
            $hidden = str_repeat('*', $length - 2);
            return $visible . $hidden;
        }, $words);

        return implode(' ', $censored);
    }

    /**
     * Accessor: Get badge styling per status
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => [
                'color' => 'yellow',
                'bg' => 'bg-yellow-50',
                'text' => 'text-yellow-700',
                'border' => 'border-yellow-200',
                'label' => 'Menunggu',
            ],
            self::STATUS_DIPROSES => [
                'color' => 'blue',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'border' => 'border-blue-200',
                'label' => 'Diproses',
            ],
            self::STATUS_SELESAI => [
                'color' => 'green',
                'bg' => 'bg-green-50',
                'text' => 'text-green-700',
                'border' => 'border-green-200',
                'label' => 'Selesai',
            ],
            self::STATUS_DITOLAK => [
                'color' => 'red',
                'bg' => 'bg-red-50',
                'text' => 'text-red-700',
                'border' => 'border-red-200',
                'label' => 'Ditolak',
            ],
            default => [
                'color' => 'gray',
                'bg' => 'bg-gray-50',
                'text' => 'text-gray-700',
                'border' => 'border-gray-200',
                'label' => 'Unknown',
            ],
        };
    }

    /**
     * Accessor: Get URL publik lampiran
     */
    public function getLampiranUrlAttribute()
    {
        return $this->lampiran ? asset('storage/' . $this->lampiran) : null;
    }

    /**
     * Check apakah lampiran berupa gambar
     */
    public function isImage()
    {
        if (!$this->lampiran) return false;
        $ext = strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }

    /**
     * Check apakah lampiran berupa PDF
     */
    public function isPdf()
    {
        if (!$this->lampiran) return false;
        $ext = strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION));
        return $ext === 'pdf';
    }

    /**
     * Get daftar status yang tersedia
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    /**
     * Scope: Urutkan berdasarkan terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
