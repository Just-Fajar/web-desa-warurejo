<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PotensiDesa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PotensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan folder storage ada
        if (!Storage::disk('public')->exists('potensi')) {
            Storage::disk('public')->makeDirectory('potensi');
        }

        $this->command->info('Membuat 15 potensi desa dummy...');

        $potensiData = [
            // Pertanian (5)
            [
                'nama' => 'Pertanian Padi Organik',
                'kategori' => 'pertanian',
                'deskripsi' => '<p><strong>Pertanian padi organik</strong> menjadi salah satu unggulan Desa Warurejo dengan luas lahan mencapai 150 hektar. Petani setempat telah menerapkan sistem pertanian organik yang ramah lingkungan tanpa menggunakan pestisida kimia.</p><p>Hasil panen padi organik memiliki kualitas premium dan diminati oleh konsumen yang peduli kesehatan. Pemerintah desa terus memberikan pendampingan dan pelatihan untuk meningkatkan produktivitas petani.</p><p>Kerjasama dengan berbagai pihak seperti dinas pertanian dan lembaga sertifikasi organik terus diperkuat untuk membuka akses pasar yang lebih luas.</p>',
                'lokasi' => 'Dusun Selatan, RT 01-05',
                'kontak' => '081234567890',
                'gambar' => 'potensi/padi-organik.jpg',
            ],
            [
                'nama' => 'Perkebunan Kopi Arabika',
                'kategori' => 'pertanian',
                'deskripsi' => '<p>Perkebunan <strong>kopi arabika</strong> berada di dataran tinggi dengan ketinggian ideal untuk menghasilkan biji kopi berkualitas. Aroma dan cita rasa khas yang dihasilkan menjadikan kopi Warurejo mulai dikenal di pasar nasional.</p><p>Kelompok tani kopi telah memiliki mesin pengolahan modern untuk proses pasca panen. Branding dan kemasan juga terus diperbaiki untuk meningkatkan nilai jual.</p><p>Wisata agro kopi juga mulai dikembangkan untuk menarik wisatawan yang ingin belajar tentang proses produksi kopi dari hulu ke hilir.</p>',
                'lokasi' => 'Dusun Utara, RT 08-10',
                'kontak' => '081234567891',
                'gambar' => 'potensi/kopi-arabika.jpg',
            ],
            [
                'nama' => 'Budidaya Sayuran Hidroponik',
                'kategori' => 'pertanian',
                'deskripsi' => '<p><strong>Hidroponik</strong> menjadi inovasi pertanian modern yang dikembangkan di Desa Warurejo. Sistem tanam tanpa tanah ini menghasilkan sayuran berkualitas tinggi dengan produktivitas optimal.</p><p>Berbagai jenis sayuran seperti selada, kangkung, dan bayam dibudidayakan menggunakan metode hidroponik NFT (Nutrient Film Technique) dan DFT (Deep Flow Technique).</p><p>Hasil panen dipasarkan ke restoran, hotel, dan supermarket dengan harga yang menguntungkan. Pelatihan hidroponik juga rutin diadakan untuk masyarakat.</p>',
                'lokasi' => 'Dusun Tengah, RT 06-07',
                'kontak' => '081234567892',
                'gambar' => 'potensi/hidroponik.jpg',
            ],
            [
                'nama' => 'Peternakan Ayam Kampung',
                'kategori' => 'peternakan',
                'deskripsi' => '<p>Peternakan <strong>ayam kampung</strong> dikelola secara modern dengan sistem kandang battery dan kandang postal. Populasi ayam mencapai 10.000 ekor dengan sistem pemeliharaan yang higienis.</p><p>Produk yang dihasilkan meliputi daging ayam kampung dan telur ayam kampung yang memiliki kandungan gizi tinggi. Permintaan pasar terus meningkat setiap tahunnya.</p><p>Peternak mendapat pendampingan dari dinas peternakan terkait manajemen kesehatan ternak dan pemasaran hasil produksi.</p>',
                'lokasi' => 'Dusun Barat, RT 11-12',
                'kontak' => '081234567893',
                'gambar' => 'potensi/ayam-kampung.jpg',
            ],
            [
                'nama' => 'Budidaya Ikan Air Tawar',
                'kategori' => 'perikanan',
                'deskripsi' => '<p><strong>Budidaya ikan air tawar</strong> seperti lele, nila, dan gurame menjadi usaha yang menjanjikan. Kolam-kolam terpal dan kolam tanah tersebar di berbagai dusun dengan total luas mencapai 20 hektar.</p><p>Sistem budidaya intensif dengan pemberian pakan berkualitas menghasilkan ikan berukuran konsumsi dalam waktu relatif singkat. Kualitas air dijaga dengan baik untuk pertumbuhan optimal.</p><p>Pemasaran dilakukan ke pasar tradisional, rumah makan, dan pengepul dengan omzet yang terus meningkat.</p>',
                'lokasi' => 'Dusun Timur, RT 13-15',
                'kontak' => '081234567894',
                'gambar' => 'potensi/ikan-air-tawar.jpg',
            ],

            // UMKM (5)
            [
                'nama' => 'Kerajinan Bambu',
                'kategori' => 'kerajinan',
                'deskripsi' => '<p><strong>Kerajinan bambu</strong> menjadi warisan turun temurun yang terus dilestarikan. Produk yang dihasilkan meliputi furniture, anyaman, dan souvenir dengan desain modern yang tetap mempertahankan ciri khas tradisional.</p><p>Pengrajin bambu telah mendapat pelatihan desain produk dan manajemen usaha untuk meningkatkan daya saing. Produk dipasarkan melalui online dan offline dengan jangkauan hingga luar daerah.</p><p>Showroom kerajinan bambu juga dibuka untuk memudahkan konsumen melihat langsung beragam produk yang tersedia.</p>',
                'lokasi' => 'Dusun Selatan, RT 02',
                'kontak' => '081234567895',
                'gambar' => 'potensi/kerajinan-bambu.jpg',
            ],
            [
                'nama' => 'Produksi Makanan Ringan Tradisional',
                'kategori' => 'umkm',
                'deskripsi' => '<p><strong>Makanan ringan tradisional</strong> seperti keripik singkong, keripik tempe, dan rempeyek menjadi produk unggulan UMKM desa. Cita rasa autentik dengan resep turun temurun menjadi daya tarik utama.</p><p>Proses produksi dilakukan secara higienis dengan kemasan menarik dan berlabel halal. Pemasaran dilakukan melalui toko oleh-oleh, marketplace online, dan reseller.</p><p>Omzet bulanan mencapai puluhan juta rupiah dengan tenaga kerja yang terus bertambah seiring meningkatnya permintaan pasar.</p>',
                'lokasi' => 'Dusun Tengah, RT 06',
                'kontak' => '081234567896',
                'gambar' => 'potensi/makanan-ringan.jpg',
            ],
            [
                'nama' => 'Konveksi Pakaian',
                'kategori' => 'umkm',
                'deskripsi' => '<p>Usaha <strong>konveksi pakaian</strong> melayani pembuatan seragam sekolah, seragam kantor, kaos sablon, dan pakaian custom. Mesin jahit modern dan tenaga kerja terampil siap mengerjakan pesanan dalam jumlah besar.</p><p>Kualitas jahitan dan ketepatan waktu menjadi komitmen utama dalam melayani pelanggan. Harga kompetitif dengan kualitas yang tidak kalah dari konveksi besar.</p><p>Kerjasama dengan sekolah, perusahaan, dan instansi pemerintah terus dibina untuk sustainability usaha.</p>',
                'lokasi' => 'Dusun Utara, RT 09',
                'kontak' => '081234567897',
                'gambar' => 'potensi/konveksi.jpg',
            ],
            [
                'nama' => 'Produksi Jamu Tradisional',
                'kategori' => 'umkm',
                'deskripsi' => '<p><strong>Jamu tradisional</strong> dengan bahan alami pilihan diproduksi sesuai standar kesehatan. Berbagai varian seperti jamu kunyit asam, beras kencur, dan temulawak tersedia dalam bentuk serbuk dan siap minum.</p><p>Legalitas PIRT dan sertifikasi halal telah dikantongi untuk menjamin keamanan produk. Branding modern dengan kemasan menarik membuat jamu tradisional diminati generasi muda.</p><p>Distribusi dilakukan ke toko herbal, apotek, dan platform e-commerce dengan respons pasar yang sangat positif.</p>',
                'lokasi' => 'Dusun Barat, RT 11',
                'kontak' => '081234567898',
                'gambar' => 'potensi/jamu-tradisional.jpg',
            ],
            [
                'nama' => 'Industri Mebel Kayu',
                'kategori' => 'umkm',
                'deskripsi' => '<p>Industri <strong>mebel kayu</strong> menghasilkan furniture berkualitas tinggi dengan desain minimalis hingga klasik. Kayu jati, mahoni, dan trembesi menjadi bahan utama yang diolah oleh tangan-tangan terampil pengrajin lokal.</p><p>Produk meliputi meja, kursi, lemari, tempat tidur, dan furniture custom sesuai permintaan konsumen. Proses finishing menggunakan cat dan plitur berkualitas untuk hasil maksimal.</p><p>Pesanan datang dari dalam dan luar daerah dengan sistem pre-order untuk memastikan kualitas setiap produk yang dihasilkan.</p>',
                'lokasi' => 'Dusun Timur, RT 14',
                'kontak' => '081234567899',
                'gambar' => 'potensi/mebel-kayu.jpg',
            ],

            // Pariwisata (3)
            [
                'nama' => 'Air Terjun Sumber Sari',
                'kategori' => 'wisata',
                'deskripsi' => '<p><strong>Air Terjun Sumber Sari</strong> merupakan destinasi wisata alam yang menawarkan keindahan air terjun setinggi 25 meter. Suasana sejuk dengan pepohonan rindang menjadikan tempat ini ideal untuk refreshing bersama keluarga.</p><p>Fasilitas yang tersedia meliputi area parkir, gazebo, warung makan, dan toilet. Jalur trekking yang aman dan terawat memudahkan pengunjung mencapai lokasi air terjun.</p><p>Pemerintah desa terus mengembangkan fasilitas dan promosi untuk meningkatkan kunjungan wisatawan. Spot foto instagramable juga disediakan untuk kepuasan pengunjung.</p>',
                'lokasi' => 'Dusun Utara, RT 10',
                'kontak' => '081234567800',
                'gambar' => 'potensi/air-terjun.jpg',
            ],
            [
                'nama' => 'Kampung Wisata Budaya',
                'kategori' => 'wisata',
                'deskripsi' => '<p><strong>Kampung Wisata Budaya</strong> menawarkan pengalaman wisata edukatif tentang kehidupan dan budaya masyarakat desa. Wisatawan dapat belajar membatik, membuat kerajinan tangan, dan memasak makanan tradisional.</p><p>Paket homestay juga tersedia bagi wisatawan yang ingin merasakan kehidupan desa secara langsung. Warga dengan ramah menyambut dan berbagi cerita tentang tradisi lokal.</p><p>Event budaya seperti pertunjukan wayang kulit dan tari tradisional rutin digelar untuk melestarikan warisan budaya sekaligus menarik wisatawan.</p>',
                'lokasi' => 'Dusun Tengah, RT 06-07',
                'kontak' => '081234567801',
                'gambar' => 'potensi/kampung-wisata.jpg',
            ],
            [
                'nama' => 'Agrowisata Kebun Buah',
                'kategori' => 'wisata',
                'deskripsi' => '<p><strong>Agrowisata Kebun Buah</strong> menghadirkan konsep wisata petik buah langsung dari pohonnya. Berbagai buah tropis seperti durian, mangga, rambutan, dan manggis tersedia sesuai musim.</p><p>Pengunjung dapat berjalan-jalan di kebun sambil menikmati udara segar dan memetik buah favorit. Harga terjangkau dengan sistem bayar per kilogram hasil petikan.</p><p>Area bermain anak, kolam renang alami, dan resto dengan menu berbahan lokal melengkapi fasilitas agrowisata untuk kenyamanan keluarga.</p>',
                'lokasi' => 'Dusun Selatan, RT 03-04',
                'kontak' => '081234567802',
                'gambar' => 'potensi/agrowisata.jpg',
            ],

            // Lainnya (2)
            [
                'nama' => 'Pengolahan Hasil Panen',
                'kategori' => 'lainnya',
                'deskripsi' => '<p>Unit <strong>pengolahan hasil panen</strong> dilengkapi mesin modern untuk mengolah hasil pertanian menjadi produk bernilai tambah. Proses meliputi sortir, grading, pengemasan, hingga labeling.</p><p>Kerjasama dengan kelompok tani memastikan pasokan bahan baku yang kontinu. Produk olahan dipasarkan ke pasar modern dan eksportir dengan standar kualitas tinggi.</p><p>Pelatihan pengelolaan pasca panen rutin diadakan untuk meningkatkan skill SDM dan mengurangi kerugian akibat kerusakan hasil panen.</p>',
                'lokasi' => 'Dusun Selatan, RT 05',
                'kontak' => '081234567803',
                'gambar' => 'potensi/pengolahan-panen.jpg',
            ],
            [
                'nama' => 'Produksi Pupuk Organik',
                'kategori' => 'lainnya',
                'deskripsi' => '<p>Pabrik <strong>pupuk organik</strong> mengolah limbah ternak dan sampah organik menjadi pupuk berkualitas. Proses produksi menggunakan teknologi fermentasi untuk menghasilkan pupuk dengan kandungan hara lengkap.</p><p>Produk dikemas dalam berbagai ukuran dari 5 kg hingga 50 kg sesuai kebutuhan konsumen. Sertifikasi organik dari lembaga resmi menjamin kualitas produk.</p><p>Pemasaran dilakukan ke toko pertanian, petani organik, dan perkebunan dengan harga bersaing. Program CSR juga memberikan pupuk gratis untuk petani kecil secara berkala.</p>',
                'lokasi' => 'Dusun Barat, RT 12',
                'kontak' => '081234567804',
                'gambar' => 'potensi/pupuk-organik.jpg',
            ],
        ];

        foreach ($potensiData as $index => $data) {
            PotensiDesa::create([
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'kategori' => $data['kategori'],
                'deskripsi' => $data['deskripsi'],
                'gambar' => $data['gambar'],
                'lokasi' => $data['lokasi'],
                'kontak' => $data['kontak'],
                'is_active' => true,
                'urutan' => $index + 1,
            ]);

            $this->command->info('✓ Potensi ' . ($index + 1) . ': ' . $data['nama']);
        }

        $this->command->info('✅ Berhasil membuat 15 potensi desa dummy!');
    }
}
