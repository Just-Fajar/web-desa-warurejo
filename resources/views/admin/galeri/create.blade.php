@extends('admin.layouts.app')

@section('title', 'Tambah Galeri Baru')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Galeri</h1>
                <p class="text-sm text-gray-500 mt-1">Upload foto ke galeri desa</p>
            </div>
            <a href="{{ route('admin.galeri.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" id="galeriForm">
                @csrf

                <div class="p-6 space-y-6">

                            <!-- JUDUL -->
                            <div class="mb-4">
                                <label for="judul" class="block text-sm font-bold text-gray-900 mb-2">
                                    Judul Galeri <span class="text-red-500">*</span>
                                </label>

                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                                    placeholder="Masukkan judul..." class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium
                                                  @error('judul') border-red-300 ring-red-100 @enderror">

                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug (Auto-generated) -->
                            <div class="mb-4">
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

                            <!-- DESKRIPSI -->
                            <div class="mb-4">
                                <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">
                                    Deskripsi
                                </label>

                                <textarea name="deskripsi" id="deskripsi" rows="6"
                                    placeholder="Ceritakan detail tentang foto ini..."
                                    class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium
                                                     @error('deskripsi') border-red-300 ring-red-100 @enderror">{{ old('deskripsi') }}</textarea>

                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                @enderror

                                <p class="text-xs text-gray-500 text-right mt-1">
                                    Opsional, tetapi disarankan diisi.
                                </p>
                            </div>

                            <!-- MULTIPLE PHOTO UPLOAD -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    Upload Foto <span class="text-red-500">*</span>
                                </label>

                                <div id="multiUploadArea"
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 
                                         text-center bg-gray-50 hover:border-primary-500 transition cursor-pointer relative">

                                    <input type="file" id="multiGambar" name="images[]" accept="image/*" multiple
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                    <!-- Placeholder -->
                                    <div id="multiUploadPlaceholder">
                                        <div class="text-gray-400 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto opacity-50"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-gray-700">Klik atau Tarik Gambar (Bisa Banyak)</p>
                                        <p class="text-xs text-gray-500">Format: JPG, PNG, WEBP (Max 2MB per file)</p>
                                    </div>
                                </div>

                                <!-- Preview Grid -->
                                <div id="previewGrid" class="row g-3 mt-3" style="display: none;"></div>

                                @error('images')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- KATEGORI -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-900 mb-2">Kategori <span
                                        class="text-red-500">*</span></label>

                                <select name="kategori" required
                                    class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium">

                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kegiatan">Kegiatan</option>
                                    <option value="pembangunan">Pembangunan</option>
                                    <option value="budaya">Budaya</option>
                                    <option value="keagamaan">Keagamaan</option>
                                    <option value="sosial">Sosial</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <!-- TANGGAL -->
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-900 mb-2">Tanggal Kejadian</label>

                                <input type="text" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                    placeholder="Pilih tanggal"
                                    class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium">
                            </div>

                            <!-- Pengaturan Publikasi -->
                            <div class="border-t border-gray-200 pt-6 mt-6">
                                <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                                <div class="space-y-6">
                                    @include('admin.partials._status_fields', [
                                        'currentStatus' => old('status', 'published'),
                                        'publishedAt' => old('published_at', ''),
                                    ])
                                </div>
                            </div>

                </div>
                
                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <a href="{{ route('admin.galeri.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span id="submitBtnText" data-module="Galeri">Publish Galeri</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
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

            let selectedFiles = [];

            // MULTIPLE UPLOAD HANDLER
            document.getElementById('multiGambar').addEventListener('change', function (event) {
                const files = Array.from(event.target.files);

                files.forEach(file => {
                    if (file.size > 2048000) {
                        alert(`File ${file.name} terlalu besar (max 2MB)`);
                        return;
                    }

                    if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                });

                updatePreviewGrid();
                updateFileInput();
            });

            function updatePreviewGrid() {
                const previewGrid = document.getElementById('previewGrid');
                const placeholder = document.getElementById('multiUploadPlaceholder');

                if (selectedFiles.length > 0) {
                    previewGrid.style.display = 'flex';
                    placeholder.style.display = 'none';

                    previewGrid.innerHTML = '';

                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3';
                            col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="width: 100%; height: 150px; object-fit: cover;">
                                <button type="button" 
                                        data-index="${index}" 
                                        class="remove-img-btn btn btn-danger btn-sm position-absolute top-0 end-0 m-1">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="badge bg-primary position-absolute bottom-0 start-0 m-1">${index + 1}</div>
                            </div>
                        `;
                            previewGrid.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    previewGrid.style.display = 'none';
                    placeholder.style.display = 'block';
                }
            }

            // Event delegation to remove images without inline onclick handlers
            const previewGridEl = document.getElementById('previewGrid');
            if (previewGridEl) {
                previewGridEl.addEventListener('click', function(e) {
                    const button = e.target.closest('.remove-img-btn');
                    if (button) {
                        const index = parseInt(button.getAttribute('data-index'));
                        removeImage(index);
                    }
                });
            }

            function removeImage(index) {
                selectedFiles.splice(index, 1);
                updatePreviewGrid();
                updateFileInput();
            }

            function updateFileInput() {
                const input = document.getElementById('multiGambar');
                const dataTransfer = new DataTransfer();

                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });

                input.files = dataTransfer.files;
            }

            // Form validation
            document.getElementById('galeriForm').addEventListener('submit', function (e) {
                if (selectedFiles.length === 0) {
                    e.preventDefault();
                    alert('Silakan pilih minimal 1 gambar!');
                    return false;
                }
            });

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