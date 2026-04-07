<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Berita;
use App\Repositories\BeritaRepository;
use App\Services\BeritaService;
use App\Services\HtmlSanitizerService;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $beritaService;
    protected $beritaRepository;
    protected $imageUploadService;
    protected $htmlSanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beritaRepository = app(BeritaRepository::class);
        $this->imageUploadService = app(ImageUploadService::class);
        $this->htmlSanitizer = app(HtmlSanitizerService::class);
        
        $this->beritaService = new BeritaService(
            $this->beritaRepository,
            $this->imageUploadService,
            $this->htmlSanitizer
        );
    }

    /**
     * Test create berita sanitizes HTML content.
     */
    public function test_create_berita_sanitizes_html_content(): void
    {
        Storage::fake('public');
        Cache::flush();

        $admin = Admin::factory()->create();

        $data = [
            'admin_id' => $admin->id,
            'judul' => 'Test Berita',
            'ringkasan' => '<p>Test summary with <script>alert("xss")</script> script</p>',
            'konten' => '<p>Test content with <script>alert("xss")</script> script</p>',
            'status' => 'published',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertNotNull($berita);
        $this->assertStringNotContainsString('<script>', $berita->konten);
        $this->assertStringNotContainsString('<script>', $berita->ringkasan);
    }

    /**
     * Test create berita sets published_at when status is published.
     */
    public function test_create_berita_sets_published_at_for_published_status(): void
    {
        Cache::flush();

        $admin = Admin::factory()->create();

        $data = [
            'admin_id' => $admin->id,
            'judul' => 'Published Berita',
            'ringkasan' => 'Summary',
            'konten' => 'Content',
            'status' => 'published',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertNotNull($berita->published_at);
        $this->assertEquals('published', $berita->status);
    }

    /**
     * Test create berita does not set published_at for draft.
     */
    public function test_create_berita_does_not_set_published_at_for_draft(): void
    {
        Cache::flush();

        $admin = Admin::factory()->create();

        $data = [
            'admin_id' => $admin->id,
            'judul' => 'Draft Berita',
            'ringkasan' => 'Summary',
            'konten' => 'Content',
            'status' => 'draft',
        ];

        $berita = $this->beritaService->createBerita($data);

        $this->assertNull($berita->published_at);
        $this->assertEquals('draft', $berita->status);
    }

    /**
     * Test create berita clears cache.
     */
    public function test_create_berita_clears_cache(): void
    {
        Cache::flush();

        $admin = Admin::factory()->create();

        // Set some cache
        Cache::put('home.latest_berita', 'test_data', 3600);
        Cache::put('berita.published', 'test_data', 3600);

        $this->assertTrue(Cache::has('home.latest_berita'));
        $this->assertTrue(Cache::has('berita.published'));

        $data = [
            'admin_id' => $admin->id,
            'judul' => 'Cache Test Berita',
            'ringkasan' => 'Summary',
            'konten' => 'Content',
            'status' => 'published',
        ];

        $this->beritaService->createBerita($data);

        $this->assertFalse(Cache::has('home.latest_berita'));
        $this->assertFalse(Cache::has('berita.published'));
    }

    /**
     * Test update berita sanitizes HTML content.
     */
    public function test_update_berita_sanitizes_html_content(): void
    {
        Storage::fake('public');
        Cache::flush();

        $admin = Admin::factory()->create();
        $berita = Berita::factory()->for($admin)->create();

        $data = [
            'ringkasan' => '<p>Updated summary with <script>alert("xss")</script> script</p>',
            'konten' => '<p>Updated content with <script>alert("xss")</script> script</p>',
        ];

        $updated = $this->beritaService->updateBerita($berita->id, $data);

        $this->assertNotNull($updated);
        $this->assertStringNotContainsString('<script>', $updated->konten);
        $this->assertStringNotContainsString('<script>', $updated->ringkasan);
    }

    /**
     * Test update berita sets published_at when changing to published.
     */
    public function test_update_berita_sets_published_at_when_publishing(): void
    {
        Cache::flush();

        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->for($admin)
            ->draft()
            ->create();

        $this->assertNull($berita->published_at);

        $data = [
            'status' => 'published',
        ];

        $updated = $this->beritaService->updateBerita($berita->id, $data);

        $this->assertNotNull($updated->published_at);
    }

    /**
     * Test update berita clears cache.
     */
    public function test_update_berita_clears_cache(): void
    {
        Cache::flush();

        $admin = Admin::factory()->create();
        $berita = Berita::factory()->for($admin)->create();

        // Set cache
        Cache::put('home.latest_berita', 'test_data', 3600);
        Cache::put('berita.published', 'test_data', 3600);
        Cache::put('berita.' . $berita->id, 'test_data', 3600);

        $data = ['judul' => 'Updated Title'];

        $this->beritaService->updateBerita($berita->id, $data);

        $this->assertFalse(Cache::has('home.latest_berita'));
        $this->assertFalse(Cache::has('berita.published'));
        $this->assertFalse(Cache::has('berita.' . $berita->id));
    }

    /**
     * Test delete berita clears cache.
     */
    public function test_delete_berita_clears_cache(): void
    {
        Storage::fake('public');
        Cache::flush();

        $admin = Admin::factory()->create();
        $berita = Berita::factory()->for($admin)->create();

        // Set cache
        Cache::put('home.latest_berita', 'test_data', 3600);
        Cache::put('berita.' . $berita->id, 'test_data', 3600);

        $this->beritaService->deleteBerita($berita->id);

        $this->assertFalse(Cache::has('home.latest_berita'));
        $this->assertFalse(Cache::has('berita.' . $berita->id));
    }

    /**
     * Test get berita by slug increments views.
     */
    public function test_get_berita_by_slug_increments_views(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()
            ->for($admin)
            ->published()
            ->create(['views' => 10]);

        $result = $this->beritaService->getBeritaBySlug($berita->slug);

        $berita->refresh();
        $this->assertEquals(11, $berita->views);
    }
}
