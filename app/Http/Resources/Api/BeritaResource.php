<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class BeritaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?? Str::limit(strip_tags($this->konten), 150),
            'konten' => strip_tags($this->konten),
            'konten_html' => $this->konten,
            'gambar' => $this->gambar ? asset('storage/' . $this->gambar) : null,
            'views' => (int) $this->views,
            'published_at' => $this->published_at ? $this->published_at->toIso8601String() : null,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
            'author' => new AdminPublicResource($this->whenLoaded('admin')),
        ];
    }
}
