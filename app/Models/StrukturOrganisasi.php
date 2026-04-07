<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'deskripsi',
        'urutan',
        'level',
        'atasan_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Constants for levels
    const LEVEL_KEPALA = 'kepala';
    const LEVEL_SEKRETARIS = 'sekretaris';
    const LEVEL_KAUR = 'kaur';
    const LEVEL_STAFF_KAUR = 'staff_kaur';
    const LEVEL_KASI = 'kasi';
    const LEVEL_STAFF_KASI = 'staff_kasi';

    /**
     * Get all available levels
     */
    public static function getLevels(): array
    {
        return [
            self::LEVEL_KEPALA => 'Kepala Desa',
            self::LEVEL_SEKRETARIS => 'Sekretaris Desa',
            self::LEVEL_KAUR => 'Kepala Urusan',
            self::LEVEL_STAFF_KAUR => 'Staff Kaur',
            self::LEVEL_KASI => 'Kepala Seksi',
            self::LEVEL_STAFF_KASI => 'Staff Kasi',
        ];
    }

    // Relationships
    public function atasan()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'atasan_id');
    }

    // Accessors
    public function getFotoUrlAttribute()
    {
        return $this->foto 
            ? asset('storage/' . $this->foto) 
            : asset('images/default-avatar.png');
    }

    public function getLevelLabelAttribute()
    {
        return self::getLevels()[$this->level] ?? $this->level;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('level')->orderBy('urutan')->orderBy('nama');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeKepala($query)
    {
        return $query->where('level', self::LEVEL_KEPALA);
    }

    public function scopeSekretaris($query)
    {
        return $query->where('level', self::LEVEL_SEKRETARIS);
    }

    public function scopeKaur($query)
    {
        return $query->where('level', self::LEVEL_KAUR);
    }

    public function scopeStaffKaur($query)
    {
        return $query->where('level', self::LEVEL_STAFF_KAUR);
    }

    public function scopeKasi($query)
    {
        return $query->where('level', self::LEVEL_KASI);
    }

    public function scopeStaffKasi($query)
    {
        return $query->where('level', self::LEVEL_STAFF_KASI);
    }
}
