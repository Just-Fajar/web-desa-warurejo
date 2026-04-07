{{--
    ADMIN LAYOUT TEMPLATE
    
    Master layout untuk seluruh halaman admin panel
    
    STRUKTUR:
    - Sidebar: Menu navigasi (fixed di desktop, toggle di mobile)
    - Header: Breadcrumb dan user info
    - Main Content: @yield('content') area
    
    DEPENDENCIES:
    - Alpine.js: Toggle sidebar di mobile (x-data, @click)
    - Font Awesome: Icons
    - TailwindCSS: Styling
    - SweetAlert2: Notifications (dimuat di parent pages)
    
    RESPONSIVE:
    - Mobile: Sidebar hidden, toggle dengan button
    - Desktop (lg+): Sidebar always visible
    
    USAGE:
    @extends('admin.layouts.app')
    @section('title', 'Dashboard')
    @section('content')
        <!-- Your content here -->
    @endsection
    
    NAVIGATION HIGHLIGHT:
    Menu aktif detect dengan request()->routeIs()
    Contoh: 'admin.berita.*' akan highlight semua route berita
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Desa Warurejo</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Animate.css for SweetAlert2 animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Alpine.js dari CDN (dimuat dulu sebelum vite) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
            id="sidebar">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
                    <i class="fas fa-home-lg-alt text-2xl mr-2"></i>
                    <h1 class="text-xl font-bold">Admin Warurejo</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-chart-line w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.berita.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.berita.*') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-newspaper w-5 mr-3"></i>
                        <span>Berita</span>
                    </a>

                    <a href="{{ route('admin.potensi.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.potensi.*') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-seedling w-5 mr-3"></i>
                        <span>Potensi Desa</span>
                    </a>

                    <a href="{{ route('admin.galeri.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.galeri.*') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-images w-5 mr-3"></i>
                        <span>Galeri</span>
                    </a>

                    <a href="{{ route('admin.publikasi.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.publikasi.*') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-file-pdf w-5 mr-3"></i>
                        <span>Publikasi</span>
                    </a>

                    <div class="border-t border-gray-200 my-2"></div>

                    <a href="{{ route('admin.struktur-organisasi.index') }}" 
                       class="flex items-center px-6 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.struktur-organisasi.*') ? 'bg-primary-50 text-primary-600 border-r-4 border-primary-600' : '' }}">
                        <i class="fas fa-sitemap w-5 mr-3"></i>
                        <span>Struktur Organisasi</span>
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="border-t border-gray-200 p-4">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm text-center text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Breadcrumb -->
                    <div class="flex-1">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                                        <i class="fas fa-home mr-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                @hasSection('breadcrumb')
                                    @yield('breadcrumb')
                                @endif
                            </ol>
                        </nav>
                    </div>
                    
                    <!-- Desktop User Dropdown -->
                    <div class="hidden lg:block" x-data="{ dropdownOpen: false }">
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" 
                                    class="flex items-center space-x-2 text-gray-700 hover:text-primary-600 focus:outline-none">
                                @if(auth()->guard('admin')->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->guard('admin')->user()->avatar) }}" 
                                         alt="{{ auth()->guard('admin')->user()->name }}" 
                                         class="w-8 h-8 rounded-full object-cover shadow-md border border-gray-200">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm font-medium">{{ auth()->guard('admin')->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="dropdownOpen" 
                                 @click.away="dropdownOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200"
                                 style="display: none;">
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit w-5 mr-3"></i>
                                    Edit Profil
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Global SweetAlert2 Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success notification
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    iconColor: '#22c55e',
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
                    showClass: {
                        popup: 'animate__animated animate__bounceIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOut'
                    },
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        confirmButton: 'px-6 py-2.5 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all'
                    }
                });
            @endif

            // Error notification
            @if(session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    iconColor: '#ef4444',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: '<i class="fas fa-times mr-2"></i>OK',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    },
                    customClass: {
                        confirmButton: 'px-6 py-2.5 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all'
                    }
                });
            @endif

            // Delete confirmation for all delete forms
            const deleteForms = document.querySelectorAll('.delete-form');
            
            deleteForms.forEach(form => {
                const deleteBtn = form.querySelector('.delete-btn');
                
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Determine content type from form action URL
                        const formAction = form.action;
                        let contentType = 'item';
                        let contentIcon = 'warning';
                        let contentColor = '#3b82f6';
                        
                        if (formAction.includes('/berita/')) {
                            contentType = 'berita';
                            contentColor = '#3b82f6';
                        } else if (formAction.includes('/potensi/')) {
                            contentType = 'potensi';
                            contentColor = '#22c55e';
                        } else if (formAction.includes('/galeri/')) {
                            contentType = 'galeri';
                            contentColor = '#a855f7';
                        } else if (formAction.includes('/publikasi/')) {
                            contentType = 'publikasi';
                            contentColor = '#f97316';
                        } else if (formAction.includes('/struktur-organisasi/')) {
                            contentType = 'anggota struktur';
                            contentColor = '#eab308';
                        } else if (formAction.includes('/admin/')) {
                            contentType = 'admin';
                            contentColor = '#ef4444';
                        }
                        
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            html: `Apakah Anda yakin ingin menghapus <strong>${contentType}</strong> ini?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan!</small>`,
                            icon: contentIcon,
                            iconColor: contentColor,
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                            reverseButtons: true,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp animate__faster'
                            },
                            backdrop: `
                                rgba(0,0,0,0.4)
                                left top
                                no-repeat
                            `,
                            customClass: {
                                confirmButton: 'px-6 py-2.5 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all',
                                cancelButton: 'px-6 py-2.5 text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Show loading state
                                Swal.fire({
                                    title: 'Menghapus...',
                                    html: 'Mohon tunggu sebentar',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    },
                                    showClass: {
                                        popup: 'animate__animated animate__zoomIn animate__faster'
                                    }
                                });
                                
                                // Submit the form
                                form.submit();
                            }
                        });
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
