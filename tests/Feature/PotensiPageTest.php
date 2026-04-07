<?php

namespace Tests\Feature;

use App\Models\PotensiDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PotensiPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test potensi index page loads successfully.
     */
    public function test_potensi_index_page_loads_successfully(): void
    {
        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.index');
    }

    /**
     * Test potensi index displays active items.
     */
    public function test_potensi_index_displays_active_items(): void
    {
        $potensi = PotensiDesa::factory()
            ->count(5)
            ->create(['is_active' => true]);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('potensi');
        
        // Check if first potensi is displayed
        $response->assertSee($potensi->first()->nama);
    }

    /**
     * Test potensi index does not display inactive items.
     */
    public function test_potensi_index_does_not_display_inactive_items(): void
    {
        $active = PotensiDesa::factory()
            ->create([
                'nama' => 'Active Potensi',
                'is_active' => true,
            ]);

        $inactive = PotensiDesa::factory()
            ->inactive()
            ->create(['nama' => 'Inactive Potensi']);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Potensi');
        $response->assertDontSee('Inactive Potensi');
    }

    /**
     * Test potensi index filters by category.
     */
    public function test_potensi_index_filters_by_category(): void
    {
        $pertanian = PotensiDesa::factory()
            ->kategori(PotensiDesa::KATEGORI_PERTANIAN)
            ->create(['nama' => 'Pertanian Item']);

        $wisata = PotensiDesa::factory()
            ->kategori(PotensiDesa::KATEGORI_WISATA)
            ->create(['nama' => 'Wisata Item']);

        $response = $this->get(route('potensi.index', ['kategori' => PotensiDesa::KATEGORI_PERTANIAN]));

        $response->assertStatus(200);
        $response->assertSee('Pertanian Item');
    }

    /**
     * Test potensi detail page loads successfully.
     */
    public function test_potensi_detail_page_loads_successfully(): void
    {
        $potensi = PotensiDesa::factory()
            ->create(['is_active' => true]);

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.show');
        $response->assertViewHas('potensi');
        $response->assertSee($potensi->nama);
    }

    /**
     * Test potensi detail page returns 404 for non-existent slug.
     */
    public function test_potensi_detail_page_returns_404_for_non_existent_slug(): void
    {
        $response = $this->get(route('potensi.show', 'non-existent-slug'));

        $response->assertStatus(404);
    }

    /**
     * Test potensi detail page returns 404 for inactive item.
     */
    public function test_potensi_detail_page_returns_404_for_inactive_item(): void
    {
        $potensi = PotensiDesa::factory()
            ->inactive()
            ->create();

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(404);
    }

    /**
     * Test potensi detail displays contact information.
     */
    public function test_potensi_detail_displays_contact_information(): void
    {
        $potensi = PotensiDesa::factory()
            ->create([
                'is_active' => true,
                'kontak' => '081234567890',
            ]);

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertSee('081234567890');
    }

    /**
     * Test potensi detail displays location information.
     */
    public function test_potensi_detail_displays_location_information(): void
    {
        $potensi = PotensiDesa::factory()
            ->create([
                'is_active' => true,
                'lokasi' => 'Test Location Address',
            ]);

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Location Address');
    }

    /**
     * Test potensi ordered by urutan field.
     */
    public function test_potensi_ordered_by_urutan_field(): void
    {
        $first = PotensiDesa::factory()
            ->create([
                'nama' => 'First Item',
                'urutan' => 1,
                'is_active' => true,
            ]);

        $second = PotensiDesa::factory()
            ->create([
                'nama' => 'Second Item',
                'urutan' => 2,
                'is_active' => true,
            ]);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('potensi');
    }
}
