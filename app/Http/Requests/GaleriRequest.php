<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GaleriRequest extends FormRequest
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
        $galeriId = $this->route('galeri') ? $this->route('galeri')->id : null;
        
        $rules = [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:kegiatan,infrastruktur,budaya,umkm,lainnya',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'is_active' => 'required|boolean',
        ];

        // Multiple images validation
        if ($galeriId) {
            // Update: images optional
            $rules['images'] = 'nullable|array|min:1';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            // Create: images required
            $rules['images'] = 'required|array|min:1';
            $rules['images.*'] = 'image|mimes:jpeg,png,jpg,webp|max:2048';
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
            'judul' => 'Judul',
            'images' => 'Gambar',
            'images.*' => 'Gambar',
            'kategori' => 'Kategori',
            'deskripsi' => 'Deskripsi',
            'tanggal' => 'Tanggal',
            'is_active' => 'Status',
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
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'images.required' => 'Minimal upload 1 gambar',
            'images.array' => 'Format gambar tidak valid',
            'images.min' => 'Minimal upload 1 gambar',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
            'kategori.required' => 'Kategori wajib dipilih',
            'kategori.in' => 'Kategori tidak valid',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'is_active.required' => 'Status wajib dipilih',
            'is_active.boolean' => 'Status tidak valid',
        ];
    }
}
