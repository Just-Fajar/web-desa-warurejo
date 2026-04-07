<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     * 
     * Factory untuk generate dummy data berita (untuk testing/development)
     * 
     * Generate data random:
     * - judul: 6 kata random
     * - slug: auto-generate dari judul + unique number
     * - ringkasan: 2 paragraf random
     * - konten: 5 paragraf random (bisa diganti dengan real content)
     * - gambar_utama: Path dummy (ganti dengan real image di seeder)
     * - status: Random 'published' atau 'draft'
     * - views: 0-1000 random
     * - published_at: Jika published, tanggal 30 hari terakhir
     * 
     * USAGE:
     * - Berita::factory()->count(10)->create() // 10 berita random
     * - Berita::factory()->published()->create() // Force published
     * - Berita::factory()->draft()->create() // Force draft
     * - Berita::factory()->popular()->create() // High views
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->sentence(6);
        $status = fake()->randomElement(['published', 'draft']);
        
        return [
            'admin_id' => Admin::factory(),
            'judul' => $judul,
            'slug' => Str::slug($judul) . '-' . fake()->unique()->numberBetween(1, 10000),
            'ringkasan' => fake()->paragraph(2),
            'konten' => fake()->paragraphs(5, true),
            'gambar_utama' => 'berita/test-image.jpg',
            'status' => $status,
            'views' => fake()->numberBetween(0, 1000),
            'published_at' => $status === 'published' ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Indicate that the berita is published.
     * Force status = 'published' dengan published_at
     * 
     * Usage: Berita::factory()->published()->create()
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the berita is a draft.
     * Force status = 'draft' dengan published_at = null
     * 
     * Usage: Berita::factory()->draft()->create()
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the berita has high views.
     * Set views 500-5000 untuk simulate berita populer
     * 
     * Usage: Berita::factory()->popular()->create()
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => fake()->numberBetween(500, 5000),
        ]);
    }
}
