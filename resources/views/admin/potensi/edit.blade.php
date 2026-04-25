@extends('admin.layouts.app')

@section('title', 'Edit Potensi Desa')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Potensi Desa</h1>
                <nav class="text-sm text-gray-500 mt-1" aria-label="breadcrumb">
                    <ol class="flex gap-2 items-center">
                        <li><a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:underline">Dashboard</a>
                        </li>
                        <li class="text-gray-400">/</li>
                        <li><a href="{{ route('admin.potensi.index') }}"
                                class="text-primary-600 hover:underline">Potensi</a></li>
                        <li class="text-gray-400">/</li>
                        <li class="text-gray-600">Edit</li>
                    </ol>
                </nav>
            </div>

            <a href="{{ route('admin.potensi.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex items-start gap-3">
                    <div class="text-red-600">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="ml-auto text-red-500 hover:text-red-700"
                        onclick="this.closest('div').remove()">
                        &times;
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.potensi.update', $potensi->id) }}" method="POST" enctype="multipart/form-data"
            id="potensiForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Konten Potensi -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Konten Potensi</h2>

                        <!-- Nama Potensi -->
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">Nama Potensi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $potensi->nama) }}"
                                placeholder="Contoh: Pertanian Padi Organik" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('nama') border-red-300 ring-red-100 @enderror">
                            @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Slug saat ini: <strong>{{ $potensi->slug }}</strong></p>
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-4">
                            <label for="lokasi" class="block text-sm font-bold text-gray-700 mb-2">Lokasi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $potensi->lokasi) }}"
                                placeholder="Contoh: Dusun Krajan" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('lokasi') border-red-300 ring-red-100 @enderror">
                            @error('lokasi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="mb-4">
                            <label for="deskripsi_singkat" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi
                                Singkat <span class="text-red-500">*</span></label>
                            <textarea id="deskripsi_singkat" name="deskripsi_singkat" rows="3" maxlength="500"
                                placeholder="Ringkasan singkat tentang potensi ini (max 500 karakter)" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('deskripsi_singkat') border-red-300 ring-red-100 @enderror">{{ old('deskripsi_singkat', $potensi->deskripsi_singkat) }}</textarea>
                            @error('deskripsi_singkat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1"><span id="charCount">0</span>/500 karakter</p>
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Lengkap
                                <span class="text-red-500">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="15"
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror">{{ old('deskripsi', $potensi->deskripsi) }}</textarea>
                            @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kontak -->
                            <div>
                                <label for="kontak" class="block text-sm font-bold text-gray-700 mb-2">Email <span
                                        class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                                <input type="email" id="kontak" name="kontak" value="{{ old('kontak', $potensi->kontak) }}"
                                    placeholder="Contoh: email@gmail.com"
                                    class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('kontak') border-red-300 ring-red-100 @enderror">
                                @error('kontak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label for="whatsapp" class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp
                                    <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-100 bg-gray-50 text-gray-500 font-medium sm:text-sm">+62</span>
                                    <input type="text" id="whatsapp" name="whatsapp"
                                        value="{{ old('whatsapp', $potensi->whatsapp) }}" placeholder="8123456789"
                                        maxlength="15" pattern="[0-9]*"
                                        class="flex-1 min-w-0 w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-none rounded-r-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('whatsapp') border-red-300 ring-red-100 @enderror">
                                </div>
                                @error('whatsapp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Informasi</h2>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Dibuat:</span>
                                <span class="text-gray-800">{{ $potensi->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Diupdate:</span>
                                <span class="text-gray-800">{{ $potensi->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Views:</span>
                                <span class="text-gray-800">{{ number_format($potensi->views ?? 0) }}</span>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-2">
                                <span class="text-gray-500 font-medium">Penulis:</span>
                                <span class="text-gray-800">{{ $potensi->admin->name ?? 'Desa Warurejo' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">

                    <!-- Media Upload -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Media</h2>

                        <div
                            class="border-2 border-dashed rounded-lg p-4 text-center relative hover:border-primary-400 transition cursor-pointer">
                            <input type="file" id="gambar" name="gambar" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                            <!-- Placeholder -->
                            <div id="uploadPlaceholder" class="{{ $potensi->gambar ? 'hidden' : '' }}">
                                <svg class="w-12 h-12 mx-auto mb-3 text-primary-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 48 48">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8M8 32l9.172-9.172a4 4 0 015.656 0L28 28l4 4m4-24h8m-4-4v8m-12 4h.02" />
                                </svg>
                                <h3 class="font-medium text-gray-700">Upload / Ganti Gambar</h3>
                                <p class="text-xs text-gray-500">Format JPG, PNG, WEBP — Max 2MB</p>
                            </div>

                            <!-- Preview -->
                            <div id="previewContainer" class="{{ $potensi->gambar ? '' : 'hidden' }}">
                                <img id="preview" src="{{ $potensi->gambar ? Storage::url($potensi->gambar) : '' }}"
                                    class="rounded-lg shadow w-full object-cover max-h-60">
                                <p class="text-xs text-gray-500 mt-2 italic">Klik area untuk mengganti gambar</p>
                            </div>
                        </div>
                        @error('gambar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Pengaturan -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan</h2>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="kategori" class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori" required
                                class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="pertanian" {{ old('kategori', $potensi->kategori) == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                <option value="peternakan" {{ old('kategori', $potensi->kategori) == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                                <option value="perikanan" {{ old('kategori', $potensi->kategori) == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                <option value="umkm" {{ old('kategori', $potensi->kategori) == 'umkm' ? 'selected' : '' }}>
                                    UMKM</option>
                                <option value="wisata" {{ old('kategori', $potensi->kategori) == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                <option value="kerajinan" {{ old('kategori', $potensi->kategori) == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="lainnya" {{ old('kategori', $potensi->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <input type="hidden" name="is_active" value="1">

                        <hr class="my-4">

                        <button type="submit" name="action" value="publish"
                            class="w-full px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition">
                            Update Perubahan
                        </button>

                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.potensi.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize CKEditor
            let editorInstance;
            ClassicEditor
                .create(document.querySelector('#deskripsi'), {
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
                    editor.ui.view.editable.element.style.minHeight = '400px';
                    window.editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            // Character counter for deskripsi_singkat
            $('#deskripsi_singkat').on('input', function () {
                var length = $(this).val().length;
                $('#charCount').text(length);

                if (length > 500) {
                    $('#charCount').addClass('text-danger');
                } else {
                    $('#charCount').removeClass('text-danger');
                }
            });

            // Trigger on page load
            $('#deskripsi_singkat').trigger('input');

            // Image Preview
            $('#gambar').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2048 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB');
                        $(this).val('');
                        return;
                    }

                    // Validate file type
                    if (!file.type.match('image.*')) {
                        alert('File harus berupa gambar!');
                        $(this).val('');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview').attr('src', e.target.result);
                        $('#uploadPlaceholder').addClass('hidden');
                        $('#previewContainer').removeClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // WhatsApp Number Validation
            $('#whatsapp').on('input', function () {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');

                // Remove leading 0 or +62 if user enters it
                if (this.value.startsWith('0')) {
                    this.value = this.value.substring(1);
                }
                if (this.value.startsWith('62')) {
                    this.value = this.value.substring(2);
                }

                // Limit to 15 characters
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
            });

            // Form Submit - Set status based on button clicked
            $('button[name="action"]').on('click', function () {
                var action = $(this).val();
                // if (action === 'draft') {
                //     $('#status').val('draft');
                // } else
                if (action === 'publish') {
                    $('#status').val('published');
                }
            });

            // Form Validation
            $('#potensiForm').on('submit', function (e) {
                // Update CKEditor content to textarea
                if (window.editorInstance) {
                    $('#deskripsi').val(window.editorInstance.getData());
                }

                // Validate required fields
                var nama = $('#nama').val().trim();
                var deskripsi = window.editorInstance ? window.editorInstance.getData().trim() : '';
                var deskripsi_singkat = $('#deskripsi_singkat').val().trim();
                var kategori = $('#kategori').val();
                var lokasi = $('#lokasi').val().trim();

                if (!nama || !deskripsi || !deskripsi_singkat || !kategori || !lokasi) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
                    return false;
                }

                // Validate deskripsi_singkat length
                if (deskripsi_singkat.length > 500) {
                    e.preventDefault();
                    alert('Deskripsi singkat maksimal 500 karakter!');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush