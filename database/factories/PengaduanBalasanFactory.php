<?php

namespace Database\Factories;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengaduanBalasanFactory extends Factory
{
    protected $model = PengaduanBalasan::class;

    public function definition(): array
    {
        return [
            'pengaduan_id' => Pengaduan::factory(),
            'isi' => fake()->paragraph(),
            'is_admin' => true,
            'lampiran' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['is_admin' => true]);
    }

    public function withLampiran(): static
    {
        return $this->state(fn () => ['lampiran' => 'pengaduan/balasan/'.fake()->uuid().'.jpg']);
    }
}
