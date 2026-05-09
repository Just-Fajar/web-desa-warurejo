<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Mulai seeding database...');
        $this->command->newLine();

        $this->command->info('📦 Seeding data utama...');
        $this->call([AdminSeeder::class]);
        $this->command->newLine();

        $this->command->info('📦 Seeding data dummy lengkap dengan gambar...');
        $this->call([DummyDataSeeder::class]);
        $this->command->newLine();

        $this->command->info('✅ Database seeding selesai!');
        $this->command->table(
            ['Model', 'Jumlah Data'],
            [
                ['Admin', '1 user'],
                ['Berita', '30 (20 published, 5 draft, 5 scheduled)'],
                ['Galeri', '30 (5 per kategori × 6 kategori)'],
                ['Potensi Desa', '30 (5 per kategori × 6 kategori)'],
                ['Publikasi', '30 (10 per kategori × 3 kategori)'],
                ['Pengaduan', '30 (8 menunggu, 8 diproses, 8 selesai, 6 ditolak)'],
            ]
        );
    }
}
