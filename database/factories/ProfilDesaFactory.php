<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfilDesa>
 */
class ProfilDesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_desa' => 'Desa ' . fake()->city(),
            'kecamatan' => 'Kecamatan ' . fake()->city(),
            'kabupaten' => 'Kabupaten ' . fake()->city(),
            'provinsi' => fake()->state(),
            'kode_pos' => fake()->postcode(),
            'email' => fake()->safeEmail(),
            'telepon' => fake()->phoneNumber(),
            'alamat_lengkap' => fake()->address(),
            'visi' => fake()->sentence(10),
            'misi' => implode("\n", fake()->sentences(5)),
            'sejarah' => fake()->paragraphs(3, true),
            'logo' => null,
            'gambar_kantor' => null,
            'latitude' => fake()->latitude(-8.0, -7.0),
            'longitude' => fake()->longitude(111.0, 112.0),
            'luas_wilayah' => fake()->randomFloat(2, 100, 1000),
            'jumlah_penduduk' => fake()->numberBetween(1000, 10000),
            'jumlah_kk' => fake()->numberBetween(300, 3000),
            'batas_utara' => 'Sebelah Utara: ' . fake()->words(3, true),
            'batas_selatan' => 'Sebelah Selatan: ' . fake()->words(3, true),
            'batas_timur' => 'Sebelah Timur: ' . fake()->words(3, true),
            'batas_barat' => 'Sebelah Barat: ' . fake()->words(3, true),
        ];
    }

    /**
     * Indicate that this is Desa Warurejo.
     */
    public function warurejo(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_desa' => 'Desa Warurejo',
            'kecamatan' => 'Balerejo',
            'kabupaten' => 'Madiun',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '63172',
            'alamat_lengkap' => 'Jl. Raya Balerejo No.1, Warurejo, Kecamatan Balerejo, Kabupaten Madiun, Jawa Timur',
        ]);
    }
}
