<?php

namespace Database\Factories;

use App\Models\PotensiDesa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PotensiDesa>
 */
class PotensiDesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->words(3, true);
        $kategori = fake()->randomElement([
            PotensiDesa::KATEGORI_PERTANIAN,
            PotensiDesa::KATEGORI_PETERNAKAN,
            PotensiDesa::KATEGORI_PERIKANAN,
            PotensiDesa::KATEGORI_UMKM,
            PotensiDesa::KATEGORI_WISATA,
            PotensiDesa::KATEGORI_LAINNYA,
        ]);

        return [
            'nama' => ucwords($nama),
            'slug' => Str::slug($nama).'-'.fake()->unique()->numberBetween(1, 1000),
            'kategori' => $kategori,
            'deskripsi' => fake()->paragraphs(3, true),
            'gambar' => 'potensi/test-image.jpg',
            'lokasi' => fake()->address(),
            'whatsapp' => '8'.fake()->numerify('##########'),
            'nama_pengelola' => fake()->name(),
            'info_utama' => fake()->sentence(4),
            'status' => 'published',
            'published_at' => now(),
            'urutan' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the potensi is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the potensi is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate the potensi category.
     */
    public function kategori(string $kategori): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => $kategori,
        ]);
    }
}
