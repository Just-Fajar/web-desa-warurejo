<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin default untuk login pertama kali
     * 
     * KREDENSIAL DEFAULT:
     * Email: adminwarurejo@gmail.com
     * Password: admin123
     * 
     * PENTING:
     * 1. WAJIB GANTI PASSWORD setelah login pertama!
     * 2. Ganti email ke email resmi desa
     * 3. Update phone number ke nomor aktif
     * 
     * CARA GANTI PASSWORD:
     * 1. Login ke admin panel
     * 2. Masuk menu Profil
     * 3. Update password dan data lainnya
     * 
     * SECURITY WARNING:
     * Jangan commit kredensial production ke Git!
     * Password ini hanya untuk development/testing.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Desa Warurejo',
            'email' => 'adminwarurejo@gmail.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
        ]);
    }
}