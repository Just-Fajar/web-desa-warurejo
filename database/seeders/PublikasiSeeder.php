<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publikasi;
use Carbon\Carbon;

class PublikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publikasi = [
            // APBDes
            [
                'judul' => 'Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun 2025',
                'kategori' => 'APBDes',
                'tahun' => 2025,
                'deskripsi' => 'Dokumen APBDes Desa Warurejo Tahun Anggaran 2025 yang mencakup rencana pendapatan dan belanja desa untuk pembangunan dan pelayanan masyarakat.',
                'file_dokumen' => 'publikasi/apbdes-2025.pdf',
                'tanggal_publikasi' => Carbon::now()->subDays(5),
                'status' => 'published',
            ],
            [
                'judul' => 'Anggaran Pendapatan dan Belanja Desa (APBDes) Tahun 2024',
                'kategori' => 'APBDes',
                'tahun' => 2024,
                'deskripsi' => 'Dokumen APBDes Desa Warurejo Tahun Anggaran 2024.',
                'file_dokumen' => 'publikasi/apbdes-2024.pdf',
                'tanggal_publikasi' => Carbon::now()->subMonths(6),
                'status' => 'published',
            ],
            [
                'judul' => 'Laporan Realisasi APBDes Semester 1 Tahun 2025',
                'kategori' => 'APBDes',
                'tahun' => 2025,
                'deskripsi' => 'Laporan realisasi pelaksanaan APBDes semester pertama tahun 2025.',
                'file_dokumen' => 'publikasi/laporan-apbdes-sem1-2025.pdf',
                'tanggal_publikasi' => Carbon::now()->subDays(15),
                'status' => 'published',
            ],
            
            // RPJMDes
            [
                'judul' => 'Rencana Pembangunan Jangka Menengah Desa (RPJMDes) 2024-2029',
                'kategori' => 'RPJMDes',
                'tahun' => 2024,
                'deskripsi' => 'Dokumen perencanaan pembangunan jangka menengah Desa Warurejo periode 2024-2029 yang memuat visi, misi, dan program pembangunan 5 tahun ke depan.',
                'file_dokumen' => 'publikasi/rpjmdes-2024-2029.pdf',
                'tanggal_publikasi' => Carbon::now()->subMonths(3),
                'status' => 'published',
            ],
            [
                'judul' => 'Evaluasi RPJMDes 2019-2024',
                'kategori' => 'RPJMDes',
                'tahun' => 2024,
                'deskripsi' => 'Laporan evaluasi pelaksanaan RPJMDes periode 2019-2024.',
                'file_dokumen' => 'publikasi/evaluasi-rpjmdes-2019-2024.pdf',
                'tanggal_publikasi' => Carbon::now()->subMonths(4),
                'status' => 'published',
            ],
            
            // RKPDes
            [
                'judul' => 'Rencana Kerja Pemerintah Desa (RKPDes) Tahun 2025',
                'kategori' => 'RKPDes',
                'tahun' => 2025,
                'deskripsi' => 'RKPDes tahun 2025 memuat rencana kegiatan pembangunan dan pemberdayaan masyarakat yang akan dilaksanakan dalam satu tahun anggaran.',
                'file_dokumen' => 'publikasi/rkpdes-2025.pdf',
                'tanggal_publikasi' => Carbon::now()->subDays(10),
                'status' => 'published',
            ],
            [
                'judul' => 'Rencana Kerja Pemerintah Desa (RKPDes) Tahun 2024',
                'kategori' => 'RKPDes',
                'tahun' => 2024,
                'deskripsi' => 'RKPDes tahun 2024 Desa Warurejo.',
                'file_dokumen' => 'publikasi/rkpdes-2024.pdf',
                'tanggal_publikasi' => Carbon::now()->subYear(),
                'status' => 'published',
            ],
            [
                'judul' => 'Laporan Pelaksanaan RKPDes Tahun 2024',
                'kategori' => 'RKPDes',
                'tahun' => 2024,
                'deskripsi' => 'Laporan pelaksanaan dan capaian program RKPDes tahun 2024.',
                'file_dokumen' => 'publikasi/laporan-rkpdes-2024.pdf',
                'tanggal_publikasi' => Carbon::now()->subMonths(2),
                'status' => 'published',
            ],
        ];

        foreach ($publikasi as $data) {
            Publikasi::create($data);
        }
    }
}
