<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:berita,slug,'.$this->route('beritum'),
            'ringkasan' => 'nullable|string|max:500',
            'konten' => 'required|string',
            'status' => 'required|in:draft,scheduled,published',
            'published_at' => 'nullable|date',
        ];

        // Jika status scheduled, published_at wajib dan harus di masa depan
        if ($this->input('status') === 'scheduled') {
            $rules['published_at'] = 'required|date|after:now';
        }

        // Validation for create
        if ($this->isMethod('post')) {
            $rules['gambar_utama'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048';
        }

        // Validation for update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['gambar_utama'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan. Silakan gunakan slug lain.',
            'konten.required' => 'Konten berita wajib diisi.',
            'status.required' => 'Status berita wajib dipilih.',
            'status.in' => 'Status berita tidak valid. Pilih: Draft, Dijadwalkan, atau Published.',
            'published_at.required' => 'Tanggal publikasi wajib diisi untuk konten yang dijadwalkan.',
            'published_at.after' => 'Tanggal publikasi harus di masa depan.',
            'gambar_utama.image' => 'File harus berupa gambar.',
            'gambar_utama.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'gambar_utama.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug from judul if not provided
        if (empty($this->slug) && ! empty($this->judul)) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->judul),
            ]);
        }

        // Jika status draft, clear published_at
        if ($this->input('status') === 'draft') {
            $this->merge(['published_at' => null]);
        }
    }
}
