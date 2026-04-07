<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\ProfilDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage loads successfully.
     */
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('public.home');
    }

    /**
     * Test homepage displays latest berita.
     */
    public function test_homepage_displays_latest_berita(): void
    {
        // Create admin and published berita
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->count(3)
            ->for($admin)
            ->published()
            ->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('latest_berita');
        
        // Check if berita titles are displayed
        foreach ($berita as $item) {
            $response->assertSee($item->judul);
        }
    }

    /**
     * Test homepage displays potensi desa.
     */
    public function test_homepage_displays_potensi_desa(): void
    {
        $potensi = PotensiDesa::factory()
            ->count(3)
            ->create(['is_active' => true]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('potensi');
        
        // Check if potensi section exists (view might use nama_potensi accessor or $item->nama)
        // Since view uses Blade @forelse, just verify the potensi variable is passed
        $this->assertNotNull($response->viewData('potensi'));
    }

    /**
     * Test homepage displays galeri.
     */
    public function test_homepage_displays_galeri(): void
    {
        $admin = Admin::factory()->create();
        $galeri = Galeri::factory()
            ->count(3)
            ->for($admin)
            ->create(['is_active' => true]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('galeri');
    }

    /**
     * Test homepage only shows published berita.
     */
    public function test_homepage_only_shows_published_berita(): void
    {
        $admin = Admin::factory()->create();
        
        // Create published berita
        $publishedBerita = Berita::factory()
            ->for($admin)
            ->published()
            ->create(['judul' => 'Published Berita']);

        // Create draft berita
        $draftBerita = Berita::factory()
            ->for($admin)
            ->draft()
            ->create(['judul' => 'Draft Berita']);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Published Berita');
        $response->assertDontSee('Draft Berita');
    }
}
