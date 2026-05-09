<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PotensiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $potensiId = $this->route('potensi') ? $this->route('potensi')->id : null;

        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:pertanian,peternakan,perikanan,umkm,wisata,lainnya',
            'deskripsi' => 'required|string',
            'gambar' => $potensiId
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
                : 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'lokasi' => 'nullable|string|max:255',
            'info_utama' => 'nullable|string|max:255',
            'nama_pengelola' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'link_maps' => 'nullable|url|max:500',
            'status' => 'required|in:draft,scheduled,published',
            'published_at' => 'nullable|date',
            'foto_galeri' => 'nullable|array',
            'foto_galeri.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        // Jika status scheduled, published_at wajib dan harus di masa depan
        if ($this->input('status') === 'scheduled') {
            $rules['published_at'] = 'required|date|after:now';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'Nama Potensi',
            'kategori' => 'Kategori',
            'deskripsi' => 'Deskripsi',
            'gambar' => 'Foto Utama',
            'lokasi' => 'Lokasi',
            'info_utama' => 'Info Utama',
            'nama_pengelola' => 'Nama Pengelola',
            'whatsapp' => 'Nomor WhatsApp',
            'link_maps' => 'Link Google Maps',
            'status' => 'Status',
            'published_at' => 'Tanggal Publikasi',
            'foto_galeri' => 'Foto Galeri',
            'foto_galeri.*' => 'Foto Galeri',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama potensi wajib diisi',
            'nama.max' => 'Nama potensi maksimal 255 karakter',
            'kategori.required' => 'Kategori wajib dipilih',
            'kategori.in' => 'Kategori tidak valid',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'gambar.required' => 'Foto utama wajib diunggah',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'nama_pengelola.required' => 'Nama pengelola wajib diisi',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter',
            'link_maps.url' => 'Link Maps harus berupa URL yang valid',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid. Pilih: Draft, Dijadwalkan, atau Published.',
            'published_at.required' => 'Tanggal publikasi wajib diisi untuk konten yang dijadwalkan.',
            'published_at.after' => 'Tanggal publikasi harus di masa depan.',
            'foto_galeri.*.image' => 'Setiap file galeri harus berupa gambar',
            'foto_galeri.*.mimes' => 'Format foto galeri harus jpeg, png, jpg, atau webp',
            'foto_galeri.*.max' => 'Ukuran foto galeri maksimal 2MB per file',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Jika status draft, clear published_at
        if ($this->input('status') === 'draft') {
            $this->merge(['published_at' => null]);
        }
    }
}
