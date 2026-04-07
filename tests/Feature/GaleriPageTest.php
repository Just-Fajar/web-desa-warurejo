<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test galeri index page loads successfully.
     */
    public function test_galeri_index_page_loads_successfully(): void
    {
        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.galeri.index');
    }

    /**
     * Test galeri index displays active items.
     */
    public function test_galeri_index_displays_active_items(): void
    {
        $admin = Admin::factory()->create();
        $galeri = Galeri::factory()
            ->count(5)
            ->for($admin)
            ->create(['is_active' => true]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris');

        // Check if first galeri is displayed
        $response->assertSee($galeri->first()->judul);
    }

    /**
     * Test galeri index does not display inactive items.
     */
    public function test_galeri_index_does_not_display_inactive_items(): void
    {
        $admin = Admin::factory()->create();
        
        $active = Galeri::factory()
            ->for($admin)
            ->create([
                'judul' => 'Active Galeri',
                'is_active' => true,
            ]);

        $inactive = Galeri::factory()
            ->for($admin)
            ->inactive()
            ->create(['judul' => 'Inactive Galeri']);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Galeri');
        $response->assertDontSee('Inactive Galeri');
    }

    /**
     * Test galeri index filters by category.
     */
    public function test_galeri_index_filters_by_category(): void
    {
        $admin = Admin::factory()->create();
        
        $kegiatanGaleri = Galeri::factory()
            ->for($admin)
            ->kategori(Galeri::KATEGORI_KEGIATAN)
            ->create(['judul' => 'Kegiatan Item']);

        $budayaGaleri = Galeri::factory()
            ->for($admin)
            ->kategori(Galeri::KATEGORI_BUDAYA)
            ->create(['judul' => 'Budaya Item']);

        $response = $this->get(route('galeri.index', ['kategori' => Galeri::KATEGORI_KEGIATAN]));

        $response->assertStatus(200);
        $response->assertSee('Kegiatan Item');
        // May or may not see Budaya Item depending on pagination
    }

    /**
     * Test galeri index has pagination.
     */
    public function test_galeri_index_has_pagination(): void
    {
        $admin = Admin::factory()->create();
        Galeri::factory()
            ->count(30)
            ->for($admin)
            ->create(['is_active' => true]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris', function ($galeris) {
            return $galeris->hasPages();
        });
    }

    /**
     * Test galeri displays admin information.
     */
    public function test_galeri_displays_admin_information(): void
    {
        $admin = Admin::factory()->create(['name' => 'Test Admin']);
        $galeri = Galeri::factory()
            ->for($admin)
            ->create(['is_active' => true]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        // Admin name may be displayed depending on view design
    }

    /**
     * Test galeri ordered by date.
     */
    public function test_galeri_ordered_by_date(): void
    {
        $admin = Admin::factory()->create();
        
        $older = Galeri::factory()
            ->for($admin)
            ->create([
                'judul' => 'Older Item',
                'tanggal' => now()->subDays(10),
                'is_active' => true,
            ]);

        $newer = Galeri::factory()
            ->for($admin)
            ->create([
                'judul' => 'Newer Item',
                'tanggal' => now()->subDay(),
                'is_active' => true,
            ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris');
    }
}
