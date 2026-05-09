<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengaduanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pengaduan publik, tidak perlu auth
    }

    public function rules(): array
    {
        return [
            'nama_pelapor' => 'required|string|max:100',
            'nomor_wa' => 'required|string|max:20',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'lokasi_kejadian' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
            'nama_pelapor.max' => 'Nama pelapor maksimal 100 karakter.',
            'nomor_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_wa.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'judul.required' => 'Judul pengaduan wajib diisi.',
            'judul.max' => 'Judul pengaduan maksimal 255 karakter.',
            'isi.required' => 'Isi pengaduan wajib diisi.',
            'lokasi_kejadian.required' => 'Lokasi kejadian wajib diisi.',
            'lokasi_kejadian.max' => 'Lokasi kejadian maksimal 255 karakter.',
            'lampiran.file' => 'Lampiran harus berupa file.',
            'lampiran.mimes' => 'Format lampiran harus JPG, JPEG, PNG, atau PDF.',
            'lampiran.max' => 'Ukuran lampiran maksimal 5MB.',
        ];
    }
}
