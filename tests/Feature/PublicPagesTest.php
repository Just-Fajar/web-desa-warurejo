<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\ProfilDesa;
use App\Models\Publikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register MySQL functions for SQLite compatibility
        if (DB::getDriverName() === 'sqlite') {
            $pdo = DB::connection()->getPdo();
            if (method_exists($pdo, 'sqliteCreateFunction')) {
                call_user_func([$pdo, 'sqliteCreateFunction'], 'YEAR', function ($date) {
                    return $date ? date('Y', strtotime($date)) : null;
                }, 1);
                call_user_func([$pdo, 'sqliteCreateFunction'], 'MONTH', function ($date) {
                    return $date ? date('m', strtotime($date)) : null;
                }, 1);
            }
        }
    }

    // ==================== HOMEPAGE ====================

    public function test_homepage_loads_successfully(): void
    {
        ProfilDesa::factory()->warurejo()->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('public.home');
        $response->assertSee('Warurejo');
    }

    // ==================== BERITA ====================

    public function test_berita_index_page_loads(): void
    {
        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.index');
    }

    public function test_berita_index_displays_published_news_only(): void
    {
        $published = Berita::factory()->published()->count(3)->create();
        $draft = Berita::factory()->draft()->count(2)->create();

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);

        foreach ($published as $berita) {
            $response->assertSee($berita->judul);
        }

        foreach ($draft as $berita) {
            $response->assertDontSee($berita->judul);
        }
    }

    public function test_berita_detail_page_loads(): void
    {
        $berita = Berita::factory()->published()->create();

        $response = $this->get(route('berita.show', $berita->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.show');
        $response->assertSee($berita->judul);
    }

    public function test_berita_detail_increments_views(): void
    {
        $berita = Berita::factory()->published()->create(['views' => 10]);

        $this->get(route('berita.show', $berita->slug));

        $this->assertEquals(11, $berita->fresh()->views);
    }

    public function test_berita_search_returns_matching_results(): void
    {
        Berita::factory()->published()->create([
            'judul' => 'Pembangunan Jalan Desa',
        ]);

        Berita::factory()->published()->create([
            'judul' => 'Kegiatan Posyandu',
        ]);

        $response = $this->get(route('berita.index', ['search' => 'Pembangunan']));

        $response->assertStatus(200);
        $response->assertSee('Pembangunan Jalan Desa');
        $response->assertDontSee('Kegiatan Posyandu');
    }

    // ==================== POTENSI ====================

    public function test_potensi_index_page_loads(): void
    {
        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.index');
    }

    public function test_potensi_index_displays_active_items_only(): void
    {
        $active = PotensiDesa::factory()->count(3)->create();
        $inactive = PotensiDesa::factory()->inactive()->count(2)->create();

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);

        foreach ($active as $potensi) {
            $response->assertSee($potensi->nama);
        }

        foreach ($inactive as $potensi) {
            $response->assertDontSee($potensi->nama);
        }
    }

    public function test_potensi_index_page_filters(): void
    {
        $p1 = PotensiDesa::factory()->create([
            'nama' => 'Potensi Wisata Indah',
            'kategori' => 'wisata',
            'published_at' => '2026-05-10 10:00:00',
            'created_at' => '2026-05-10 10:00:00',
            'views' => 10,
        ]);
        $p2 = PotensiDesa::factory()->create([
            'nama' => 'Potensi Kuliner Lezat',
            'kategori' => 'umkm',
            'published_at' => '2026-05-20 10:00:00',
            'created_at' => '2026-05-20 10:00:00',
            'views' => 20,
        ]);

        // Search filter
        $response = $this->get(route('potensi.index', ['search' => 'Wisata']));
        $response->assertSee('Potensi Wisata Indah');
        $response->assertDontSee('Potensi Kuliner Lezat');

        // Kategori filter
        $response = $this->get(route('potensi.index', ['kategori' => 'umkm']));
        $response->assertSee('Potensi Kuliner Lezat');
        $response->assertDontSee('Potensi Wisata Indah');

        // Date range filter
        $response = $this->get(route('potensi.index', ['date_from' => '2026-05-15', 'date_to' => '2026-05-25']));
        $response->assertSee('Potensi Kuliner Lezat');
        $response->assertDontSee('Potensi Wisata Indah');

        // Sorting: popular (most viewed first)
        $response = $this->get(route('potensi.index', ['sort' => 'popular']));
        $response->assertStatus(200);

        // Sorting: oldest
        $response = $this->get(route('potensi.index', ['sort' => 'oldest']));
        $response->assertStatus(200);
    }

    public function test_potensi_detail_page_loads(): void
    {
        $potensi = PotensiDesa::factory()->create();

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.show');
        $response->assertSee($potensi->nama);
    }

    // ==================== GALERI ====================

    public function test_galeri_index_page_loads(): void
    {
        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.galeri.index');
    }

    public function test_galeri_index_page_filters(): void
    {
        $admin = \App\Models\Admin::factory()->create();
        $g1 = Galeri::factory()->active()->create([
            'admin_id' => $admin->id,
            'judul' => 'Kegiatan Bersih Desa',
            'kategori' => Galeri::KATEGORI_KEGIATAN,
            'tanggal' => '2026-05-10',
            'views' => 10,
        ]);
        $g2 = Galeri::factory()->active()->create([
            'admin_id' => $admin->id,
            'judul' => 'Pembangunan Balai RT',
            'kategori' => Galeri::KATEGORI_PEMBANGUNAN,
            'tanggal' => '2026-05-20',
            'views' => 20,
        ]);

        // Search filter
        $response = $this->get(route('galeri.index', ['search' => 'Bersih']));
        $response->assertSee('Kegiatan Bersih Desa');
        $response->assertDontSee('Pembangunan Balai RT');

        // Kategori filter
        $response = $this->get(route('galeri.index', ['kategori' => Galeri::KATEGORI_PEMBANGUNAN]));
        $response->assertSee('Pembangunan Balai RT');
        $response->assertDontSee('Kegiatan Bersih Desa');

        // Date range filter
        $response = $this->get(route('galeri.index', ['date_from' => '2026-05-15', 'date_to' => '2026-05-25']));
        $response->assertSee('Pembangunan Balai RT');
        $response->assertDontSee('Kegiatan Bersih Desa');

        // Sorting: terbaru
        $response = $this->get(route('galeri.index', ['urutkan' => 'terbaru']));
        $response->assertStatus(200);

        // Sorting: terlama
        $response = $this->get(route('galeri.index', ['urutkan' => 'terlama']));
        $response->assertStatus(200);
    }

    // ==================== PROFIL DESA ====================

    public function test_profil_desa_pages_load(): void
    {
        ProfilDesa::factory()->warurejo()->create();

        $response = $this->get(route('profil.index'));
        $response->assertRedirect(route('profil.visi-misi'));

        $response = $this->get(route('profil.visi-misi'));
        $response->assertStatus(200);
        $response->assertViewIs('public.profil.visi-misi');

        $response = $this->get(route('profil.sejarah'));
        $response->assertStatus(200);
        $response->assertViewIs('public.profil.sejarah');

        $response = $this->get(route('profil.struktur-organisasi'));
        $response->assertStatus(200);
        $response->assertViewIs('public.profil.struktur-organisasi');
    }

    // ==================== PUBLIKASI ====================

    public function test_publikasi_pages_load_and_filter(): void
    {
        $p1 = Publikasi::factory()->published()->create([
            'judul' => 'Laporan APBDes 2026',
            'kategori' => 'APBDes',
            'tahun' => 2026,
            'jumlah_download' => 10,
        ]);
        $p2 = Publikasi::factory()->published()->create([
            'judul' => 'Rencana RKPDes 2025',
            'kategori' => 'RKPDes',
            'tahun' => 2025,
            'jumlah_download' => 20,
        ]);

        $response = $this->get(route('publikasi.index'));
        $response->assertStatus(200);
        $response->assertViewIs('public.publikasi.index');

        // Category filter
        $response = $this->get(route('publikasi.index', ['kategori' => 'RKPDes']));
        $response->assertStatus(200);
        $response->assertSee('Rencana RKPDes 2025');

        // Year filter
        $response = $this->get(route('publikasi.index', ['tahun' => 2026]));
        $response->assertStatus(200);
        $response->assertSee('Laporan APBDes 2026');

        // Search filter
        $response = $this->get(route('publikasi.index', ['search' => 'Laporan']));
        $response->assertStatus(200);
        $response->assertSee('Laporan APBDes 2026');

        // Sorting: terbaru
        $response = $this->get(route('publikasi.index', ['urutkan' => 'terbaru']));
        $response->assertStatus(200);

        // Sorting: terlama
        $response = $this->get(route('publikasi.index', ['urutkan' => 'terlama']));
        $response->assertStatus(200);

        // Show single publikasi
        $response = $this->get(route('publikasi.show', $p1->id));
        $response->assertStatus(200);
        $response->assertViewIs('public.publikasi.show');
        $response->assertSee($p1->judul);
    }

    public function test_publikasi_sorting(): void
    {
        // p1: newer publication date, but created earlier
        $p1 = Publikasi::factory()->published()->create([
            'judul' => 'Publikasi A',
            'kategori' => 'APBDes',
            'tanggal_publikasi' => now()->subDays(5)->format('Y-m-d'),
            'created_at' => now()->subDays(10),
        ]);
        // p2: older publication date, but created later
        $p2 = Publikasi::factory()->published()->create([
            'judul' => 'Publikasi B',
            'kategori' => 'APBDes',
            'tanggal_publikasi' => now()->subDays(10)->format('Y-m-d'),
            'created_at' => now()->subDays(5),
        ]);

        // Terbaru: p1 (newer) should be first, then p2 (older)
        $response = $this->get(route('publikasi.index', ['urutkan' => 'terbaru']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Publikasi A', 'Publikasi B']);

        // Terlama: p2 (older) should be first, then p1 (newer)
        $response = $this->get(route('publikasi.index', ['urutkan' => 'terlama']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Publikasi B', 'Publikasi A']);
    }

    public function test_galeri_sorting(): void
    {
        $admin = \App\Models\Admin::factory()->create();
        // g1: newer event date, but created earlier
        $g1 = Galeri::factory()->active()->create([
            'admin_id' => $admin->id,
            'judul' => 'Galeri A',
            'tanggal' => now()->subDays(5)->format('Y-m-d H:i:s'),
            'created_at' => now()->subDays(10),
        ]);
        // g2: older event date, but created later
        $g2 = Galeri::factory()->active()->create([
            'admin_id' => $admin->id,
            'judul' => 'Galeri B',
            'tanggal' => now()->subDays(10)->format('Y-m-d H:i:s'),
            'created_at' => now()->subDays(5),
        ]);

        // Terbaru: g1 (newer) should be first, then g2 (older)
        $response = $this->get(route('galeri.index', ['urutkan' => 'terbaru']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Galeri A', 'Galeri B']);

        // Terlama: g2 (older) should be first, then g1 (newer)
        $response = $this->get(route('galeri.index', ['urutkan' => 'terlama']));
        $response->assertStatus(200);
        $response->assertSeeInOrder(['Galeri B', 'Galeri A']);
    }

    public function test_publikasi_download(): void
    {
        $p = Publikasi::factory()->published()->create([
            'judul' => 'Downloadable Document',
            'file_dokumen' => 'publikasi/test.pdf',
            'jumlah_download' => 0,
        ]);

        $response = $this->get(route('publikasi.download', $p->id));
        $response->assertStatus(403);
        $this->assertEquals(0, $p->fresh()->jumlah_download);
    }

    public function test_publikasi_download_file_not_found(): void
    {
        $p = Publikasi::factory()->published()->create([
            'file_dokumen' => 'publikasi/missing.pdf',
        ]);

        $response = $this->get(route('publikasi.download', $p->id));
        $response->assertStatus(403);
    }

    // ==================== 404 ====================

    public function test_404_for_non_existent_berita(): void
    {
        $response = $this->get(route('berita.show', 'non-existent-slug'));
        $response->assertStatus(404);
    }

    public function test_404_for_non_existent_potensi(): void
    {
        $response = $this->get(route('potensi.show', 'non-existent-slug'));
        $response->assertStatus(404);
    }

    // ==================== SEO Routes ====================

    public function test_sitemap_xml_loads(): void
    {
        $response = $this->get(route('sitemap'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
    }

    public function test_robots_txt_loads(): void
    {
        $response = $this->get(route('robots'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Sitemap: ');
    }
}
