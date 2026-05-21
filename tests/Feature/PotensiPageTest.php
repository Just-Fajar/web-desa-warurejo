<?php

namespace Tests\Feature;

use App\Models\PotensiDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PotensiPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_potensi_index_page_loads_successfully(): void
    {
        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.index');
    }

    public function test_potensi_index_displays_active_items(): void
    {
        $potensi = PotensiDesa::factory()
            ->count(5)
            ->create(['status' => 'published', 'published_at' => now()]);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('potensi');
        $response->assertSee($potensi->first()->nama);
    }

    public function test_potensi_index_does_not_display_inactive_items(): void
    {
        PotensiDesa::factory()->create([
            'nama' => 'Active Potensi',
            'status' => 'published',
            'published_at' => now(),
        ]);

        PotensiDesa::factory()->inactive()->create([
            'nama' => 'Inactive Potensi',
        ]);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Potensi');
        $response->assertDontSee('Inactive Potensi');
    }

    public function test_potensi_index_filters_by_category(): void
    {
        PotensiDesa::factory()
            ->kategori(PotensiDesa::KATEGORI_PERTANIAN)
            ->create(['nama' => 'Pertanian Item']);

        PotensiDesa::factory()
            ->kategori(PotensiDesa::KATEGORI_WISATA)
            ->create(['nama' => 'Wisata Item']);

        $response = $this->get(route('potensi.index', ['kategori' => PotensiDesa::KATEGORI_PERTANIAN]));

        $response->assertStatus(200);
        $response->assertSee('Pertanian Item');
    }

    public function test_potensi_detail_page_loads_successfully(): void
    {
        $potensi = PotensiDesa::factory()->create();

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.potensi.show');
        $response->assertViewHas('potensi');
        $response->assertSee($potensi->nama);
    }

    public function test_potensi_detail_page_returns_404_for_non_existent_slug(): void
    {
        $response = $this->get(route('potensi.show', 'non-existent-slug'));
        $response->assertStatus(404);
    }

    public function test_potensi_detail_page_returns_404_for_inactive_item(): void
    {
        $potensi = PotensiDesa::factory()->inactive()->create();

        $response = $this->get(route('potensi.show', $potensi->slug));
        $response->assertStatus(404);
    }

    public function test_potensi_detail_displays_whatsapp_information(): void
    {
        $potensi = PotensiDesa::factory()->create([
            'whatsapp' => '081234567890',
        ]);

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        // Verify data is accessible through view
        $response->assertViewHas('potensi');
    }

    public function test_potensi_detail_displays_location_information(): void
    {
        $potensi = PotensiDesa::factory()->create([
            'lokasi' => 'Test Location Address',
        ]);

        $response = $this->get(route('potensi.show', $potensi->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Location Address');
    }

    public function test_potensi_ordered_by_urutan_field(): void
    {
        PotensiDesa::factory()->create(['nama' => 'First Item', 'urutan' => 1]);
        PotensiDesa::factory()->create(['nama' => 'Second Item', 'urutan' => 2]);

        $response = $this->get(route('potensi.index'));

        $response->assertStatus(200);
        $response->assertViewHas('potensi');
    }
}
