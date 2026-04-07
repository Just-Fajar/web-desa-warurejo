<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Admin Model
 * 
 * Eloquent model untuk admin authentication
 * Extends Laravel Authenticatable untuk login system
 * 
 * FILLABLE:
 * - name: Nama lengkap admin
 * - email: Email untuk login (unique)
 * - password: Hashed password (bcrypt)
 * - phone: Nomor telepon admin
 * - avatar: Path foto profil di storage/
 * 
 * HIDDEN:
 * - password: Jangan expose di JSON response
 * - remember_token: Token untuk "remember me" feature
 * 
 * RELATIONSHIPS:
 * - berita(): hasMany - Admin bisa punya banyak berita
 * - galeri(): hasMany - Admin bisa upload banyak galeri
 * 
 * ACCESSORS:
 * - avatar_url: Full URL ke avatar (fallback ke default-avatar.png)
 * 
 * AUTHENTICATION:
 * Guard: 'admin' (defined di config/auth.php)
 * Usage:
 * - auth()->guard('admin')->attempt($credentials)
 * - auth()->guard('admin')->user()
 * - auth()->guard('admin')->logout()
 * 
 * DEFAULT CREDENTIALS (WAJIB GANTI!):
 * Email: admin@warurejo.desa.id
 * Password: password123
 * 
 * Seeder: database/seeders/AdminSeeder.php
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function berita()
    {
        return $this->hasMany(Berita::class);
    }

    public function galeri()
    {
        return $this->hasMany(Galeri::class);
    }

    // Accessors
    
    /**
     * Get avatar URL dengan fallback
     * 
     * Return full URL ke storage jika avatar ada
     * Fallback ke default-avatar.png jika avatar null
     * 
     * Usage: $admin->avatar_url
     */
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : asset('images/default-avatar.png');
    }
}