@extends('admin.layouts.app')

@section('title', 'Tambah Potensi Desa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tambah Potensi Desa</h1>
                <nav class="text-sm text-gray-500 mt-1" aria-label="breadcrumb">
                    <ol class="flex gap-2 items-center">
                        <li><a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:underline">Dashboard</a></li>
                        <li class="text-gray-400">/</li>
                        <li><a href="{{ route('admin.potensi.index') }}" class="text-primary-600 hover:underline">Potensi</a></li>
                        <li class="text-gray-400">/</li>
                        <li class="text-gray-600">Tambah</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.potensi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <div class="flex items-start gap-3">
                    <div class="text-red-600"><i class="fas fa-exclamation-circle"></i></div>
                    <div class="text-sm text-red-700">{{ session('error') }}</div>
                    <button type="button" class="close-alert-btn ml-auto text-red-500 hover:text-red-700">&times;</button>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.potensi.store') }}" method="POST" enctype="multipart/form-data" id="potensiForm">
            @csrf
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 space-y-6">
                    <!-- Konten Potensi -->
                    <div>
                        <label for="nama" class="block text-sm font-bold text-gray-900 mb-2">Nama Potensi <span class="text-red-500">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Pertanian Padi Organik" required class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('nama') border-red-300 ring-red-100 @enderror">
                        @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="lokasi" class="block text-sm font-bold text-gray-900 mb-2">Lokasi <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Dusun Krajan, RT 01/RW 02" class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('lokasi') border-red-300 ring-red-100 @enderror">
                            @error('lokasi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="info_utama" class="block text-sm font-bold text-gray-900 mb-2">Info Utama <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                            <input type="text" id="info_utama" name="info_utama" value="{{ old('info_utama') }}" placeholder="Contoh: 50 Hektar, 100 Ekor, Berdiri Sejak 2010" class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('info_utama') border-red-300 ring-red-100 @enderror">
                            @error('info_utama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Informasi ringkas yang ditampilkan di card dan halaman detail</p>
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-bold text-gray-900 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="15" class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('deskripsi') border-red-300 ring-red-100 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_pengelola" class="block text-sm font-bold text-gray-900 mb-2">Nama Pengelola <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_pengelola" name="nama_pengelola" value="{{ old('nama_pengelola') }}" placeholder="Contoh: Pak Sugeng" required class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('nama_pengelola') border-red-300 ring-red-100 @enderror">
                            @error('nama_pengelola') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="whatsapp" class="block text-sm font-bold text-gray-900 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-100 bg-gray-50 text-gray-500 font-medium sm:text-sm">+62</span>
                                <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="8123456789" maxlength="15" pattern="[0-9]*" required class="flex-1 min-w-0 w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-none rounded-r-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('whatsapp') border-red-300 ring-red-100 @enderror">
                            </div>
                            @error('whatsapp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="link_maps" class="block text-sm font-bold text-gray-900 mb-2">Link Google Maps <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                        <input type="url" id="link_maps" name="link_maps" value="{{ old('link_maps') }}" placeholder="https://maps.google.com/..." class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('link_maps') border-red-300 ring-red-100 @enderror">
                        @error('link_maps') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @php $potensi = null; @endphp
                    <!-- Media Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200 mt-6">
                        <!-- Foto Utama -->
                        <div>
                            <label for="gambar" class="block text-sm font-bold text-gray-900 mb-2">Foto Utama</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center relative hover:border-primary-500 transition cursor-pointer flex flex-col justify-center w-full aspect-video overflow-hidden bg-gray-50">
                                <input type="file" id="gambar" name="gambar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div id="uploadPlaceholder" class="{{ $potensi && $potensi->gambar ? 'hidden' : '' }}">
                                    <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                                    <h3 class="text-primary-600 hover:text-primary-700 font-semibold text-sm">Upload Foto Utama</h3>
                                    <p class="text-xs text-gray-500 mt-2">Format JPG, PNG, WEBP — Max 2MB</p>
                                </div>
                                <div id="previewContainer" class="{{ $potensi && $potensi->gambar ? '' : 'hidden' }} absolute inset-0 w-full h-full p-2">
                                    <img id="preview" src="{{ $potensi && $potensi->gambar ? Storage::url($potensi->gambar) : '' }}" class="rounded-lg shadow w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity rounded-xl">
                                        <p class="text-white text-sm font-medium">Ubah Gambar</p>
                                    </div>
                                </div>
                            </div>
                            @error('gambar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Foto Galeri -->
                        <div>
                            <label for="foto_galeri" class="block text-sm font-bold text-gray-900 mb-2">Foto Galeri <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>

                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center relative hover:border-primary-500 transition cursor-pointer flex flex-col justify-center w-full aspect-video overflow-hidden bg-gray-50">
                                <input type="file" id="foto_galeri" name="foto_galeri[]" accept="image/*" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div>
                                    <i class="fas fa-images text-4xl text-gray-400 mb-3"></i>
                                    <h3 class="text-primary-600 hover:text-primary-700 font-semibold text-sm">{{ $potensi ? 'Tambah Foto Galeri' : 'Upload Foto Galeri' }}</h3>
                                    <p class="text-xs text-gray-500 mt-2">Pilih beberapa foto sekaligus — Max 2MB per file</p>
                                </div>
                            </div>

                            <div id="galeriPreview" class="grid grid-cols-3 gap-2 mt-3 hidden"></div>
                            @error('foto_galeri.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Pengaturan Publikasi -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-primary-600 mb-4">Pengaturan Publikasi</h3>
                        <div class="space-y-6">
                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-bold text-gray-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select id="kategori" name="kategori" required class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all text-sm font-medium @error('kategori') border-red-300 ring-red-100 @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(\App\Models\PotensiDesa::getKategoriList() as $key => $label)
                                        <option value="{{ $key }}" {{ old('kategori', $potensi->kategori ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status & Scheduling -->
                            @include('admin.partials._status_fields', [
                                'currentStatus' => old('status', $potensi->status ?? 'published'),
                                'publishedAt' => old('published_at', $potensi && $potensi->published_at ? $potensi->published_at->format('Y-m-d H:i') : ''),
                            ])
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.potensi.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-semibold flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="submitBtnText" data-module="Potensi">Publish Potensi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js" nonce="{{ csp_nonce() }}"></script>
    <script nonce="{{ csp_nonce() }}">
        $(document).ready(function () {
            // Close alert button handler
            $(document).on('click', '.close-alert-btn', function() {
                $(this).closest('div.bg-red-50').remove();
            });
            // Auto-generate slug from nama
            $('#nama').on('input', function () {
                const nama = $(this).val();
                const slug = nama
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                $('#slug').val(slug);
            });

            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#deskripsi'), {
                    toolbar: {
                        items: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
                    },
                    language: 'id',
                    table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] }
                })
                .then(editor => {
                    editor.ui.view.editable.element.style.minHeight = '400px';
                    window.editorInstance = editor;
                })
                .catch(error => console.error(error));

            // Image Preview - Foto Utama
            $('#gambar').on('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2048 * 1024) { alert('Ukuran file terlalu besar! Maksimal 2MB'); $(this).val(''); return; }
                    if (!file.type.match('image.*')) { alert('File harus berupa gambar!'); $(this).val(''); return; }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview').attr('src', e.target.result);
                        $('#uploadPlaceholder').addClass('hidden');
                        $('#previewContainer').removeClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Foto Galeri Preview
            $('#foto_galeri').on('change', function (e) {
                const files = e.target.files;
                const $preview = $('#galeriPreview');
                $preview.empty();
                if (files.length > 0) {
                    $preview.removeClass('hidden');
                    Array.from(files).forEach(function(file) {
                        if (file.size > 2048 * 1024) { alert('File "' + file.name + '" terlalu besar!'); return; }
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $preview.append('<div class="relative group"><img src="' + e.target.result + '" class="w-full h-20 object-cover rounded-lg border"><div class="absolute inset-0 bg-black/40 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition"><span class="text-white text-xs">Baru</span></div></div>');
                        }
                        reader.readAsDataURL(file);
                    });
                } else {
                    $preview.addClass('hidden');
                }
            });

            // WhatsApp Number Validation
            $('#whatsapp').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.startsWith('0')) this.value = this.value.substring(1);
                if (this.value.startsWith('62')) this.value = this.value.substring(2);
                if (this.value.length > 15) this.value = this.value.substring(0, 15);
            });

            // Form Submit
            $('button[name="action"]').on('click', function () {
                if ($(this).val() === 'publish') $('#status').val('published');
            });

            // Form Validation
            $('#potensiForm').on('submit', function (e) {
                if (window.editorInstance) $('#deskripsi').val(window.editorInstance.getData());
                var nama = $('#nama').val().trim();
                var deskripsi = window.editorInstance ? window.editorInstance.getData().trim() : '';
                var kategori = $('#kategori').val();
                var nama_pengelola = $('#nama_pengelola').val().trim();
                var whatsapp = $('#whatsapp').val().trim();
                if (!nama || !deskripsi || !kategori || !nama_pengelola || !whatsapp) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi (bertanda *)');
                    return false;
                }
                return true;
            });
        });

        // AJAX delete foto galeri (edit mode)
        function hapusFotoGaleri(fotoId) {
            if (!confirm('Hapus foto ini dari galeri?')) return;
            $.ajax({
                url: '/admin/potensi/foto/' + fotoId,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        $('#foto-' + fotoId).fadeOut(300, function() { $(this).remove(); });
                    }
                },
                error: function() { alert('Gagal menghapus foto'); }
            });
        }
    </script>
@endpush