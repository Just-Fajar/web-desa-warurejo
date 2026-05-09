<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBalasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth sudah dihandle middleware
    }

    public function rules(): array
    {
        return [
            'isi' => 'nullable|string',
            'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
            'alasan_penolakan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'isi.string' => 'Isi balasan harus berupa teks.',
            'status.required' => 'Status pengaduan wajib dipilih.',
            'status.in' => 'Status pengaduan tidak valid.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ];
    }
}
