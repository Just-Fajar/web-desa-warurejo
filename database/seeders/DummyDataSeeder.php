<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\Publikasi;
use App\Models\Pengaduan;
use App\Models\DailyVisitorStat;

class DummyDataSeeder extends Seeder
{
    /**
     * Seeder utama: memanggil semua seeder modul.
     * Total: 30 Berita + 30 Galeri + 30 Potensi + 30 Publikasi + 30 Pengaduan + Visitor 1 tahun
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai pembuatan data dummy lengkap dengan gambar...');
        $this->command->newLine();

        $this->call([
            BeritaSeeder::class,
            GaleriSeeder::class,
            PotensiSeeder::class,
            PublikasiSeeder::class,
            PengaduanSeeder::class,
            VisitorDataSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Semua data dummy berhasil dibuat!');
        $this->command->table(
            ['Model', 'Jumlah'],
            [
                ['Berita', Berita::count()],
                ['Galeri', Galeri::count()],
                ['Potensi Desa', PotensiDesa::count()],
                ['Publikasi', Publikasi::count()],
                ['Pengaduan', Pengaduan::count()],
                ['Visitor Stats', DailyVisitorStat::count() . ' hari'],
            ]
        );
    }
}
