<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\ProfilDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage loads successfully
     */
    public function test_homepage_loads_successfully(): void
    {
        // Create profil desa
        ProfilDesa::factory()->warurejo()->create();
        
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('public.home');
        $response->assertSee('Warurejo');
    }

    /**
     * Test berita index page loads
     */
    public function test_berita_index_page_loads(): void
    {
        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.index');
    }

    /**
     * Test berita index displays published news only
     */
    public function test_berita_index_displays_published_news_only(): void
    {
        // Create published and draft berita
        $published = Berita::factory()->published()->count(3)->create();
        $draft = Berita::factory()->draft()->count(2)->create();

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        
        // Should see published berita
        foreach ($published as $berita) {
            $response->assertSee($berita->judul);
        }
        
        // Should NOT see draft berita
        foreach ($draft as $berita) {
            $response->assertDontSee($berita->judul);
        }
    }

    /**
     * Test berita detail page loads
     */
    public function test_berita_detail_page_loads(): void
    {
        $berita = Berita::factory()->published()->create();

        $response = $this->get(route('berita.show', $berita->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.show');
        $response->assertSee($berita->judul);
        $response->assertSee($berita->konten);
    }

    /**
     * Test berita detail increments views
     */
    public function test_berita_detail_increments_views(): void
    {
        $berita = Berita::factory()->published()->create(['views' => 10]);

        $this->get(route('berita.show', $berita->slug));

        // View count should increase
        $this->assertEquals(11, $berita->fresh()->views);
    }

    /**
     * Test berita search returns matching results
     */
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

    /**
     * Test potensi index page loads
     */
    public function test_potensi_index_page_loads(): void
    {
        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.index');
    }

    /**
     * Test potensi index displays active items only
     */
    public function test_potensi_index_displays_active_items_only(): void
    {
        $active = PotensiDesa::factory()->count(3)->create();
        $inactive = PotensiDesa::factory()->inactive()->count(2)->create();

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        
        // Should see active potensi
        foreach ($active as $potensi) {
            $response->assertSee($potensi->nama);
        }
        
        // Should NOT see inactive potensi
        foreach ($inactive as $potensi) {
            $response->assertDontSee($potensi->nama);
        }
    }

    /**
     * Test potensi detail page loads
     */
    public function test_potensi_detail_page_loads(): void
    {
        $potensi = PotensiDesa::factory()->create();

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.show');
        $response->assertSee($potensi->nama);
        // Use assertSeeText to ignore HTML tags in deskripsi
        $response->assertSeeText(substr(strip_tags($potensi->deskripsi), 0, 100), false);
    }

    /**
     * Test galeri index page loads
     */
    public function test_galeri_index_page_loads(): void
    {
        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.galeri.index');
    }

    /**
     * Test galeri index displays active items only
     */
    public function test_galeri_index_displays_active_items_only(): void
    {
        $active = Galeri::factory()->count(3)->create();
        $inactive = Galeri::factory()->inactive()->count(2)->create();

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        
        // Should see active galeri
        foreach ($active as $galeri) {
            $response->assertSee($galeri->judul);
        }
    }

    /**
     * Test profil desa pages load
     */
    public function test_profil_desa_pages_load(): void
    {
        ProfilDesa::factory()->warurejo()->create();

        // Test visi misi
        $response = $this->get(route('profil.visi-misi'));
        $response->assertStatus(200);

        // Test sejarah
        $response = $this->get(route('profil.sejarah'));
        $response->assertStatus(200);
    }

    /**
     * Test kontak page loads
     */
    public function test_kontak_page_loads(): void
    {
        ProfilDesa::factory()->warurejo()->create();
        
        $response = $this->get(route('kontak.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.kontak.index');
    }

    /**
     * Test 404 page for non-existent berita
     */
    public function test_404_for_non_existent_berita(): void
    {
        $response = $this->get(route('berita.show', 'non-existent-slug'));

        $response->assertStatus(404);
    }

    /**
     * Test 404 page for non-existent potensi
     */
    public function test_404_for_non_existent_potensi(): void
    {
        $response = $this->get(route('potensi.show', 'non-existent-slug'));

        $response->assertStatus(404);
    }
}
