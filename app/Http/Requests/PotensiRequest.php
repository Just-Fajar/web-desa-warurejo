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
        
        return [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:pertanian,peternakan,perikanan,umkm,wisata,kerajinan,lainnya',
            'deskripsi' => 'required|string',
            'gambar' => $potensiId 
                ? 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' 
                : 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'lokasi' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:20',
        ];
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
            'gambar' => 'Gambar',
            'lokasi' => 'Lokasi',
            'kontak' => 'Kontak',
            'whatsapp' => 'Nomor WhatsApp',
            'is_active' => 'Status Aktif',
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
            'gambar.required' => 'Gambar wajib diunggah',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'lokasi.max' => 'Lokasi maksimal 255 karakter',
            'kontak.max' => 'Kontak maksimal 100 karakter',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter',
        ];
    }
}
