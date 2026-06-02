<?php

namespace Tests\Feature\Api\V1;

use App\Models\Admin;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\GaleriImage;
use App\Models\PotensiDesa;
use App\Http\Resources\Api\BeritaCollection;
use App\Jobs\IncrementViewsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiEndpointsV1Test extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Authentication API endpoints (Login, Logout, Me, Tokens).
     */
    public function test_auth_api_workflow(): void
    {
        $password = 'SecretPassword123!';
        $admin = Admin::factory()->create([
            'email' => 'test-api@warurejo.gov',
            'password' => Hash::make($password),
        ]);

        // 1. Fail login validation
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test-api@warurejo.gov',
            'password' => 'wrong-pass',
            'device_name' => 'TestDevice',
        ]);
        $response->assertStatus(422)
            ->assertJsonPath('success', false);

        // 2. Successful login
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test-api@warurejo.gov',
            'password' => $password,
            'device_name' => 'TestDevice',
        ]);
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'admin' => ['id', 'nama', 'email', 'username'],
                    'token',
                    'token_type'
                ]
            ]);

        $token = $response->json('data.token');

        // 3. Get Authenticated User Details (Me)
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/me');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'test-api@warurejo.gov');

        // 4. List Tokens
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/tokens');
        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'abilities', 'last_used_at', 'created_at']
                ]
            ]);

        // 5. Logout
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/logout');
        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->app['auth']->forgetGuards();

        // ME should now fail (unauthenticated)
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/me');
        $response->assertStatus(401);
    }

    /**
     * Test LogoutAll functionality.
     */
    public function test_auth_api_logout_all(): void
    {
        $admin = Admin::factory()->create();
        $token1 = $admin->createToken('Device1')->plainTextToken;
        $token2 = $admin->createToken('Device2')->plainTextToken;

        // Logout all
        $response = $this->withHeader('Authorization', 'Bearer ' . $token1)
            ->postJson('/api/v1/logout-all');
        $response->assertStatus(200);

        $this->app['auth']->forgetGuards();

        // Both tokens should be revoked
        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer ' . $token1])->assertStatus(401);
        $this->getJson('/api/v1/me', ['Authorization' => 'Bearer ' . $token2])->assertStatus(401);
    }

    /**
     * Test Berita API endpoints.
     */
    public function test_berita_api_endpoints(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()->published()->create([
            'admin_id' => $admin->id,
            'judul' => 'Sistem Informasi Desa Baru',
            'views' => 10,
        ]);

        // Index
        $response = $this->getJson('/api/v1/berita');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => ['id', 'judul', 'slug', 'excerpt', 'konten', 'konten_html', 'gambar', 'views', 'published_at', 'created_at', 'updated_at', 'author']
                ]
            ]);

        // Latest
        $response = $this->getJson('/api/v1/berita/latest?limit=2');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.slug', $berita->slug);

        // Popular
        $response = $this->getJson('/api/v1/berita/popular');
        $response->assertStatus(200);

        // Show details (IncrementViewsJob handles afterResponse)
        $response = $this->getJson('/api/v1/berita/' . $berita->slug);
        $response->assertStatus(200)
            ->assertJsonPath('data.judul', 'Sistem Informasi Desa Baru');

        // Test search filter
        $response = $this->getJson('/api/v1/berita?search=Sistem');
        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));

        // Test date range filter
        $response = $this->getJson('/api/v1/berita?from_date=' . now()->subDay()->toDateString() . '&to_date=' . now()->addDay()->toDateString());
        $response->assertStatus(200);
    }

    /**
     * Test Potensi Desa API endpoints.
     */
    public function test_potensi_api_endpoints(): void
    {
        $potensi = PotensiDesa::factory()->active()->create([
            'nama' => 'Kerajinan Batik Warurejo',
            'views' => 5,
        ]);

        // Index
        $response = $this->getJson('/api/v1/potensi');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id', 'nama', 'slug', 'excerpt', 'deskripsi', 'deskripsi_html',
                        'gambar', 'views', 'kategori', 'lokasi', 'link_maps', 'info_utama'
                    ]
                ]
            ]);

        // Featured
        $response = $this->getJson('/api/v1/potensi/featured');
        $response->assertStatus(200);

        // Show details
        $response = $this->getJson('/api/v1/potensi/' . $potensi->slug);
        $response->assertStatus(200)
            ->assertJsonPath('data.nama', 'Kerajinan Batik Warurejo');

        // Test search filter
        $response = $this->getJson('/api/v1/potensi?search=Kerajinan');
        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    /**
     * Test Galeri API endpoints.
     */
    public function test_galeri_api_endpoints(): void
    {
        $galeri = Galeri::factory()->active()->create([
            'judul' => 'Perayaan HUT RI Desa',
            'kategori' => 'Kegiatan',
        ]);
        
        // Add an image
        $image = new GaleriImage([
            'image_path' => 'galeri/test.jpg',
            'urutan' => 1,
        ]);
        $galeri->images()->save($image);

        // Index
        $response = $this->getJson('/api/v1/galeri');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id', 'judul', 'deskripsi', 'kategori', 'tanggal',
                        'views', 'images', 'thumbnail', 'created_at', 'updated_at'
                    ]
                ]
            ]);

        // Latest
        $response = $this->getJson('/api/v1/galeri/latest');
        $response->assertStatus(200);

        // Categories
        $response = $this->getJson('/api/v1/galeri/categories');
        $response->assertStatus(200)
            ->assertJsonFragment(['Kegiatan']);

        // Show details
        $response = $this->getJson('/api/v1/galeri/' . $galeri->id);
        $response->assertStatus(200)
            ->assertJsonPath('data.judul', 'Perayaan HUT RI Desa')
            ->assertJsonStructure([
                'data' => [
                    'images' => [
                        '*' => ['id', 'path', 'raw_path', 'urutan']
                    ]
                ]
            ]);
    }

    /**
     * Test IncrementViewsJob directly to cover job execution.
     */
    public function test_increment_views_job(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()->published()->create([
            'admin_id' => $admin->id,
            'views' => 10,
        ]);

        // Run the job handler directly
        $job = new IncrementViewsJob($berita);
        $job->handle();

        $this->assertEquals(11, $berita->fresh()->views);
    }

    /**
     * Test BeritaCollection serialization explicitly.
     */
    public function test_berita_collection_serialization(): void
    {
        $admin = Admin::factory()->create();
        $berita = Berita::factory()->published()->count(2)->create([
            'admin_id' => $admin->id,
        ]);

        $collection = new BeritaCollection($berita);
        $array = $collection->toArray(request());

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('judul', $array[0]);
    }
}
