<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GaleriResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'tanggal' => $this->tanggal ? $this->tanggal->toIso8601String() : null,
            'views' => (int) $this->views,
            'images' => $this->images->sortBy('urutan')->map(function ($image) {
                return [
                    'id' => $image->id,
                    'path' => $image->image_url,
                    'raw_path' => $image->image_path,
                    'urutan' => (int) $image->urutan,
                ];
            })->values(),
            'thumbnail' => $this->gambar_url,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
