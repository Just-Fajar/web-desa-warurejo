<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Galeri;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Galeri>
 */
class GaleriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = fake()->randomElement([
            Galeri::KATEGORI_KEGIATAN,
            Galeri::KATEGORI_PEMBANGUNAN,
            Galeri::KATEGORI_BUDAYA,
            Galeri::KATEGORI_KEAGAMAAN,
            Galeri::KATEGORI_SOSIAL,
            Galeri::KATEGORI_LAINNYA,
        ]);

        return [
            'admin_id' => Admin::factory(),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(2),
            'gambar' => 'galeri/test-image.jpg',
            'kategori' => $kategori,
            'tanggal' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => 'published',
            'published_at' => now(),
        ];
    }

    /**
     * Indicate that the galeri is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the galeri is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate the galeri category.
     */
    public function kategori(string $kategori): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Indicate a recent galeri item.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}
