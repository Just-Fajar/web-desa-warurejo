@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Berita</h1>
                <p class="text-sm text-gray-600 mt-1">Perbarui informasi berita</p>
            </div>
            <a href="{{ route('admin.berita.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data"
            id="beritaForm">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-bold text-gray-900 mb-2">
                            Judul Berita <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $berita->judul) }}"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('judul') border-red-300 ring-red-100 @enderror"
                            required>
                        @error('judul') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-bold text-gray-900 mb-2">
                            Slug <span class="text-xs text-gray-500 font-normal">(Otomatis)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $berita->slug) }}"
                            class="w-full px-5 py-3 bg-gray-50 border border-gray-300 shadow-sm rounded-xl text-gray-500 text-sm font-medium focus:outline-none cursor-not-allowed"
                            readonly>
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Ringkasan -->
                    <div>
                        <label for="ringkasan" class="block text-sm font-bold text-gray-900 mb-2">
                            Ringkasan/Excerpt
                        </label>
                        <textarea name="ringkasan" id="ringkasan" rows="3"
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('ringkasan') border-red-300 ring-red-100 @enderror">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500"><span
                                id="ringkasanCount">{{ strlen(old('ringkasan', $berita->ringkasan ?? '')) }}</span>/500
                            karakter</p>
                        @error('ringkasan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Konten -->
                    <div>
                        <label for="konten" class="block text-sm font-bold text-gray-900 mb-2">
                            Konten Berita <span class="text-red-500">*</span>
                        </label>
                        <textarea name="konten" id="konten" rows="10"
                            class="w-full border border-gray-300 rounded-xl p-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('konten') border-red-300 @enderror">{{ old('konten', $berita->konten) }}</textarea>
                        @error('konten') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Media Upload -->
                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Media</h3>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">Gambar Utama</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Gambar Saat Ini (Kiri) -->
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gambar Saat Ini</span>
                                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 w-full aspect-video flex items-center justify-center relative shadow-sm">
                                        @if($berita->gambar_utama)
                                            <img src="{{ $berita->gambar_utama_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="text-center p-6 text-gray-400">
                                                <i class="fas fa-image text-4xl mb-2"></i>
                                                <p class="text-sm">Tidak ada gambar saat ini</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Gambar Baru (Kanan) -->
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gambar Baru</span>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center relative hover:border-primary-500 transition cursor-pointer flex flex-col justify-center w-full aspect-video overflow-hidden bg-gray-50 shadow-sm">
                                        <input type="file" id="gambar_utama" name="gambar_utama" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <!-- Placeholder -->
                                        <div id="uploadPlaceholder">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-primary-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 48 48">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4m4-24h8m-4-4v8m-12 4h.02" />
                                            </svg>
                                            <h3 class="font-medium text-primary-600 hover:text-primary-700 text-sm">Pilih Gambar Baru</h3>
                                            <p class="text-xs text-gray-500 mt-2">Format JPG, PNG, WEBP — Max 2MB</p>
                                        </div>

                                        <!-- Preview -->
                                        <div id="previewContainer" class="hidden absolute inset-0 w-full h-full p-2 bg-gray-50">
                                            <img id="imagePreview" src=""
                                                class="rounded-lg shadow w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-xl">
                                                <p class="text-white text-sm font-medium mb-2">Ubah Gambar Baru</p>
                                                <button type="button" id="btnBatalGanti" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all focus:outline-none z-20">
                                                    Batal Ganti
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('gambar_utama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                     <!-- Pengaturan Publikasi -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                        <div class="space-y-6">
                            @include('admin.partials._status_fields', [
                                'currentStatus' => old('status', $berita->status),
                                'publishedAt' => old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d H:i') : ''),
                            ])
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Dibuat</p>
                                <p class="text-gray-800 font-semibold">{{ $berita->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Diupdate</p>
                                <p class="text-gray-800 font-semibold">{{ $berita->updated_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Views</p>
                                <p class="text-gray-800 font-semibold">{{ number_format($berita->views ?? 0) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 font-medium mb-1">Penulis</p>
                                <p class="text-gray-800 font-semibold">{{ $berita->admin->name ?? 'Desa Warurejo' }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.berita.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="submitBtnText" data-module="Berita">Update Berita</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            .ck-editor__editable_inline {
                min-height: 400px;
                border-radius: 0.75rem !important;
                border-color: #f3f4f6 !important;
            }

            .ck-toolbar {
                border-top-left-radius: 0.75rem !important;
                border-top-right-radius: 0.75rem !important;
                background-color: #f9fafb !important;
                border-color: #f3f4f6 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- CKEditor 5 -->
        <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
        <script>
            // Initialize CKEditor
            let editorInstance;
            ClassicEditor
                .create(document.querySelector('#konten'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    language: 'id',
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                    }
                })
                .then(editor => {
                    editorInstance = editor;
                    editor.ui.view.editable.element.style.minHeight = '500px';
                })
                .catch(error => {
                    console.error(error);
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

            // Character count for ringkasan
            const ringkasanTextarea = document.getElementById('ringkasan');
            const ringkasanCount = document.getElementById('ringkasanCount');

            ringkasanTextarea.addEventListener('input', function () {
                const length = this.value.length;
                ringkasanCount.textContent = length;

                if (length > 500) {
                    ringkasanCount.classList.add('text-red-600');
                } else {
                    ringkasanCount.classList.remove('text-red-600');
                }
            });

            // Image preview
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
            document.getElementById('gambar_utama').addEventListener('change', previewImage);

            const btnBatalGanti = document.getElementById('btnBatalGanti');
            if (btnBatalGanti) {
                btnBatalGanti.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('gambar_utama').value = '';
                    document.getElementById('uploadPlaceholder').classList.remove('hidden');
                    document.getElementById('previewContainer').classList.add('hidden');
                });
            }


            // Client-side validation
            document.getElementById('beritaForm').addEventListener('submit', function (e) {
                const judul = document.getElementById('judul').value.trim();
                const konten = editorInstance ? editorInstance.getData() : '';

                if (!judul) {
                    e.preventDefault();
                    alert('Judul berita wajib diisi!');
                    document.getElementById('judul').focus();
                    return false;
                }

                if (!konten || konten.trim() === '') {
                    e.preventDefault();
                    alert('Konten berita wajib diisi!');
                    editorInstance.focus();
                    return false;
                }
            });
        </script>
    @endpush
@endsection