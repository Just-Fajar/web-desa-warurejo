<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StrukturOrganisasiRequest extends FormRequest
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
        $rules = [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'level' => 'required|in:kepala,sekretaris,kaur,staff_kaur,kasi,staff_kasi',
            'atasan_id' => 'nullable|exists:struktur_organisasi,id',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ];

        // Validation for create
        if ($this->isMethod('post')) {
            $rules['foto'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048';
        }

        // Validation for update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['foto'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048';
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
            'nama' => 'Nama',
            'jabatan' => 'Jabatan',
            'foto' => 'Foto',
            'deskripsi' => 'Deskripsi',
            'level' => 'Level/Tingkat',
            'atasan_id' => 'Atasan',
            'urutan' => 'Urutan',
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
            'nama.required' => 'Nama wajib diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'jabatan.required' => 'Jabatan wajib diisi',
            'jabatan.max' => 'Jabatan maksimal 255 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, jpg, png, atau webp',
            'foto.max' => 'Ukuran foto maksimal 2MB',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'level.required' => 'Level wajib dipilih',
            'level.in' => 'Level tidak valid',
            'atasan_id.exists' => 'Atasan tidak ditemukan',
            'urutan.integer' => 'Urutan harus berupa angka',
            'urutan.min' => 'Urutan minimal 0',
            'is_active.required' => 'Status wajib dipilih',
            'is_active.boolean' => 'Status tidak valid',
        ];
    }
}
