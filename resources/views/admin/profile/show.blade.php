{{--
    ADMIN PROFILE SHOW
    
    Display profile admin (read-only view)
    
    FEATURES:
    - Display avatar (photo atau initial)
    - Show nama, email, phone
    - Show account creation date
    - Show last login timestamp
    - Statistics (total berita/galeri created)
    - Edit profile button
    - Change password button
    
    SECTIONS:
    
    1. PROFILE CARD:
    - Large avatar display
    - Nama & jabatan
    - Email & phone
    - Member since date
    
    2. ACCOUNT INFO:
    - Last login timestamp
    - Account status (active)
    - Role (Admin)
    
    3. STATISTICS:
    - Total berita created
    - Total galeri uploaded
    - Recent activity
    
    4. ACTIONS:
    - Edit profile button (navigate to edit page)
    - Change password button
    - Logout button
    
    DATA:
    $admin: Current authenticated admin
    $totalBerita: Count berita by admin
    $totalGaleri: Count galeri by admin
    
    Route: /admin/profile
    Controller: AdminProfileController@show
--}}
@extends('admin.layouts.app')

@section('title', 'Profile')

@section('breadcrumb')
<li class="flex items-center text-gray-500 text-sm">
    <i class="fas fa-chevron-right mx-2 text-gray-400"></i> Profile
</li>
@endsection

@section('content')

<div class="px-4 py-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT CONTENT -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Profile Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-user-circle text-indigo-500 mr-2"></i>
                        Informasi Profil
                    </h2>
                    <a href="{{ route('admin.profile.edit') }}"
                        class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        <i class="fas fa-edit mr-1"></i> Edit Profil
                    </a>
                </div>

                <div class="p-6 space-y-4">

                    <!-- Row -->
                    <div>
                        <div class="text-gray-500 text-sm font-medium flex items-center">
                            <i class="fas fa-user mr-2"></i> Nama Lengkap
                        </div>
                        <div class="text-gray-800 font-semibold">
                            {{ $admin->name }}
                        </div>
                    </div>
                    <hr>

                    <div>
                        <div class="text-gray-500 text-sm font-medium flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Email
                        </div>
                        <div class="text-gray-800 font-semibold">
                            {{ $admin->email }}
                        </div>
                    </div>
                    <hr>

                    <div>
                        <div class="text-gray-500 text-sm font-medium flex items-center">
                            <i class="fas fa-calendar mr-2"></i> Terdaftar Sejak
                        </div>
                        <div class="text-gray-800 font-semibold">
                            {{ $admin->created_at->format('d F Y') }}
                        </div>
                    </div>

                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">

                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-lock text-yellow-500 mr-2"></i>
                        Ubah Password
                    </h2>
                </div>

                <div class="p-6">

                    <form method="POST" action="{{ route('admin.profile.update-password') }}" class="space-y-4" id="formPassword">
                        @csrf
                        @method('PUT')

                        <!-- Checkbox Lupa Password -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="lupaPassword" 
                                       name="lupa_password"
                                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-question-circle text-blue-500"></i>
                                    <strong>Lupa password lama?</strong> Centang ini untuk reset tanpa password lama
                                </span>
                            </label>
                        </div>

                        <div id="fieldPasswordLama">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Password Saat Ini <span class="text-red-500" id="requiredOld">*</span>
                            </label>
                            <input type="password"
                                id="current_password"
                                name="current_password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field Email (muncul saat lupa password) -->
                        <div id="fieldEmailVerifikasi" style="display:none;">
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Email Anda (untuk verifikasi) <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                id="email_verifikasi"
                                name="email_verifikasi"
                                value="{{ $admin->email }}"
                                placeholder="Masukkan email Anda"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                            <p class="text-gray-400 text-xs mt-1">Email harus sama dengan email akun Anda</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                name="password"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none @error('password') border-red-500 @enderror">
                            <p class="text-gray-400 text-sm">Minimal 8 karakter</p>
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password"
                                name="password_confirmation"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                        </div>

                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg font-semibold">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </button>

                    </form>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="space-y-6">

            <!-- Avatar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">

                @if($admin->avatar)
                    <div class="mx-auto mb-4 w-32 h-32">
                        <img src="{{ asset('storage/' . $admin->avatar) }}" 
                             alt="{{ $admin->name }}" 
                             class="w-full h-full rounded-full object-cover shadow-md border-4 border-gray-100">
                    </div>
                @else
                    <div class="mx-auto mb-4 flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 text-white text-5xl font-bold shadow-md">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                @endif

                <h3 class="text-lg font-semibold">{{ $admin->name }}</h3>
                <p class="text-gray-500">Administrator</p>

                <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Aktif
                </span>

            </div>

            <!-- Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                    Aktivitas
                </h4>

                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-500">Terakhir Login</span>
                    <span class="font-semibold text-gray-700">Hari ini</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Total Login</span>
                    <span class="font-semibold text-gray-700">-</span>
                </div>

            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
// Toggle field saat checkbox lupa password di klik
document.getElementById('lupaPassword').addEventListener('change', function() {
    const fieldPasswordLama = document.getElementById('fieldPasswordLama');
    const fieldEmailVerifikasi = document.getElementById('fieldEmailVerifikasi');
    const inputPasswordLama = document.getElementById('current_password');
    const inputEmailVerifikasi = document.getElementById('email_verifikasi');
    const requiredOld = document.getElementById('requiredOld');
    
    if (this.checked) {
        // Lupa password mode: sembunyikan password lama, tampilkan email
        fieldPasswordLama.style.display = 'none';
        fieldEmailVerifikasi.style.display = 'block';
        inputPasswordLama.required = false;
        inputEmailVerifikasi.required = true;
        inputPasswordLama.value = '';
    } else {
        // Normal mode: tampilkan password lama, sembunyikan email
        fieldPasswordLama.style.display = 'block';
        fieldEmailVerifikasi.style.display = 'none';
        inputPasswordLama.required = true;
        inputEmailVerifikasi.required = false;
    }
});
</script>
@endpush

@endsection
