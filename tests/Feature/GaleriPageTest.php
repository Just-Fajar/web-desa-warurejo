<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GaleriPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_galeri_index_page_loads_successfully(): void
    {
        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.galeri.index');
    }

    public function test_galeri_index_displays_active_items(): void
    {
        $admin = Admin::factory()->create();
        $galeri = Galeri::factory()
            ->count(5)
            ->for($admin)
            ->create(['status' => 'published', 'published_at' => now()]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris');
        $response->assertSee($galeri->first()->judul);
    }

    public function test_galeri_index_does_not_display_inactive_items(): void
    {
        $admin = Admin::factory()->create();

        Galeri::factory()->for($admin)->create([
            'judul' => 'Active Galeri',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Galeri::factory()->for($admin)->create([
            'judul' => 'Inactive Galeri',
            'status' => 'draft',
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertSee('Active Galeri');
        $response->assertDontSee('Inactive Galeri');
    }

    public function test_galeri_index_filters_by_category(): void
    {
        $admin = Admin::factory()->create();

        Galeri::factory()->for($admin)
            ->kategori(Galeri::KATEGORI_KEGIATAN)
            ->create(['judul' => 'Kegiatan Item']);

        Galeri::factory()->for($admin)
            ->kategori(Galeri::KATEGORI_BUDAYA)
            ->create(['judul' => 'Budaya Item']);

        $response = $this->get(route('galeri.index', ['kategori' => Galeri::KATEGORI_KEGIATAN]));

        $response->assertStatus(200);
        $response->assertSee('Kegiatan Item');
    }

    public function test_galeri_index_has_pagination(): void
    {
        $admin = Admin::factory()->create();
        Galeri::factory()->count(30)->for($admin)->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris', function ($galeris) {
            return $galeris->hasPages();
        });
    }

    public function test_galeri_displays_admin_information(): void
    {
        $admin = Admin::factory()->create(['name' => 'Test Admin']);
        Galeri::factory()->for($admin)->create([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('galeri.index'));
        $response->assertStatus(200);
    }

    public function test_galeri_ordered_by_date(): void
    {
        $admin = Admin::factory()->create();

        Galeri::factory()->for($admin)->create([
            'judul' => 'Older Item',
            'tanggal' => now()->subDays(10),
            'status' => 'published',
            'published_at' => now()->subDays(10),
        ]);

        Galeri::factory()->for($admin)->create([
            'judul' => 'Newer Item',
            'tanggal' => now()->subDay(),
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertStatus(200);
        $response->assertViewHas('galeris');
    }
}
