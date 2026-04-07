<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * CARA PAKAI:
     * 1. Fresh install: php artisan migrate:fresh --seed
     * 2. Re-seed only: php artisan db:seed
     * 3. Specific seeder: php artisan db:seed --class=BeritaSeeder
     * 
     * STRUKTUR SEEDING:
     * 1. Data utama (WAJIB):
     *    - AdminSeeder: Admin default untuk login pertama
     * 
     * 2. Data dummy (OPTIONAL - untuk development/testing):
     *    - BeritaSeeder: 20 sample berita
     *    - PotensiSeeder: 15 sample potensi desa
     *    - GaleriSeeder: 30 sample galeri (foto + video)
     * 
     * CATATAN PRODUCTION:
     * Comment out data dummy seeders di production!
     * Hanya jalankan AdminSeeder untuk create admin pertama.
     */
    public function run(): void
    {
        $this->command->info('🌱 Mulai seeding database...');
        $this->command->newLine();

        // Seed data utama (wajib)
        $this->command->info('📦 Seeding data utama...');
        $this->call([
            AdminSeeder::class,
        ]);
        $this->command->newLine();

        // Seed data dummy (optional tapi recommended untuk development)
        $this->command->info('📦 Seeding data dummy untuk development...');
        $this->call([
            BeritaSeeder::class,
            PotensiSeeder::class,
            GaleriSeeder::class,
        ]);
        $this->command->newLine();

        $this->command->info('✅ Database seeding selesai!');
        $this->command->info('🎉 Semua data berhasil di-seed ke database.');
        $this->command->newLine();
        
        // Summary
        $this->command->table(
            ['Model', 'Jumlah Data'],
            [
                ['Admin', '1 user'],
                ['Berita', '20 berita'],
                ['Potensi Desa', '15 potensi'],
                ['Galeri', '30 item (25 foto + 5 video)'],
            ]
        );
    }
}