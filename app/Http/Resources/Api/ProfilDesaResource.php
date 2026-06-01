<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilDesaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sejarah' => $this->sejarah,
            'visi' => $this->visi,
            'misi' => $this->misi,
            'luas_wilayah' => (float) $this->luas_wilayah,
            'jumlah_penduduk' => (int) $this->jumlah_penduduk,
            'jumlah_kk' => (int) $this->jumlah_kk,
            'peta_wilayah' => $this->peta_wilayah,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
