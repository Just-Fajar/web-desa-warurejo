<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\PotensiDesa;
use App\Models\ProfilDesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Create required data for homepage
        ProfilDesa::factory()->warurejo()->create();
        Berita::factory()->published()->count(3)->create();
        Galeri::factory()->active()->count(3)->create();
        PotensiDesa::factory()->active()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
