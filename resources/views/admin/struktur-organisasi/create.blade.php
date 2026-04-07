{{--
    ADMIN STRUKTUR ORGANISASI CREATE
    
    Form tambah anggota struktur organisasi desa
    
    FEATURES:
    - Photo upload dengan crop
    - Jabatan & periode selection
    - Hierarki level (1-5)
    - Contact info (telepon, email)
    - Order priority
    - Active status toggle
    
    FORM FIELDS:
    - nama: Nama lengkap anggota (required)
    - jabatan: Jabatan (Kepala Desa, Sekretaris, dll) (required)
    - foto: Photo upload (max 2MB, JPG/PNG)
    - nip: Nomor Induk Pegawai (optional)
    - periode_mulai: Start date periode jabatan
    - periode_selesai: End date periode (optional, ongoing if null)
    - telepon: Nomor telepon (optional)
    - email: Email address (optional, format validated)
    - level: Hierarki level 1-5 (1=top, 5=bottom)
    - urutan: Display order dalam level
    - is_active: Boolean toggle (default true)
    
    VALIDATION:
    - Nama required, max 255 chars
    - Jabatan required, max 255 chars
    - Foto max 2MB, image format
    - Email format validation
    - Level must be 1-5
    - Periode selesai >= periode mulai
    
    JAVASCRIPT:
    - Image crop/preview
    - Date picker (periode)
    - Email validation
    - Form validation
    
    HIERARKI EXPLANATION:
    Level 1: Kepala Desa
    Level 2: Sekretaris, Kaur
    Level 3: Kasi
    Level 4: Kepala Dusun
    Level 5: Staff
    
    Route: POST /admin/struktur-organisasi/store
    Controller: StrukturOrganisasiController@store
--}}
@extends('admin.layouts.app')

@section('title', 'Tambah Anggota Struktur Organisasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Anggota Baru</h1>
            <p class="text-sm text-gray-600 mt-1">Pilih template dan isi data anggota</p>
        </div>
        <a href="{{ route('admin.struktur-organisasi.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left: Form Input -->
        <div class="bg-white rounded-lg shadow order-2 lg:order-1">
            <form action="{{ route('admin.struktur-organisasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Pilih Template/Posisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Posisi/Template <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($levels as $key => $label)
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition template-option {{ old('level') == $key ? 'border-primary-600 bg-primary-50' : 'border-gray-300' }}">
                                <input type="radio" name="level" value="{{ $key }}" {{ old('level') == $key ? 'checked' : '' }} required
                                       class="text-primary-600 focus:ring-primary-500" onchange="updatePreview()">
                                <span class="ml-3 font-medium text-gray-800">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Contoh: ALBERTO" required oninput="updatePreview()">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Contoh: Kepala Desa Warurejo" required oninput="updatePreview()">
                        @error('jabatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Foto Profil <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               onchange="previewPhoto()">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Maks: 2MB</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Atasan (Optional untuk staff) -->
                    <div id="atasan-field" class="hidden">
                        <label for="atasan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Atasan (Opsional)
                        </label>
                        <select name="atasan_id" id="atasan_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Tidak Ada Atasan --</option>
                            @foreach($potentialAtasan as $atasan)
                                <option value="{{ $atasan->id }}" {{ old('atasan_id') == $atasan->id ? 'selected' : '' }}>
                                    {{ $atasan->nama }} - {{ $atasan->jabatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('atasan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Deskripsi singkat tentang anggota" oninput="updatePreview()">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="hidden" name="urutan" value="{{ old('urutan', 0) }}">
                    <input type="hidden" name="is_active" value="1">
                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.struktur-organisasi.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Live Preview -->
        <div class="bg-white rounded-lg shadow p-6 order-1 lg:order-2">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Preview Tampilan
            </h2>
            
            <!-- Preview Container -->
            <div id="preview-kepala" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-center">
                        <h2 class="text-xl font-bold text-white mb-1">KEPALA DESA</h2>
                        <p class="text-blue-100 text-sm">Pemimpin Pemerintahan Desa</p>
                    </div>
                    <div class="p-6 text-center">
                        <div class="inline-block">
                            <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden bg-gray-200">
                                <img id="preview-photo-kepala" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1" id="preview-nama-kepala">NAMA LENGKAP</h3>
                            <p class="text-gray-600 text-sm" id="preview-jabatan-kepala">Jabatan</p>
                            <p class="text-gray-500 text-xs mt-2" id="preview-desc-kepala"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="preview-sekretaris" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-3 text-center">
                        <h3 class="text-lg font-bold text-white">SEKRETARIS DESA</h3>
                    </div>
                    <div class="p-4 text-center">
                        <div class="w-24 h-24 mx-auto mb-2 rounded-full overflow-hidden bg-gray-200">
                            <img id="preview-photo-sekretaris" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-lg font-bold text-gray-800" id="preview-nama-sekretaris">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm" id="preview-jabatan-sekretaris">Sekretaris Desa</p>
                        <p class="text-gray-500 text-xs mt-2" id="preview-desc-sekretaris"></p>
                    </div>
                </div>
            </div>

            <div id="preview-kaur" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-4">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-yellow-100 bg-gray-200">
                            <img id="preview-photo-kaur" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-base" id="preview-nama-kaur">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm mb-2" id="preview-jabatan-kaur">Kepala Urusan</p>
                        <p class="text-gray-500 text-xs mb-2" id="preview-desc-kaur"></p>
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Kepala Urusan</span>
                    </div>
                </div>
            </div>

            <div id="preview-staff_kaur" class="preview-template hidden">
                <div class="bg-gradient-to-br from-yellow-50 to-white rounded-lg shadow-md hover:shadow-xl transition p-4 border-l-4 border-yellow-500">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden ring-2 ring-yellow-200 bg-gray-200">
                            <img id="preview-photo-staff_kaur" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-sm" id="preview-nama-staff_kaur">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-xs mb-1" id="preview-jabatan-staff_kaur">Staff</p>
                        <p class="text-yellow-700 text-xs font-medium">dibawah Kaur</p>
                        <p class="text-gray-500 text-xs mt-1" id="preview-desc-staff_kaur"></p>
                    </div>
                </div>
            </div>

            <div id="preview-kasi" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-4">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-blue-100 bg-gray-200">
                            <img id="preview-photo-kasi" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-base" id="preview-nama-kasi">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm mb-2" id="preview-jabatan-kasi">Kepala Seksi</p>
                        <p class="text-gray-500 text-xs mb-2" id="preview-desc-kasi"></p>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Kepala Seksi</span>
                    </div>
                </div>
            </div>

            <div id="preview-staff_kasi" class="preview-template hidden">
                <div class="bg-gradient-to-br from-green-50 to-white rounded-lg shadow-md hover:shadow-xl transition p-4 border-l-4 border-green-500">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden ring-2 ring-green-200 bg-gray-200">
                            <img id="preview-photo-staff_kasi" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-sm" id="preview-nama-staff_kasi">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-xs mb-1" id="preview-jabatan-staff_kasi">Staff</p>
                        <p class="text-green-700 text-xs font-medium">dibawah Kasi</p>
                        <p class="text-gray-500 text-xs mt-1" id="preview-desc-staff_kasi"></p>
                    </div>
                </div>
            </div>

            <div id="preview-placeholder" class="text-center py-12 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <p>Pilih posisi dan isi data untuk melihat preview</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPhoto = '/images/default-avatar.png';

function previewPhoto() {
    const file = document.getElementById('foto').files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            currentPhoto = e.target.result;
            updatePreview();
        };
        reader.readAsDataURL(file);
    }
}

function updatePreview() {
    const level = document.querySelector('input[name="level"]:checked')?.value;
    const nama = document.getElementById('nama').value.toUpperCase() || 'NAMA LENGKAP';
    const jabatan = document.getElementById('jabatan').value || 'Jabatan';
    const deskripsi = document.getElementById('deskripsi').value;
    
    // Hide all previews
    document.querySelectorAll('.preview-template').forEach(el => el.classList.add('hidden'));
    document.getElementById('preview-placeholder').classList.add('hidden');
    
    // Show/hide atasan field
    const atasanField = document.getElementById('atasan-field');
    if (level === 'staff_kaur' || level === 'staff_kasi') {
        atasanField.classList.remove('hidden');
    } else {
        atasanField.classList.add('hidden');
    }
    
    if (level) {
        const previewEl = document.getElementById('preview-' + level);
        if (previewEl) {
            previewEl.classList.remove('hidden');
            
            // Update preview content
            const photoEl = document.getElementById('preview-photo-' + level);
            const namaEl = document.getElementById('preview-nama-' + level);
            const jabatanEl = document.getElementById('preview-jabatan-' + level);
            const descEl = document.getElementById('preview-desc-' + level);
            
            if (photoEl) photoEl.src = currentPhoto;
            if (namaEl) namaEl.textContent = nama;
            if (jabatanEl) jabatanEl.textContent = jabatan;
            if (descEl) {
                descEl.textContent = deskripsi;
                descEl.style.display = deskripsi ? 'block' : 'none';
            }
        }
    } else {
        document.getElementById('preview-placeholder').classList.remove('hidden');
    }
}

// Update template option styling
document.querySelectorAll('input[name="level"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.template-option').forEach(opt => {
            opt.classList.remove('border-primary-600', 'bg-primary-50');
            opt.classList.add('border-gray-300');
        });
        if (this.checked) {
            this.closest('.template-option').classList.remove('border-gray-300');
            this.closest('.template-option').classList.add('border-primary-600', 'bg-primary-50');
        }
    });
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
@endsection
