<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
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

        // Seed data dummy lengkap dengan gambar (recommended untuk development)
        $this->command->info('📦 Seeding data dummy lengkap dengan gambar...');
        $this->call([
            DummyDataSeeder::class,
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
                ['Berita', '20 berita (16 published, 4 draft)'],
                ['Potensi Desa', '20 potensi (semua aktif)'],
                ['Galeri', '30 galeri (dengan multi-image)'],
                ['Publikasi', '10 dokumen (APBDes, RPJMDes, RKPDes)'],
            ]
        );
    }
}