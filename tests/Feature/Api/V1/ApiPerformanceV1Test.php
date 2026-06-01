<?php

namespace Tests\Feature\Api\V1;

use App\Models\Berita;
use App\Models\PotensiDesa;
use App\Models\Galeri;
use App\Models\Publikasi;
use App\Models\ProfilDesa;
use App\Models\DailyVisitorStat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiPerformanceV1Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize default seed data
        ProfilDesa::factory()->warurejo()->create();
    }

    /**
     * Test versioning middleware sets headers.
     */
    public function test_api_version_middleware_sets_headers()
    {
        $response = $this->getJson('/api/v1/profil');

        $response->assertStatus(200);
        $response->assertHeader('X-API-Version', '1.0.0');
        $response->assertHeader('X-API-Status', 'active');
        $response->assertHeader('X-API-Environment', 'testing');
        $response->assertHeaderMissing('API-Deprecated');
    }

    /**
     * Test versioning middleware warns about deprecation for old headers.
     */
    public function test_api_version_middleware_warns_deprecation_for_old_header()
    {
        $response = $this->withHeaders([
            'X-API-Version' => '0.9.0'
        ])->getJson('/api/v1/profil');

        $response->assertStatus(200);
        $response->assertHeader('API-Deprecated', 'true');
        $response->assertHeader('X-API-Version', '1.0.0');
    }

    /**
     * Test success response format.
     */
    public function test_api_response_success_format()
    {
        $response = $this->getJson('/api/v1/profil');

        $response->assertJsonStructure([
            'success',
            'message',
            'data'
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertEquals('Request successful', $response->json('message'));
    }

    /**
     * Test error response format.
     */
    public function test_api_response_error_format()
    {
        // Try getting a non-existent news slug
        $response = $this->getJson('/api/v1/berita/non-existent-slug');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);
        $this->assertFalse($response->json('success'));
    }

    /**
     * Test cache hit/miss behavior on read requests.
     */
    public function test_api_cache_middleware_caches_responses()
    {
        // First request to get ETag
        $response1 = $this->getJson('/api/v1/profil');
        $response1->assertStatus(200);
        $response1->assertHeader('ETag');
        $response1->assertHeader('Cache-Control', 'max-age=60, must-revalidate, public');

        $etag = $response1->headers->get('ETag');

        // Second request with If-None-Match
        $response2 = $this->withHeaders([
            'If-None-Match' => $etag
        ])->getJson('/api/v1/profil');

        $response2->assertStatus(304);
    }

    /**
     * Test statistics summary endpoint returns expected data structures.
     */
    public function test_statistik_summary_returns_correct_counters()
    {
        // Create mock data
        Berita::factory()->published()->count(3)->create(['views' => 10]);
        PotensiDesa::factory()->active()->count(2)->create(['views' => 5]);
        Galeri::factory()->active()->count(4)->create(['views' => 8]);
        
        Publikasi::factory()->published()->create([
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'views' => 20,
            'jumlah_download' => 15
        ]);

        DailyVisitorStat::create([
            'date' => now()->toDateString(),
            'unique_visitors' => 50,
            'page_views' => 120
        ]);

        $response = $this->getJson('/api/v1/statistik/summary');

        $response->assertStatus(200);
        $response->assertJsonPath('data.contents.berita.count', 3);
        $response->assertJsonPath('data.contents.berita.views', 30);
        $response->assertJsonPath('data.contents.potensi.count', 2);
        $response->assertJsonPath('data.contents.potensi.views', 10);
        $response->assertJsonPath('data.contents.publikasi.count', 1);
        $response->assertJsonPath('data.contents.publikasi.downloads', 15);
    }

    /**
     * Test publikasi index filtering.
     */
    public function test_publikasi_index_with_filters()
    {
        Publikasi::factory()->published()->create([
            'judul' => 'APBDes Warurejo 2024',
            'kategori' => 'APBDes',
            'tahun' => 2024
        ]);

        Publikasi::factory()->published()->create([
            'judul' => 'RPJMDes Warurejo 2023',
            'kategori' => 'RPJMDes',
            'tahun' => 2023
        ]);

        // Filter by category
        $response = $this->getJson('/api/v1/publikasi?kategori=APBDes');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('APBDes Warurejo 2024', $response->json('data.0.judul'));

        // Filter by year
        $responseYear = $this->getJson('/api/v1/publikasi?tahun=2023');
        $responseYear->assertStatus(200);
        $this->assertCount(1, $responseYear->json('data'));
        $this->assertEquals('RPJMDes Warurejo 2023', $responseYear->json('data.0.judul'));
    }
}
