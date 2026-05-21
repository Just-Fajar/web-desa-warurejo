<?php

namespace Database\Factories;

use App\Models\Pengaduan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengaduanFactory extends Factory
{
    protected $model = Pengaduan::class;

    public function definition(): array
    {
        return [
            'nama_pelapor' => fake()->name(),
            'nomor_wa' => fake()->numerify('08##########'),
            'judul' => fake()->sentence(5),
            'isi' => fake()->paragraph(3),
            'lokasi_kejadian' => fake()->address(),
            'lampiran' => null,
            'status' => Pengaduan::STATUS_MENUNGGU,
            'alasan_penolakan' => null,
        ];
    }

    public function menunggu(): static
    {
        return $this->state(fn () => ['status' => Pengaduan::STATUS_MENUNGGU]);
    }

    public function diproses(): static
    {
        return $this->state(fn () => ['status' => Pengaduan::STATUS_DIPROSES]);
    }

    public function selesai(): static
    {
        return $this->state(fn () => ['status' => Pengaduan::STATUS_SELESAI]);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => [
            'status' => Pengaduan::STATUS_DITOLAK,
            'alasan_penolakan' => fake()->sentence(),
        ]);
    }

    public function withLampiran(): static
    {
        return $this->state(fn () => ['lampiran' => 'pengaduan/'.fake()->uuid().'.jpg']);
    }
}
