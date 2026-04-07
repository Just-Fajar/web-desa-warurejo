<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilDesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Hanya 2 gambar yang bisa diupdate
            'gambar_header' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB untuk banner
            'struktur_organisasi' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB untuk struktur
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'gambar_header.image' => 'File gambar header harus berupa gambar',
            'gambar_header.mimes' => 'Format gambar header harus JPEG, JPG, PNG, atau WEBP',
            'gambar_header.max' => 'Ukuran gambar header maksimal 5MB',
            
            'struktur_organisasi.image' => 'File struktur organisasi harus berupa gambar',
            'struktur_organisasi.mimes' => 'Format struktur organisasi harus JPEG, JPG, PNG, atau WEBP',
            'struktur_organisasi.max' => 'Ukuran struktur organisasi maksimal 5MB',
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
            'gambar_header' => 'gambar header',
            'struktur_organisasi' => 'struktur organisasi',
        ];
    }
}
