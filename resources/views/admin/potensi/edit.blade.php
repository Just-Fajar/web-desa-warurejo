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
                    <li><a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:underline">Dashboard</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('admin.potensi.index') }}" class="text-primary-600 hover:underline">Potensi</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-600">Edit</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.potensi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
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
                <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.closest('div').remove()">
                    &times;
                </button>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-100 px-6 py-4">
            <h6 class="m-0 text-primary-600 font-semibold">Form Edit Potensi</h6>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.potensi.update', $potensi->id) }}" method="POST" enctype="multipart/form-data" id="potensiForm">
                @csrf
                @method('PUT')                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Nama Potensi -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Potensi <span class="text-red-600">*</span></label>
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama', $potensi->nama) }}"
                                   placeholder="Contoh: Pertanian Padi Organik"
                                   required
                                   class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') @enderror">
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Slug saat ini: <strong>{{ $potensi->slug }}</strong></p>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div>
                            <label for="deskripsi_singkat" class="block text-sm font-medium text-gray-700">Deskripsi Singkat <span class="text-red-600">*</span></label>
                            <textarea id="deskripsi_singkat"
                                      name="deskripsi_singkat"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Ringkasan singkat tentang potensi ini (max 500 karakter)"
                                      required
                                      class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_singkat')  @enderror">{{ old('deskripsi_singkat', $potensi->deskripsi_singkat) }}</textarea>
                            @error('deskripsi_singkat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1"><span id="charCount">0</span>/500 karakter</p>
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Lengkap <span class="text-red-600">*</span></label>
                            <textarea id="deskripsi"
                                      name="deskripsi"
                                      rows="15"
                                      class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') @enderror">{{ old('deskripsi', $potensi->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        
                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Gambar -->
                        <div>
                            <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>

                            @if($potensi->gambar)
                                <div class="mb-3" id="currentImageWrapper">
                                    <img src="{{ Storage::url($potensi->gambar) }}"
                                         alt="{{ $potensi->nama }}"
                                         id="currentImage"
                                         class="rounded-md shadow-sm w-full object-cover"
                                         style="max-height: 220px;">
                                    <p class="text-xs text-gray-500 mt-2">Gambar saat ini</p>
                                </div>
                            @endif

                            <input type="file"
                                   id="gambar"
                                   name="gambar"
                                   accept="image/*"
                                   class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gambar')  @enderror">
                            @error('gambar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-gray-500 mt-2">
                                Format: JPG, PNG, WEBP. Max: 2MB<br>
                                <em>Biarkan kosong jika tidak ingin mengubah gambar</em>
                            </p>

                            <!-- New Image Preview (kept hidden initially using inline style so jQuery.show() works) -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</label>
                                <img id="preview" src="" alt="Preview" class="rounded-md shadow-sm w-full object-cover" style="max-height: 220px;">
                                <button type="button" class="mt-3 inline-flex items-center px-3 py-1 bg-red-600 text-white rounded" id="removeImage">
                                    <i class="fas fa-times me-2"></i> Batalkan
                                </button>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-600">*</span></label>
                            <select id="kategori" name="kategori" required class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori')  @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="pertanian" {{ old('kategori', $potensi->kategori) == 'pertanian' ? 'selected' : '' }}>Pertanian</option>
                                <option value="peternakan" {{ old('kategori', $potensi->kategori) == 'peternakan' ? 'selected' : '' }}>Peternakan</option>
                                <option value="perikanan" {{ old('kategori', $potensi->kategori) == 'perikanan' ? 'selected' : '' }}>Perikanan</option>
                                <option value="umkm" {{ old('kategori', $potensi->kategori) == 'umkm' ? 'selected' : '' }}>UMKM</option>
                                <option value="wisata" {{ old('kategori', $potensi->kategori) == 'wisata' ? 'selected' : '' }}>Wisata</option>
                                <option value="kerajinan" {{ old('kategori', $potensi->kategori) == 'kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="lainnya" {{ old('kategori', $potensi->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi <span class="text-red-600">*</span></label>
                            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $potensi->lokasi) }}" placeholder="Contoh: Dusun Krajan" required class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lokasi')  @enderror">
                            @error('lokasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kontak (Optional) -->
                        <div>
                            <label for="kontak" class="block text-sm font-medium text-gray-700">Email<span class="text-xs text-gray-500">(Opsional)</span></label>
                            <input type="text" id="kontak" name="kontak" value="{{ old('kontak', $potensi->kontak) }}" placeholder="Contoh: email@gmail.com" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kontak')  @enderror">
                            @error('kontak')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
dr
                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp <span class="text-xs text-gray-500">(Opsional)</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-md text-gray-700">+62</span>
                                <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $potensi->whatsapp) }}" placeholder="8123456789" maxlength="15" pattern="[0-9]*" class="w-full px-4 py-2 border rounded-r-md border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('whatsapp')  @enderror">
                            </div>
                            @error('whatsapp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor tanpa awalan 0 atau +62. Contoh: 8123456789</p>
                        </div>

                        <!-- Status (di-comment, belum dibutuhkan) -->
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Publikasi</label>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $potensi->is_active) == 1 ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                    Aktifkan potensi ini (tampilkan di halaman public)
                                </label>
                            </div>
                            
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <small class="text-gray-500 text-xs mt-1 block">Jika dicentang, potensi ini akan ditampilkan di halaman publik</small>
                        </div> --}}
                        <input type="hidden" name="is_active" value="1">

                        <!-- Meta Info -->
                        <div class="bg-blue-50 border border-blue-100 rounded p-3 text-sm text-blue-700">
                            <strong>Info:</strong><br>
                            Dibuat: {{ $potensi->created_at->format('d/m/Y H:i') }}<br>
                            Terakhir diubah: {{ $potensi->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="border-t pt-4 mt-6 flex items-center justify-between">
                    <a href="{{ route('admin.potensi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-times me-2"></i> Batal
                    </a>

                    <div class="flex gap-3">
                        <button type="submit" name="action" value="publish" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <i class="fas fa-check me-2"></i> Perbarui & Publikasikan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
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
    $('#deskripsi_singkat').on('input', function() {
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
    $('#gambar').on('change', function(e) {
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
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
                $('#imagePreview').show();
                $('#currentImage').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove Image / Cancel new selection
    $('#removeImage').on('click', function() {
        $('#gambar').val('');
        $('#imagePreview').hide();
        $('#preview').attr('src', '');
        $('#currentImage').show();
    });

    // WhatsApp Number Validation
    $('#whatsapp').on('input', function() {
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
    $('button[name="action"]').on('click', function() {
        var action = $(this).val();
        // if (action === 'draft') {
        //     $('#status').val('draft');
        // } else
        if (action === 'publish') {
            $('#status').val('published');
        }
    });

    // Form Validation
    $('#potensiForm').on('submit', function(e) {
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
