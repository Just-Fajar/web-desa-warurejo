<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublikasiResource extends JsonResource
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
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'tahun' => (int) $this->tahun,
            'deskripsi' => $this->deskripsi,
            'thumbnail' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
            'tanggal_publikasi' => $this->tanggal_publikasi ? \Carbon\Carbon::parse($this->tanggal_publikasi)->toIso8601String() : null,
            'status' => $this->status,
            'jumlah_download' => (int) $this->jumlah_download,
            'views' => (int) $this->views,
            'download_url' => route('api.publikasi.download', ['id' => $this->id]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
