@extends('admin.layouts.app')

@section('title', 'Upload Publikasi')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Upload Publikasi Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Tambah dokumen publikasi desa</p>
            </div>
            <a href="{{ route('admin.publikasi.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.publikasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-bold text-gray-900 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                            placeholder="Contoh: Anggaran Pendapatan dan Belanja Desa Tahun 2025" required>
                        @error('judul')
                            <p class="text-red-600 text-sm font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug (Auto-generated) -->
                    <div>
                        <label for="slug" class="block text-sm font-bold text-gray-900 mb-2">
                            Slug <span class="text-xs text-gray-500 font-normal">(Otomatis dibuatkan dari judul)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-300 shadow-sm rounded-xl text-gray-500 text-sm font-medium focus:outline-none cursor-not-allowed @error('slug') border-red-300 @enderror"
                            placeholder="slug-otomatis" readonly>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Tahun -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori" class="block text-sm font-bold text-gray-900 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" name="kategori"
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                <option value="APBDes" {{ old('kategori') == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                                <option value="RPJMDes" {{ old('kategori') == 'RPJMDes' ? 'selected' : '' }}>RPJMDes
                                </option>
                                <option value="RKPDes" {{ old('kategori') == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                            </select>
                            @error('kategori')
                                <p class="text-red-600 text-sm font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun" class="block text-sm font-bold text-gray-900 mb-2">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="tahun" name="tahun" value="{{ old('tahun', date('Y')) }}"
                                min="1990" max="{{ date('Y') + 5 }}"
                                class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tahun') border-red-300 ring-red-100 @enderror"
                                required>
                            @error('tahun')
                                <p class="text-red-600 text-sm font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror"
                            placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-600 text-sm font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Media Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- File Dokumen -->
                        <div>
                            <label for="file_dokumen" class="block text-sm font-bold text-gray-900 mb-2">
                                File Dokumen (PDF) <span class="text-red-500">*</span>
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition h-full flex flex-col justify-center">
                                <i class="fas fa-file-pdf text-4xl text-gray-400 mb-3"></i>
                                <input type="file" id="file_dokumen" name="file_dokumen" accept=".pdf" class="hidden"
                                    required>
                                <label for="file_dokumen" class="cursor-pointer">
                                    <span class="text-primary-600 hover:text-primary-700 font-semibold">Pilih File
                                        PDF</span>
                                    <span class="text-gray-600"> atau drag & drop</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">Maksimal 10MB</p>
                                <p id="file-name" class="text-sm text-gray-700 font-semibold mt-2"></p>
                            </div>
                            @error('file_dokumen')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <label for="thumbnail" class="block text-sm font-bold text-gray-900 mb-2">
                                Thumbnail (Opsional)
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition h-full flex flex-col justify-center">
                                <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/jpg"
                                    class="hidden">
                                <label for="thumbnail" class="cursor-pointer">
                                    <span class="text-primary-600 hover:text-primary-700 font-semibold">Pilih Gambar</span>
                                    <span class="text-gray-600"> atau drag & drop</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG (Maksimal 2MB)</p>
                                <p id="thumbnail-name" class="text-sm text-gray-700 font-semibold mt-2"></p>
                            </div>
                            @error('thumbnail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Pengaturan Publikasi -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                        <div class="space-y-6">
                            <!-- Tanggal Dokumen -->
                            <div>
                                <label for="tanggal_publikasi" class="block text-sm font-bold text-gray-900 mb-2">
                                    Tanggal Dokumen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="tanggal_publikasi" name="tanggal_publikasi"
                                    value="{{ old('tanggal_publikasi', date('Y-m-d')) }}" placeholder="Pilih tanggal"
                                    class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('tanggal_publikasi') border-red-300 ring-red-100 @enderror"
                                    required>
                                @error('tanggal_publikasi')
                                    <p class="text-red-600 text-sm font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status & Scheduling -->
                            @include('admin.partials._status_fields', [
                                'currentStatus' => old('status', 'published'),
                                'publishedAt' => old('published_at', ''),
                            ])
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.publikasi.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="submitBtnText" data-module="Publikasi">Publish Publikasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
        <script>
            flatpickr("#tanggal_publikasi", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                locale: "id"
            });

            function displayFileName(inputId, displayId) {
                const input = document.getElementById(inputId);
                const display = document.getElementById(displayId);

                if (input && display && input.files.length > 0) {
                    const fileName = input.files[0].name;
                    const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                    display.textContent = `${fileName} (${fileSize} MB)`;
                } else if (display) {
                    display.textContent = '';
                }
            }

            const fileDokumenInput = document.getElementById('file_dokumen');
            if (fileDokumenInput) {
                fileDokumenInput.addEventListener('change', function() {
                    displayFileName('file_dokumen', 'file-name');
                });
            }

            const thumbnailInput = document.getElementById('thumbnail');
            if (thumbnailInput) {
                thumbnailInput.addEventListener('change', function() {
                    displayFileName('thumbnail', 'thumbnail-name');
                });
            }

            // Auto-generate slug from judul
            document.getElementById('judul').addEventListener('input', function () {
                const judul = this.value;
                const slug = judul
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                document.getElementById('slug').value = slug;
            });
        </script>
    @endpush
@endsection