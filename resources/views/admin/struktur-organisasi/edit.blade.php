@extends('admin.layouts.app')

@section('title', 'Edit Anggota Struktur Organisasi')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Anggota</h1>
            <p class="text-sm text-gray-600 mt-1">Perbarui data anggota struktur organisasi</p>
        </div>
        <a href="{{ route('admin.struktur-organisasi.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.struktur-organisasi.update', $strukturOrganisasi->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                <!-- Level -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Level / Posisi <span class="text-red-500">*</span>
                    </label>

                    <select name="level"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                   focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Level --</option>
                        @foreach($levels as $key => $label)
                            <option value="{{ $key }}" 
                                {{ old('level', $strukturOrganisasi->level) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="{{ old('nama', $strukturOrganisasi->nama) }}"
                           placeholder="Contoh: SUNARTO"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-primary-500 focus:border-primary-500">

                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="jabatan"
                           value="{{ old('jabatan', $strukturOrganisasi->jabatan) }}"
                           placeholder="Contoh: Sekretaris Desa"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                  focus:ring-2 focus:ring-primary-500 focus:border-primary-500">

                    @error('jabatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Atasan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Atasan (Opsional)
                    </label>

                    <select name="atasan_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                   focus:ring-2 focus:ring-primary-500 focus:border-primary-500">

                        <option value="">-- Tidak Ada Atasan --</option>

                        @foreach($potentialAtasan as $atasan)
                            <option value="{{ $atasan->id }}"
                                {{ old('atasan_id', $strukturOrganisasi->atasan_id) == $atasan->id ? 'selected' : '' }}>
                                {{ $atasan->nama }} - {{ $atasan->jabatan }}
                            </option>
                        @endforeach
                    </select>

                    @error('atasan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Profil
                    </label>

                    <div class="flex items-center gap-4">
                        @if ($strukturOrganisasi->foto_url)
                        <img src="{{ $strukturOrganisasi->foto_url }}" 
                             class="w-28 h-28 object-cover rounded-lg border">
                        @endif

                        <input type="file" name="foto" accept="image/*"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg 
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500">

                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Biarkan kosong jika tidak ingin mengubah foto. Maks: 2MB
                    </p>

                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Singkat (Opsional)
                    </label>
                    <textarea name="deskripsi" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg 
                                     focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Deskripsi singkat...">{{ old('deskripsi', $strukturOrganisasi->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status (di-comment, belum dibutuhkan) -->
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_active" value="1"
                                {{ old('is_active', $strukturOrganisasi->is_active) == 1 ? 'checked' : '' }}>
                            Aktif
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="is_active" value="0"
                                {{ old('is_active', $strukturOrganisasi->is_active) == 0 ? 'checked' : '' }}>
                            Tidak Aktif
                        </label>
                    </div>
                </div> --}}
                <input type="hidden" name="is_active" value="1">

            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
                <a href="{{ route('admin.struktur-organisasi.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
