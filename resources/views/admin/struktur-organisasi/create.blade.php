@extends('admin.layouts.app')

@section('title', 'Tambah Anggota Struktur Organisasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Anggota Baru</h1>
            <p class="text-sm text-gray-600 mt-1">Pilih posisi dan isi data anggota</p>
        </div>
        <a href="{{ route('admin.struktur-organisasi.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left: Form Input -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden order-2 lg:order-1">
            <form action="{{ route('admin.struktur-organisasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Pilih Template/Posisi -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Pilih Posisi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($levels as $key => $label)
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition template-option {{ old('level') == $key ? 'border-primary-600 bg-primary-50' : 'border-gray-300' }}">
                                <input type="radio" name="level" value="{{ $key }}" {{ old('level') == $key ? 'checked' : '' }} required
                                       class="text-primary-600 focus:ring-primary-500">
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
                        <label for="nama" class="block text-sm font-bold text-gray-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                               class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('nama') border-red-300 ring-red-100 @enderror"
                               placeholder="Contoh: ALBERTO" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-bold text-gray-900 mb-2">
                            Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                               class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('jabatan') border-red-300 ring-red-100 @enderror"
                               placeholder="Contoh: Kepala Desa Warurejo" required>
                        @error('jabatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div>
                        <label for="foto" class="block text-sm font-bold text-gray-900 mb-2">
                            Upload Foto Profil <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" required
                               class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('foto') border-red-300 ring-red-100 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Maks: 2MB</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Atasan (Optional untuk staff) -->
                    <div id="atasan-field" class="hidden">
                        <label for="atasan_id" class="block text-sm font-bold text-gray-900 mb-2">
                            Pilih Atasan (Opsional)
                        </label>
                        <select name="atasan_id" id="atasan_id"
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('atasan_id') border-red-300 ring-red-100 @enderror">
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

                    <!-- Periode Jabatan (Optional) -->
                    <div>
                        <label for="periode_jabatan" class="block text-sm font-bold text-gray-900 mb-2">
                            Periode Jabatan (Opsional)
                        </label>
                        <input type="text" name="periode_jabatan" id="periode_jabatan" value="{{ old('periode_jabatan') }}"
                               class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('periode_jabatan') border-red-300 ring-red-100 @enderror"
                               placeholder="Contoh: 2020 - 2028">
                        @error('periode_jabatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor WhatsApp (Optional) -->
                    <div>
                        <label for="whatsapp" class="block text-sm font-bold text-gray-900 mb-2">
                            Nomor WhatsApp (Opsional)
                        </label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                               class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('whatsapp') border-red-300 ring-red-100 @enderror"
                               placeholder="Contoh: 08123456789">
                        @error('whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="2"
                                  class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror"
                                  placeholder="Deskripsi singkat tentang anggota">{{ old('deskripsi') }}</textarea>
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
        <div class="order-1 lg:order-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sticky top-6">
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
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-4 text-center">
                        <h2 class="text-xl font-bold text-white mb-1">KEPALA DESA</h2>
                        <p class="text-indigo-100 text-sm">Pemimpin Pemerintahan Desa</p>
                    </div>
                    <div class="p-6 text-center">
                        <div class="inline-block w-full">
                            <div class="w-28 h-28 mx-auto mb-3 rounded-full overflow-hidden bg-gray-200">
                                <img id="preview-photo-kepala" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1" id="preview-nama-kepala">NAMA LENGKAP</h3>
                            <p class="text-gray-600 text-sm" id="preview-jabatan-kepala">Jabatan</p>
                            <p class="text-indigo-600 text-[11px] font-semibold mt-1 hidden" id="preview-periode-kepala"></p>
                            <div id="preview-wa-container-kepala" class="mt-2 hidden">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-kepala"></span>
                                </span>
                            </div>
                            <p class="text-gray-500 text-xs mt-2" id="preview-desc-kepala"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="preview-sekretaris" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-3 text-center">
                        <h3 class="text-lg font-bold text-white">SEKRETARIS DESA</h3>
                    </div>
                    <div class="p-4 text-center">
                        <div class="w-24 h-24 mx-auto mb-2 rounded-full overflow-hidden bg-gray-200">
                            <img id="preview-photo-sekretaris" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-lg font-bold text-gray-800" id="preview-nama-sekretaris">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm" id="preview-jabatan-sekretaris">Sekretaris Desa</p>
                        <p class="text-emerald-600 text-[11px] font-semibold mt-1 hidden" id="preview-periode-sekretaris"></p>
                        <div id="preview-wa-container-sekretaris" class="mt-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-sekretaris"></span>
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mt-2" id="preview-desc-sekretaris"></p>
                    </div>
                </div>
            </div>

            <div id="preview-kaur" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-4">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-amber-100 bg-gray-200">
                            <img id="preview-photo-kaur" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-base" id="preview-nama-kaur">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm mb-1" id="preview-jabatan-kaur">Kepala Urusan</p>
                        <p class="text-amber-700 text-[11px] font-semibold mb-1 hidden" id="preview-periode-kaur"></p>
                        <div id="preview-wa-container-kaur" class="mb-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-kaur"></span>
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2" id="preview-desc-kaur"></p>
                        <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">Kepala Urusan</span>
                    </div>
                </div>
            </div>

            <div id="preview-staff_kaur" class="preview-template hidden">
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-lg shadow-md hover:shadow-xl transition p-4 border-l-4 border-orange-500">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden ring-2 ring-orange-200 bg-gray-200">
                            <img id="preview-photo-staff_kaur" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-sm" id="preview-nama-staff_kaur">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-xs mb-1" id="preview-jabatan-staff_kaur">Staff</p>
                        <p class="text-orange-700 text-xs font-medium mb-1">dibawah <span id="preview-atasan-staff_kaur">Kaur</span></p>
                        <p class="text-orange-600 text-[11px] font-semibold mb-1 hidden" id="preview-periode-staff_kaur"></p>
                        <div id="preview-wa-container-staff_kaur" class="mb-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-staff_kaur"></span>
                            </span>
                        </div>
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
                        <p class="text-gray-600 text-sm mb-1" id="preview-jabatan-kasi">Kepala Seksi</p>
                        <p class="text-blue-700 text-[11px] font-semibold mb-1 hidden" id="preview-periode-kasi"></p>
                        <div id="preview-wa-container-kasi" class="mb-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-kasi"></span>
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2" id="preview-desc-kasi"></p>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Kepala Seksi</span>
                    </div>
                </div>
            </div>

            <div id="preview-staff_kasi" class="preview-template hidden">
                <div class="bg-gradient-to-br from-teal-50 to-white rounded-lg shadow-md hover:shadow-xl transition p-4 border-l-4 border-teal-500">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden ring-2 ring-teal-200 bg-gray-200">
                            <img id="preview-photo-staff_kasi" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-sm" id="preview-nama-staff_kasi">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-xs mb-1" id="preview-jabatan-staff_kasi">Staff</p>
                        <p class="text-teal-700 text-xs font-medium mb-1">dibawah <span id="preview-atasan-staff_kasi">Kasi</span></p>
                        <p class="text-teal-600 text-[11px] font-semibold mb-1 hidden" id="preview-periode-staff_kasi"></p>
                        <div id="preview-wa-container-staff_kasi" class="mb-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-staff_kasi"></span>
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mt-1" id="preview-desc-staff_kasi"></p>
                    </div>
                </div>
            </div>

            <div id="preview-kadus" class="preview-template hidden">
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-4">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-purple-100 bg-gray-200">
                            <img id="preview-photo-kadus" src="/images/default-avatar.png" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1 text-base" id="preview-nama-kadus">NAMA LENGKAP</h4>
                        <p class="text-gray-600 text-sm mb-1" id="preview-jabatan-kadus">Kepala Dusun</p>
                        <p class="text-purple-700 text-[11px] font-semibold mb-1 hidden" id="preview-periode-kadus"></p>
                        <div id="preview-wa-container-kadus" class="mb-2 hidden">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> <span id="preview-wa-kadus"></span>
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mb-2" id="preview-desc-kadus"></p>
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Kepala Dusun</span>
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
</div>

@push('scripts')
<script @nonce>
document.addEventListener('DOMContentLoaded', function() {
    let currentPhoto = '/images/default-avatar.png';

    const fotoInput = document.getElementById('foto');
    const namaInput = document.getElementById('nama');
    const jabatanInput = document.getElementById('jabatan');
    const deskripsiInput = document.getElementById('deskripsi');
    const levelRadios = document.querySelectorAll('input[name="level"]');
    const periodeJabatanInput = document.getElementById('periode_jabatan');
    const whatsappInput = document.getElementById('whatsapp');

    function previewPhoto() {
        const file = fotoInput.files[0];
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
        const levelRadioChecked = document.querySelector('input[name="level"]:checked');
        const level = levelRadioChecked ? levelRadioChecked.value : null;
        const nama = (namaInput ? namaInput.value.toUpperCase() : '') || 'NAMA LENGKAP';
        const jabatan = (jabatanInput ? jabatanInput.value : '') || 'Jabatan';
        const deskripsi = deskripsiInput ? deskripsiInput.value : '';
        const periode = periodeJabatanInput ? periodeJabatanInput.value : '';
        const wa = whatsappInput ? whatsappInput.value : '';
        
        // Hide all previews
        document.querySelectorAll('.preview-template').forEach(el => el.classList.add('hidden'));
        document.getElementById('preview-placeholder').classList.add('hidden');
        
        // Show/hide atasan field
        const atasanField = document.getElementById('atasan-field');
        if (atasanField) {
            if (level === 'staff_kaur' || level === 'staff_kasi') {
                atasanField.classList.remove('hidden');
            } else {
                atasanField.classList.add('hidden');
            }
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
                const periodeEl = document.getElementById('preview-periode-' + level);
                const waContainerEl = document.getElementById('preview-wa-container-' + level);
                const waEl = document.getElementById('preview-wa-' + level);
                
                if (photoEl) photoEl.src = currentPhoto;
                if (namaEl) namaEl.textContent = nama;
                if (jabatanEl) jabatanEl.textContent = jabatan;
                if (descEl) {
                    descEl.textContent = deskripsi;
                    descEl.style.display = deskripsi ? 'block' : 'none';
                }
                
                if (periodeEl) {
                    if (periode) {
                        periodeEl.textContent = 'Periode: ' + periode;
                        periodeEl.classList.remove('hidden');
                    } else {
                        periodeEl.classList.add('hidden');
                    }
                }
                
                if (waContainerEl && waEl) {
                    if (wa) {
                        waEl.textContent = wa;
                        waContainerEl.classList.remove('hidden');
                    } else {
                        waContainerEl.classList.add('hidden');
                    }
                }

                // Update dynamic atasan
                if (level === 'staff_kaur' || level === 'staff_kasi') {
                    const atasanInput = document.getElementById('atasan_id');
                    let atasanName = '';
                    if (atasanInput && atasanInput.selectedIndex >= 0) {
                        const selectedOption = atasanInput.options[atasanInput.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            atasanName = selectedOption.text.trim();
                        }
                    }
                    const atasanEl = document.getElementById('preview-atasan-' + level);
                    if (atasanEl) {
                        atasanEl.textContent = atasanName || (level === 'staff_kaur' ? 'Kaur' : 'Kasi');
                    }
                }
            }
        } else {
            document.getElementById('preview-placeholder').classList.remove('hidden');
        }
    }

    // Attach listeners
    if (fotoInput) {
        fotoInput.addEventListener('change', previewPhoto);
    }
    if (namaInput) {
        namaInput.addEventListener('input', updatePreview);
    }
    if (jabatanInput) {
        jabatanInput.addEventListener('input', updatePreview);
    }
    if (deskripsiInput) {
        deskripsiInput.addEventListener('input', updatePreview);
    }
    if (periodeJabatanInput) {
        periodeJabatanInput.addEventListener('input', updatePreview);
    }
    if (whatsappInput) {
        whatsappInput.addEventListener('input', updatePreview);
    }
    const atasanInput = document.getElementById('atasan_id');
    if (atasanInput) {
        atasanInput.addEventListener('change', updatePreview);
    }
    levelRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.template-option').forEach(opt => {
                opt.classList.remove('border-primary-600', 'bg-primary-50');
                opt.classList.add('border-gray-300');
            });
            if (this.checked) {
                this.closest('.template-option').classList.remove('border-gray-300');
                this.closest('.template-option').classList.add('border-primary-600', 'bg-primary-50');
            }
            updatePreview();
        });
    });

    // Initialize on page load
    updatePreview();
});
</script>
@endpush
@endsection
