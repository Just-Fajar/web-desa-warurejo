@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.profile.show') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600">
            Profile
        </a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Edit Profile</span>
    </li>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Sesuaikan informasi profil dan keamanan akun Anda</p>
            </div>
            <a href="{{ route('admin.profile.show') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        <!-- Card 1: Edit Profile (Foto & Nama) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-edit text-primary-600"></i>
                    Informasi Dasar
                </h2>
            </div>

            <div class="p-8">
                {{-- Photo Upload Section --}}
                <div class="mb-8 pb-8 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 mb-4">Foto Profil</h3>

                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        {{-- Current Photo Display --}}
                        <div class="flex-shrink-0">
                            <div id="currentPhotoContainer" class="relative group">
                                @if($admin->avatar)
                                    <img id="currentPhoto" src="{{ asset('storage/' . $admin->avatar) }}" alt="Foto Profil"
                                        class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                @else
                                    <div id="currentPhoto"
                                        class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-4 border-white shadow-lg">
                                        <span class="text-4xl font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                {{-- Delete Button --}}
                                @if($admin->avatar)
                                    <button type="button" onclick="deletePhoto()"
                                        class="absolute -top-2 -right-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full p-2.5 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-200"
                                        title="Hapus Foto">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Upload Controls --}}
                        <div class="flex-1">
                            <div class="space-y-4">
                                {{-- File Input (Hidden) --}}
                                <input type="file" id="photoInput" accept="image/jpeg,image/jpg,image/png" class="hidden">

                                {{-- Upload Button --}}
                                <button type="button" onclick="document.getElementById('photoInput').click()"
                                    class="px-5 py-2.5 bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white rounded-xl font-medium transition-all shadow-sm border border-primary-100 hover:border-primary-600 inline-flex items-center gap-2">
                                    <i class="fas fa-camera"></i>
                                    <span>{{ $admin->avatar ? 'Ubah Foto Profil' : 'Upload Foto Baru' }}</span>
                                </button>

                                {{-- Info Text --}}
                                <div class="flex gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1"><i class="fas fa-file-image text-gray-400"></i>
                                        JPG, PNG</span>
                                    <span class="flex items-center gap-1"><i
                                            class="fas fa-weight-hanging text-gray-400"></i> Max 2MB</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-expand text-gray-400"></i>
                                        400x400 px</span>
                                </div>

                                {{-- Preview Section (Hidden by default) --}}
                                <div id="previewSection"
                                    class="hidden mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <img id="previewImage" src="" alt="Preview"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-primary-400 shadow-sm">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-800 line-clamp-1" id="fileName"></p>
                                            <p class="text-xs text-gray-500 mt-0.5" id="fileSize"></p>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <button type="button" onclick="uploadPhoto()" id="btnUpload"
                                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                                                <i class="fas fa-check"></i> Upload
                                            </button>
                                            <button type="button" onclick="cancelPreview()"
                                                class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nama Form --}}
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                            class="w-full px-5 py-3 bg-white border border-gray-300 shadow-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('name') border-rose-300 ring-rose-100 @enderror"
                            placeholder="Masukkan nama lengkap Anda">
                        @error('name') <p class="text-sm text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-all shadow-sm flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Nama
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card 2: Keamanan Akun (Email & Password) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shield-alt text-emerald-500"></i>
                    Keamanan & Kredensial
                </h2>
            </div>

            <div class="p-8">
                <!-- Form Update Email -->
                <form action="{{ route('admin.profile.update') }}" method="POST" class="mb-10">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-4">Email ini digunakan untuk login ke dashboard admin.</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1 relative">
                                <span class="absolute left-4 top-3.5 text-gray-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                                    class="w-full pl-11 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('email') border-rose-300 ring-rose-100 @enderror">
                            </div>
                            <button type="submit"
                                class="px-6 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-all shadow-sm whitespace-nowrap flex justify-center items-center gap-2">
                                <i class="fas fa-sync-alt text-primary-600"></i> Perbarui Email
                            </button>
                        </div>
                        @error('email') <p class="text-sm text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </form>

                <div class="relative border-t border-gray-100 mb-10">
                    <div
                        class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-white px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        Ubah Password
                    </div>
                </div>

                <!-- Form Update Password -->
                <form action="{{ route('admin.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Password Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-400">
                                    <i class="fas fa-unlock-alt"></i>
                                </span>
                                <input type="password" name="current_password" required
                                    class="w-full pl-11 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('current_password') border-rose-300 ring-rose-100 @enderror"
                                    placeholder="Masukkan password saat ini untuk memverifikasi">
                            </div>
                            @error('current_password') <p class="text-sm text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    Password Baru <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-400">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" name="password" required
                                        class="w-full pl-11 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium @error('password') border-rose-300 ring-rose-100 @enderror"
                                        placeholder="Minimal 8 karakter">
                                </div>
                                @error('password') <p class="text-sm text-rose-600 mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    Konfirmasi Password Baru <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-400">
                                        <i class="fas fa-check-double"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full pl-11 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm font-medium"
                                        placeholder="Ketik ulang password baru">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-medium transition-all shadow-sm flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Simpan Password Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        let selectedFile = null;

        // Handle file selection
        document.getElementById('photoInput').addEventListener('change', function (e) {
            const file = e.target.files[0];

            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Tidak Valid',
                    text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran file maksimal 2 MB',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            selectedFile = file;

            // Show preview
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('previewSection').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        // Upload photo
        function uploadPhoto() {
            if (!selectedFile) return;

            const formData = new FormData();
            formData.append('photo', selectedFile);
            formData.append('_token', '{{ csrf_token() }}');

            // Disable upload button
            const btnUpload = document.getElementById('btnUpload');
            const originalContent = btnUpload.innerHTML;
            btnUpload.disabled = true;
            btnUpload.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Uploading...';

            fetch('{{ route("admin.profile.update-photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#10b981',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        }).then(() => {
                            // Update current photo
                            const currentPhoto = document.getElementById('currentPhoto');
                            if (currentPhoto.tagName === 'IMG') {
                                currentPhoto.src = data.photo_url;
                            } else {
                                // Replace placeholder with image
                                const container = document.getElementById('currentPhotoContainer');
                                container.innerHTML = `
                            <img id="currentPhoto" 
                                 src="${data.photo_url}" 
                                 alt="Foto Profil" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow-md">
                            <button type="button" 
                                    onclick="deletePhoto()"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    title="Hapus Foto">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        `;
                            }

                            // Reset preview
                            cancelPreview();

                            // Reload page to update header avatar
                            setTimeout(() => location.reload(), 1000);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengupload foto',
                        confirmButtonColor: '#ef4444'
                    });
                    console.error('Error:', error);
                })
                .finally(() => {
                    btnUpload.disabled = false;
                    btnUpload.innerHTML = originalContent;
                });
        }

        // Delete photo
        function deletePhoto() {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: 'Foto profil Anda akan dihapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.profile.delete-photo") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    confirmButtonColor: '#10b981',
                                    showClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutUp'
                                    }
                                }).then(() => {
                                    // Replace image with placeholder
                                    const container = document.getElementById('currentPhotoContainer');
                                    container.innerHTML = `
                                <div id="currentPhoto" class="w-32 h-32 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center border-4 border-gray-200 shadow-md">
                                    <span class="text-4xl font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                                </div>
                            `;

                                    // Reload page to update header avatar
                                    setTimeout(() => location.reload(), 1000);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message,
                                    confirmButtonColor: '#ef4444'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus foto',
                                confirmButtonColor: '#ef4444'
                            });
                            console.error('Error:', error);
                        });
                }
            });
        }

        // Cancel preview
        function cancelPreview() {
            selectedFile = null;
            document.getElementById('photoInput').value = '';
            document.getElementById('previewSection').classList.add('hidden');
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>

@endsection