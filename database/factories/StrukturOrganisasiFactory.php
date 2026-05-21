<?php

namespace Database\Factories;

use App\Models\StrukturOrganisasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class StrukturOrganisasiFactory extends Factory
{
    protected $model = StrukturOrganisasi::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'jabatan' => fake()->jobTitle(),
            'foto' => null,
            'deskripsi' => fake()->sentence(),
            'urutan' => fake()->numberBetween(1, 20),
            'level' => fake()->randomElement([
                StrukturOrganisasi::LEVEL_KEPALA,
                StrukturOrganisasi::LEVEL_SEKRETARIS,
                StrukturOrganisasi::LEVEL_KAUR,
                StrukturOrganisasi::LEVEL_KASI,
            ]),
            'atasan_id' => null,
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function kepala(): static
    {
        return $this->state(fn () => [
            'level' => StrukturOrganisasi::LEVEL_KEPALA,
            'jabatan' => 'Kepala Desa',
        ]);
    }

    public function sekretaris(): static
    {
        return $this->state(fn () => [
            'level' => StrukturOrganisasi::LEVEL_SEKRETARIS,
            'jabatan' => 'Sekretaris Desa',
        ]);
    }

    public function kaur(): static
    {
        return $this->state(fn () => [
            'level' => StrukturOrganisasi::LEVEL_KAUR,
            'jabatan' => 'Kepala Urusan',
        ]);
    }

    public function kasi(): static
    {
        return $this->state(fn () => [
            'level' => StrukturOrganisasi::LEVEL_KASI,
            'jabatan' => 'Kepala Seksi',
        ]);
    }
}
