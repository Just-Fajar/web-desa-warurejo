<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Desa Warurejo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Animate.css for SweetAlert2 animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Alpine.js dari CDN (dimuat dulu sebelum vite) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 admin-theme" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
            id="sidebar">
            <div class="flex flex-col h-full bg-white shadow-xl">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 bg-primary-600 text-white relative overflow-hidden">
                    <!-- Deco shapes -->
                    <div class="absolute -top-6 -left-6 w-20 h-20 bg-white opacity-10 rounded-full blur-xl"></div>
                    <div
                        class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary-400 opacity-30 rounded-full blur-2xl">
                    </div>

                    <div class="relative flex items-center gap-3 z-10">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center p-1.5">
                            <img src="{{ asset('images/Logo-Kabupaten.webp') }}" alt="Logo Kabupaten" class="w-full h-full object-contain">
                        </div>
                        <h1 class="text-xl font-bold tracking-wide">Desa<span
                                class="font-normal opacity-80">Warurejo</span></h1>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1.5 custom-scrollbar">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-pie text-sm"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <div class="py-2">
                        <hr class="border-gray-100">
                    </div>

                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Konten Website</p>

                    <a href="{{ route('admin.berita.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.berita.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.berita.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-newspaper text-sm"></i>
                        </div>
                        <span>Berita</span>
                    </a>

                    <a href="{{ route('admin.potensi.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.potensi.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.potensi.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-seedling text-sm"></i>
                        </div>
                        <span>Potensi Desa</span>
                    </a>

                    <a href="{{ route('admin.galeri.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.galeri.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.galeri.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-images text-sm"></i>
                        </div>
                        <span>Galeri</span>
                    </a>

                    <a href="{{ route('admin.publikasi.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.publikasi.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.publikasi.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </div>
                        <span>Publikasi</span>
                    </a>

                    <a href="{{ route('admin.struktur-organisasi.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.struktur-organisasi.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.struktur-organisasi.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-sitemap text-sm"></i>
                        </div>
                        <span>Struktur Organisasi</span>
                    </a>

                    <div class="py-2">
                        <hr class="border-gray-100">
                    </div>

                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Layanan Masyarakat</p>

                    <a href="{{ route('admin.pengaduan.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.pengaduan.*') ? 'bg-primary-50 text-primary-600 font-bold shadow-sm' : 'text-gray-700 font-medium hover:bg-primary-50 hover:text-primary-600' }}">
                        <div
                            class="{{ request()->routeIs('admin.pengaduan.*') ? 'bg-primary-100 text-primary-600 shadow-sm' : 'bg-white text-gray-500 shadow-sm group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors border border-gray-100' }} w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bullhorn text-sm"></i>
                        </div>
                        <span>Pengaduan</span>
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-3 text-sm font-semibold text-rose-500 hover:text-white bg-white hover:bg-rose-500 shadow-sm rounded-xl transition-all duration-200 flex items-center justify-center gap-2 border border-rose-100 hover:border-rose-500 group">
                            <i
                                class="fas fa-sign-out-alt transform group-hover:-translate-x-1 transition-transform"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border-b border-gray-100">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="flex-1">
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-primary-600 bg-gray-50 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-home mr-2 text-primary-500"></i>
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
                                class="flex items-center space-x-3 px-3 py-1.5 rounded-full border border-transparent hover:border-primary-100 hover:bg-primary-50/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-100 text-gray-700 hover:text-primary-700 group">
                                @if(auth()->guard('admin')->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->guard('admin')->user()->avatar) }}"
                                        alt="{{ auth()->guard('admin')->user()->name }}"
                                        class="w-8 h-8 rounded-full object-cover shadow-sm border-2 border-white group-hover:border-primary-100 transition-colors">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-bold text-sm shadow-sm border-2 border-white group-hover:border-primary-100 transition-colors">
                                        {{ substr(auth()->guard('admin')->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="text-sm font-semibold">{{ auth()->guard('admin')->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200"
                                style="display: none;">
                                <a href="{{ route('admin.profile.show') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user w-5 mr-3"></i>
                                    Profil
                                </a>
                                <a href="{{ route('admin.profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit w-5 mr-3"></i>
                                    Edit Profil
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
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

    <!-- SweetAlert2 & jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global SweetAlert2 Notifications -->
    <script @nonce>
        // Global image loading error fallback
        window.addEventListener('error', function(e) {
            if (e.target && e.target.tagName === 'IMG') {
                const fallback = e.target.getAttribute('data-fallback');
                if (fallback && e.target.src !== fallback) {
                    e.target.src = fallback;
                    return;
                }
                if (e.target.getAttribute('data-hide-on-error') === 'true') {
                    e.target.style.display = 'none';
                    if (e.target.parentElement) {
                        e.target.parentElement.classList.add('bg-gray-100');
                    }
                }
            }
        }, true);

        document.addEventListener('DOMContentLoaded', function () {
            // Global alert dismissal
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('alert-dismiss-btn')) {
                    e.target.parentElement.remove();
                }
            });

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
                    deleteBtn.addEventListener('click', function (e) {
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
                        } else if (formAction.includes('/pengaduan/')) {
                            contentType = 'pengaduan';
                            contentColor = '#f43f5e';
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

    <!-- Double-Submit Prevention: Disable button setelah klik pertama -->
    <script @nonce>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                if (form.classList.contains('delete-form') || form.getAttribute('method')?.toLowerCase() === 'get') return;

                let isSubmitting = false;

                form.addEventListener('submit', function(e) {
                    // Cegah submit kedua
                    if (isSubmitting) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        return false;
                    }

                    isSubmitting = true;

                    // Disable semua submit buttons di form ini
                    const submitBtns = form.querySelectorAll('[type="submit"]');
                    submitBtns.forEach(function(btn) {
                        btn.disabled = true;
                        btn.style.opacity = '0.7';
                        btn.style.cursor = 'not-allowed';

                        // Ganti text dengan loading spinner
                        const originalHTML = btn.innerHTML;
                        btn.dataset.originalHtml = originalHTML;
                        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                    });

                    // Timeout fallback: re-enable jika request gagal/lama
                    setTimeout(function() {
                        isSubmitting = false;
                        submitBtns.forEach(function(btn) {
                            btn.disabled = false;
                            btn.style.opacity = '';
                            btn.style.cursor = '';
                            if (btn.dataset.originalHtml) {
                                btn.innerHTML = btn.dataset.originalHtml;
                            }
                        });
                    }, 15000); // 15 detik timeout
                });
            });
        });
    </script>

    <!-- Real-time Auto-Publish Polling: Cek setiap 60 detik -->
    <script @nonce>
        (function() {
            const POLL_INTERVAL = 60000; // 60 detik
            const AUTO_PUBLISH_URL = '{{ route("admin.auto-publish") }}';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

            function checkAutoPublish() {
                fetch(AUTO_PUBLISH_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.published > 0) {
                        // Tampilkan notifikasi
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.published + ' konten berhasil dipublish otomatis',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            showClass: {
                                popup: 'animate__animated animate__slideInRight animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__slideOutRight animate__faster'
                            }
                        });

                        // Auto-reload halaman index jika sedang di halaman list
                        const currentPath = window.location.pathname;
                        const isIndexPage = currentPath.match(/\/admin\/(berita|potensi|galeri|publikasi)$/);
                        if (isIndexPage) {
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    }
                })
                .catch(err => {
                    // Silent fail — jangan ganggu user
                    console.log('Auto-publish check failed:', err.message);
                });
            }

            // Jalankan pertama kali setelah 10 detik, lalu setiap 60 detik
            setTimeout(checkAutoPublish, 10000);
            setInterval(checkAutoPublish, POLL_INTERVAL);
        })();
    </script>

    @stack('scripts')
</body>

</html>