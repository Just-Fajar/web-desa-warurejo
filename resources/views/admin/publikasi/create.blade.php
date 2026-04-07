{{--
    ADMIN PUBLIKASI CREATE
    
    Form upload dokumen publikasi desa (APBDes, RPJMDes, RKPDes)
    
    FEATURES:
    - PDF file upload dengan validation
    - Kategori selection (APBDes/RPJMDes/RKPDes/Lainnya)
    - Tahun selection (dropdown atau manual input)
    - Rich text editor untuk deskripsi
    - File size & type validation
    - Active/draft status
    
    FORM FIELDS:
    - judul: Judul dokumen (required)
    - kategori: Dropdown kategori (required)
    - tahun: Year selection (required, current year default)
    - deskripsi: Rich text description (optional)
    - file: PDF upload (required, max 10MB)
    - tanggal_terbit: Date picker (default today)
    - is_active: Boolean toggle (default true)
    
    VALIDATION:
    - Judul max 255 chars
    - File must be PDF, max 10MB
    - Tahun numeric, 4 digits
    - Kategori in enum list
    
    FILE UPLOAD:
    - Only PDF allowed (mimes:pdf)
    - Max 10MB (configurable)
    - Stored in storage/publikasi/
    - Original filename preserved dengan timestamp
    
    JAVASCRIPT:
    - File input validation
    - File size check
    - TinyMCE initialization
    - Form validation
    
    Route: POST /admin/publikasi/store
    Controller: AdminPublikasiController@store
--}}
@extends('admin.layouts.app')

@section('title', 'Upload Publikasi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.publikasi.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Upload Publikasi Baru</h1>
            <p class="text-gray-600">Tambah dokumen publikasi desa</p>
        </div>
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

    <form action="{{ route('admin.publikasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('judul')  @enderror"
                               placeholder="Contoh: Anggaran Pendapatan dan Belanja Desa Tahun 2025"
                               required>
                        @error('judul')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Tahun -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="kategori" 
                                    name="kategori" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('kategori')  @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                <option value="APBDes" {{ old('kategori') == 'APBDes' ? 'selected' : '' }}>APBDes</option>
                                <option value="RPJMDes" {{ old('kategori') == 'RPJMDes' ? 'selected' : '' }}>RPJMDes</option>
                                <option value="RKPDes" {{ old('kategori') == 'RKPDes' ? 'selected' : '' }}>RKPDes</option>
                            </select>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tahun <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="tahun" 
                                   name="tahun" 
                                   value="{{ old('tahun', date('Y')) }}"
                                   min="2000"
                                   max="{{ date('Y') + 5 }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('tahun')  @enderror"
                                   required>
                            @error('tahun')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('deskripsi')  @enderror"
                                  placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Dokumen -->
                    <div class="mb-6">
                        <label for="file_dokumen" class="block text-sm font-semibold text-gray-700 mb-2">
                            File Dokumen (PDF) <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition">
                            <i class="fas fa-file-pdf text-4xl text-gray-400 mb-3"></i>
                            <input type="file" 
                                   id="file_dokumen" 
                                   name="file_dokumen" 
                                   accept=".pdf"
                                   class="hidden"
                                   onchange="displayFileName('file_dokumen', 'file-name')"
                                   required>
                            <label for="file_dokumen" class="cursor-pointer">
                                <span class="text-primary-600 hover:text-primary-700 font-semibold">Pilih File PDF</span>
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
                    <div class="mb-6">
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">
                            Thumbnail (Opsional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition">
                            <i class="fas fa-image text-4xl text-gray-400 mb-3"></i>
                            <input type="file" 
                                   id="thumbnail" 
                                   name="thumbnail" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="hidden"
                                   onchange="displayFileName('thumbnail', 'thumbnail-name')">
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
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Pengaturan Publikasi</h3>

                    <!-- Tanggal Publikasi -->
                    <div class="mb-4">
                        <label for="tanggal_publikasi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Publikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_publikasi" 
                               name="tanggal_publikasi" 
                               value="{{ old('tanggal_publikasi', date('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('tanggal_publikasi')  @enderror"
                               required>
                        @error('tanggal_publikasi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-600 focus:border-transparent @error('status')  @enderror"
                                required>
                            {{-- <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option> --}}
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        {{-- <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Draft tidak akan ditampilkan di halaman publik
                        </p> --}}
                    </div>

                    <!-- Submit Buttons -->
                    <div class="space-y-2">
                        <button type="submit" 
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Publikasi
                        </button>
                        <a href="{{ route('admin.publikasi.index') }}" 
                           class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function displayFileName(inputId, displayId) {
    const input = document.getElementById(inputId);
    const display = document.getElementById(displayId);
    
    if (input.files.length > 0) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
        display.textContent = `${fileName} (${fileSize} MB)`;
    } else {
        display.textContent = '';
    }
}
</script>
@endsection
