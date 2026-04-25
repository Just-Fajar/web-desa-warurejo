@extends('admin.layouts.app')

@section('title', 'Edit Galeri')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Galeri</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi galeri desa</p>
            </div>

            <a href="{{ route('admin.galeri.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Content (Gallery details) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Konten Galeri</h2>

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-bold text-gray-700 mb-2">
                                Judul Galeri <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $galeri->judul) }}"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                                required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="6"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Informasi Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Informasi</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Dibuat:</span>
                                <span
                                    class="text-gray-800">{{ \Carbon\Carbon::parse($galeri->created_at)->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Diupdate:</span>
                                <span
                                    class="text-gray-800">{{ \Carbon\Carbon::parse($galeri->updated_at)->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Views:</span>
                                <span class="text-gray-800">{{ number_format($galeri->views ?? 0) }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Penulis:</span>
                                <span class="text-gray-800">{{ $galeri->admin->name ?? 'Admin Desa' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">

                    <!-- Media Upload -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Media</h2>

                        <div
                            class="border-2 border-dashed rounded-lg p-4 text-center relative hover:border-primary-400 transition cursor-pointer">
                            <input type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                            <!-- Placeholder -->
                            <div id="uploadPlaceholder" class="{{ $galeri->gambar ? 'hidden' : '' }}">
                                <svg class="w-12 h-12 mx-auto mb-3 text-primary-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 48 48">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4m4-24h8m-4-4v8m-12 4h.02" />
                                </svg>
                                <h3 class="font-medium text-gray-700">Upload / Ganti Gambar</h3>
                                <p class="text-xs text-gray-500">Format JPG, PNG, WEBP — Max 2MB</p>
                            </div>

                            <!-- Preview -->
                            <div id="previewContainer" class="{{ $galeri->gambar ? '' : 'hidden' }}">
                                <img id="imagePreview"
                                    src="{{ $galeri->gambar ? asset('storage/' . $galeri->gambar) : '' }}"
                                    class="rounded-lg shadow w-full object-cover max-h-60">
                                <p class="text-xs text-gray-500 mt-2 italic">Klik area untuk mengganti gambar</p>
                            </div>
                        </div>

                        @error('gambar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengaturan -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan</h2>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kegiatan" {{ old('kategori', $galeri->kategori) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                                <option value="pembangunan" {{ old('kategori', $galeri->kategori) == 'pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                                <option value="budaya" {{ old('kategori', $galeri->kategori) == 'budaya' ? 'selected' : '' }}>Budaya</option>
                                <option value="keagamaan" {{ old('kategori', $galeri->kategori) == 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                                <option value="sosial" {{ old('kategori', $galeri->kategori) == 'sosial' ? 'selected' : '' }}>Sosial</option>
                                <option value="lainnya" {{ old('kategori', $galeri->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kejadian</label>
                            <input type="text" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', $galeri->tanggal ? $galeri->tanggal->format('Y-m-d') : '') }}"
                                placeholder="Pilih tanggal"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tanggal') border-red-300 ring-red-100 @enderror"
                                required>
                        </div>

                        <!-- Status (di-comment, belum dibutuhkan) -->
                        {{-- <div class="flex items-center gap-3">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="w-5 h-5" {{
                                old('is_active', $galeri->is_active) == '1' ? 'checked' : '' }}>
                            <label for="is_active" class="text-sm text-gray-700">Tampilkan di Website</label>
                        </div> --}}
                        <input type="hidden" name="is_active" value="1">

                        <hr class="my-4">

                        <button type="submit"
                            class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition">
                            Update Perubahan
                        </button>
                    </div>

                </div>

            </div>

        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            flatpickr("#tanggal", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale: "id"
            });

            function previewImage(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('uploadPlaceholder').classList.add('hidden');
                        document.getElementById('previewContainer').classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>
    @endpush

@endsection