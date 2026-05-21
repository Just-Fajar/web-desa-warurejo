<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed admin default untuk login pertama kali
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin Desa',
            'email' => 'adminwarurejo@gmail.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
        ]);
    }
}
