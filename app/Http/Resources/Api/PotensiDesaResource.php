<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PotensiDesaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'deskripsi' => strip_tags($this->deskripsi),
            'deskripsi_html' => $this->deskripsi,
            'gambar' => $this->gambar_url,
            'views' => (int) $this->views,
            'kategori' => $this->kategori,
            'lokasi' => $this->lokasi,
            'link_maps' => $this->link_maps,
            'info_utama' => $this->info_utama,
            'nama_pengelola' => $this->nama_pengelola,
            'whatsapp' => $this->whatsapp,
            'whatsapp_link' => $this->whatsapp_link,
            'whatsapp_formatted' => $this->whatsapp_formatted,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
