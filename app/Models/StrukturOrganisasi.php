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
        'periode_jabatan',
        'whatsapp',
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

    const LEVEL_KADUS = 'kadus';

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
            self::LEVEL_KADUS => 'Kepala Dusun',
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
            ? asset('storage/'.$this->foto)
            : asset('images/default-avatar.png');
    }

    /**
     * Get a level-specific avatar URL. If foto is null, generates a color-coded fallback from ui-avatars.com
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/'.$this->foto);
        }

        $colors = [
            self::LEVEL_KEPALA => ['color' => '4F46E5', 'bg' => 'EEF2FF'],     // Indigo
            self::LEVEL_SEKRETARIS => ['color' => '059669', 'bg' => 'ECFDF5'], // Emerald
            self::LEVEL_KAUR => ['color' => 'D97706', 'bg' => 'FEF3C7'],       // Amber
            self::LEVEL_KASI => ['color' => '2563EB', 'bg' => 'DBEAFE'],       // Blue
            self::LEVEL_STAFF_KAUR => ['color' => 'EA580C', 'bg' => 'FFEDD5'], // Orange
            self::LEVEL_STAFF_KASI => ['color' => '0D9488', 'bg' => 'E6FFFA'], // Teal
            self::LEVEL_KADUS => ['color' => '8B5CF6', 'bg' => 'F5F3FF'],      // Purple
        ];

        $theme = $colors[$this->level] ?? ['color' => '4B5563', 'bg' => 'F3F4F6']; // Gray fallback

        return "https://ui-avatars.com/api/?name=" . urlencode($this->nama ?? 'NN') .
               "&color=" . $theme['color'] .
               "&background=" . $theme['bg'] .
               "&size=512";
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

    public function scopeKadus($query)
    {
        return $query->where('level', self::LEVEL_KADUS);
    }
}
