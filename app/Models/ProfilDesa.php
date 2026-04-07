<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    use HasFactory;

    protected $table = 'profil_desa';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'email',
        'telepon',
        'alamat_lengkap',
        'visi',
        'misi',
        'sejarah',
        'logo',
        'gambar_kantor',
        'latitude',
        'longitude',
        'luas_wilayah',
        'jumlah_penduduk',
        'jumlah_kk',
        'batas_utara',
        'batas_selatan',
        'batas_timur',
        'batas_barat',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'luas_wilayah' => 'decimal:2',
        'jumlah_penduduk' => 'integer',
        'jumlah_kk' => 'integer',
    ];

    // Singleton pattern - hanya boleh ada 1 record
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = self::first();

            // Jika belum ada record, buat default otomatis
            if (self::$instance === null) {
                self::$instance = self::create([
                    'nama_desa' => 'Desa Warurejo',
                    'kecamatan' => 'Balerejo',
                    'kabupaten' => 'Madiun',
                    'provinsi' => 'Jawa Timur',
                    'kode_pos' => '63172',
                    'alamat_lengkap' => 'Jl. Raya Balerejo No.1, Warurejo, Kecamatan Balerejo, Kabupaten Madiun, Jawa Timur',
                    'jumlah_penduduk' => 0,
                    'luas_wilayah' => 0,
                ]);
            }
        }

        return self::$instance;
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/default-logo.png');
    }

    public function getGambarKantorUrlAttribute()
    {
        return $this->gambar_kantor 
            ? asset('storage/' . $this->gambar_kantor) 
            : asset('images/default-kantor.jpg');
    }

    public function getAlamatLengkapFormatAttribute()
    {
        return "{$this->alamat_lengkap}, {$this->kecamatan}, {$this->kabupaten}, {$this->provinsi} {$this->kode_pos}";
    }

    public function getMisiArrayAttribute()
    {
        if (empty($this->misi)) {
            return [];
        }

        // Split per baris
        $misi = preg_split('/\r\n|\r|\n/', $this->misi);
        return array_filter(array_map('trim', $misi));
    }

    // Prevent multiple records
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (self::count() > 0) {
                throw new \Exception('Hanya boleh ada satu profil desa. Silakan update profil yang sudah ada.');
            }
        });
    }

    // Disable delete
    public function delete()
    {
        throw new \Exception('Profil desa tidak dapat dihapus. Silakan update data jika diperlukan.');
    }
}
