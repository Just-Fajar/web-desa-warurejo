<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publikasi>
 */
class PublikasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = fake()->randomElement([
            'APBDes',
            'RPJMDes',
            'RKPDes',
        ]);

        $status = fake()->randomElement(['published', 'draft']);

        return [
            'judul' => fake()->sentence(5),
            'kategori' => $kategori,
            'tahun' => fake()->numberBetween(2015, date('Y')),
            'deskripsi' => fake()->paragraph(3),

            // Dummy file dokumen
            'file_dokumen' => 'publikasi/test-document.pdf',

            // Dummy thumbnail agar semua data punya gambar
            'thumbnail' => 'publikasi/thumbnails/dummy.jpg',

            'tanggal_publikasi' => $status === 'published'
                ? fake()->dateTimeBetween('-1 year', 'now')
                : null,

            'status' => $status,
            'jumlah_download' => fake()->numberBetween(0, 500),
            'views' => fake()->numberBetween(0, 2000),
        ];
    }

    /**
     * Indicate that the publikasi is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'tanggal_publikasi' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the publikasi is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'tanggal_publikasi' => null,
        ]);
    }

    /**
     * Indicate the publikasi category.
     */
    public function kategori(string $kategori): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Indicate a specific year.
     */
    public function tahun(int $tahun): static
    {
        return $this->state(fn (array $attributes) => [
            'tahun' => $tahun,
        ]);
    }
}
