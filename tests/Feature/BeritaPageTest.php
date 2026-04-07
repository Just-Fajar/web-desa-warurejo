<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test berita index page loads successfully.
     */
    public function test_berita_index_page_loads_successfully(): void
    {
        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.index');
    }

    /**
     * Test berita index displays published articles.
     */
    public function test_berita_index_displays_published_articles(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->count(5)
            ->for($admin)
            ->published()
            ->create();

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertViewHas('berita');
        
        // Check if first berita is displayed
        $response->assertSee($berita->first()->judul);
    }

    /**
     * Test berita index does not display draft articles.
     */
    public function test_berita_index_does_not_display_draft_articles(): void
    {
        $admin = Admin::factory()->create();
        
        $published = Berita::factory()
            ->for($admin)
            ->published()
            ->create(['judul' => 'Published Article']);

        $draft = Berita::factory()
            ->for($admin)
            ->draft()
            ->create(['judul' => 'Draft Article']);

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertSee('Published Article');
        $response->assertDontSee('Draft Article');
    }

    /**
     * Test berita detail page loads successfully.
     */
    public function test_berita_detail_page_loads_successfully(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->for($admin)
            ->published()
            ->create();

        $response = $this->get(route('berita.show', $berita->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.berita.show');
        $response->assertViewHas('berita');
        $response->assertSee($berita->judul);
    }

    /**
     * Test berita detail page increments views.
     */
    public function test_berita_detail_page_increments_views(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->for($admin)
            ->published()
            ->create(['views' => 0]);

        $this->get(route('berita.show', $berita->slug));

        $berita->refresh();
        $this->assertEquals(1, $berita->views);
    }

    /**
     * Test berita detail page returns 404 for non-existent slug.
     */
    public function test_berita_detail_page_returns_404_for_non_existent_slug(): void
    {
        $response = $this->get(route('berita.show', 'non-existent-slug'));

        $response->assertStatus(404);
    }

    /**
     * Test berita detail page returns 404 for draft article.
     */
    public function test_berita_detail_page_returns_404_for_draft_article(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->for($admin)
            ->draft()
            ->create();

        $response = $this->get(route('berita.show', $berita->slug));

        $response->assertStatus(404);
    }

    /**
     * Test berita index has pagination.
     */
    public function test_berita_index_has_pagination(): void
    {
        $admin = Admin::factory()->create();
        Berita::factory()
            ->count(20)
            ->for($admin)
            ->published()
            ->create();

        $response = $this->get(route('berita.index'));

        $response->assertStatus(200);
        $response->assertViewHas('berita', function ($berita) {
            return $berita->hasPages();
        });
    }

    /**
     * Test berita detail displays admin information.
     */
    public function test_berita_detail_displays_admin_information(): void
    {
        $admin = Admin::factory()->create(['name' => 'Test Admin']);
        $berita = Berita::factory()
            ->for($admin)
            ->published()
            ->create();

        $response = $this->get(route('berita.show', $berita->slug));

        $response->assertStatus(200);
        $response->assertSee('Test Admin');
    }
}
