{{--
    ERROR 403 - FORBIDDEN ACCESS
    
    Custom error page untuk akses ditolak
    
    DESIGN:
    - Split layout (2 columns)
    - Left: Error info dengan red gradient
    - Right: Navigation & access options
    - Responsive: Stack to 1 column on mobile
    
    LEFT SECTION:
    - Logo desa dengan branding
    - Lock icon dengan shake animation
    - Large 403 number
    - Error explanation (akses ditolak)
    - Protected page info dengan shield icon
    - Possible causes list:
      * Belum login sebagai admin
      * Hak akses tidak mencukupi
      * Halaman khusus administrator
    
    RIGHT SECTION:
    - Hand stop icon dengan pulse
    - "Anda Tidak Diizinkan" message
    - Navigation buttons:
      * Kembali ke Beranda (primary red)
      * Login Admin (outline) - ke /admin/login
      * Halaman Sebelumnya (outline) - history.back()
    - Public pages grid:
      * Berita, Potensi, Galeri, Profil
    - Contact administrator link
    
    ANIMATIONS:
    - lock-shake: Lock icon shakes (0.5s infinite)
    - pulse-border: Border color pulse (2s infinite)
    - Background gradient decorations
    
    WHEN TRIGGERED:
    - Accessing admin routes without authentication
    - Insufficient permissions
    - Protected resources
    - Manual abort(403) in controllers
    
    MIDDLEWARE:
    - AdminAuthenticate middleware triggers this
    - Can be customized in middleware
    
    SEO:
    - meta robots: noindex, nofollow
    - Title: 403 - Akses Ditolak
    
    CUSTOMIZATION:
    - Update login route if changed
    - Modify available public pages
    - Adjust contact email
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 - Akses Ditolak | Desa Warurejo</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @keyframes lock-shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }
        
        .lock-animation {
            animation: lock-shake 0.5s ease-in-out infinite;
        }
        
        @keyframes pulse-border {
            0%, 100% { border-color: rgb(239, 68, 68); }
            50% { border-color: rgb(239, 68, 68, 0.5); }
        }
        
        .pulse-border {
            animation: pulse-border 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-50 min-h-screen flex items-center justify-center p-4">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-red-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse delay-1000"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 max-w-4xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Side - Error Info -->
                <div class="p-12 flex flex-col justify-center bg-gradient-to-br from-red-600 to-red-700 text-white">
                    <div class="mb-8">
                        <!-- Logo -->
                        <div class="flex items-center space-x-3 mb-6">
                            <img src="{{ asset('images/Logo-Kabupaten.png') }}" alt="Logo Desa" class="h-16 w-16 bg-white rounded-full p-2">
                            <div>
                                <h2 class="text-2xl font-bold">Desa Warurejo</h2>
                                <p class="text-red-100 text-sm">Kabupaten Madiun</p>
                            </div>
                        </div>
                        
                        <!-- 403 Number with Lock Icon -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="lock-animation">
                                    <i class="fas fa-lock text-6xl text-white/90"></i>
                                </div>
                                <h1 class="text-7xl font-black text-white/90">403</h1>
                            </div>
                        </div>
                        
                        <h3 class="text-3xl font-bold mb-3">Akses Ditolak</h3>
                        <p class="text-red-100 text-lg leading-relaxed mb-6">
                            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                            Halaman ini hanya dapat diakses oleh pengguna dengan hak akses tertentu.
                        </p>

                        <!-- Access Info -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 pulse-border">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-shield-alt text-2xl text-white mt-1"></i>
                                <div>
                                    <div class="font-semibold text-sm mb-2">Halaman Terlindungi</div>
                                    <p class="text-red-100 text-xs leading-relaxed">
                                        Halaman ini dilindungi untuk menjaga keamanan dan privasi data. 
                                        Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Why Blocked -->
                    <div class="pt-6 border-t border-red-500/30">
                        <h5 class="font-semibold text-sm mb-3">Kemungkinan Penyebab:</h5>
                        <ul class="space-y-2 text-sm text-red-100">
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-times-circle mt-0.5"></i>
                                <span>Anda belum login sebagai admin</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-times-circle mt-0.5"></i>
                                <span>Hak akses Anda tidak mencukupi</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-times-circle mt-0.5"></i>
                                <span>Halaman khusus untuk administrator</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side - Navigation -->
                <div class="p-12 flex flex-col justify-center bg-white">
                    <div class="text-center mb-8">
                        <div class="inline-block p-6 bg-red-50 rounded-full mb-6">
                            <i class="fas fa-hand-paper text-6xl text-red-600"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-3">Anda Tidak Diizinkan</h4>
                        <p class="text-gray-600 mb-8">
                            Silakan kembali ke area publik atau login sebagai admin jika Anda memiliki akun
                        </p>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-y-3">
                        <a href="{{ route('home') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-home text-xl"></i>
                            <span>Kembali ke Beranda</span>
                        </a>

                        <a href="{{ route('admin.login') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-red-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt text-xl text-red-600"></i>
                            <span>Login Admin</span>
                        </a>

                        <button 
                           onclick="window.history.back()" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-red-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-arrow-left text-xl text-red-600"></i>
                            <span>Halaman Sebelumnya</span>
                        </button>
                    </div>

                    <!-- Public Pages -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h5 class="font-semibold text-gray-800 mb-3 text-sm">Halaman Publik yang Tersedia:</h5>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('berita.index') }}" class="flex items-center space-x-2 px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                                <i class="fas fa-newspaper text-green-600"></i>
                                <span>Berita</span>
                            </a>
                            <a href="{{ route('potensi.index') }}" class="flex items-center space-x-2 px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                                <i class="fas fa-seedling text-green-600"></i>
                                <span>Potensi</span>
                            </a>
                            <a href="{{ route('galeri.index') }}" class="flex items-center space-x-2 px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                                <i class="fas fa-images text-green-600"></i>
                                <span>Galeri</span>
                            </a>
                            <a href="{{ route('profil.sejarah') }}" class="flex items-center space-x-2 px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm text-gray-700 transition">
                                <i class="fas fa-info-circle text-green-600"></i>
                                <span>Profil</span>
                            </a>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-500">
                            Butuh bantuan? 
                            <a href="mailto:admin@warurejo.desa.id" class="text-red-600 hover:text-red-700 font-semibold hover:underline">
                                Hubungi Administrator
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8">
            <p class="text-gray-600">
                <i class="fas fa-ban mr-2"></i>
                Error Code: 403 - Forbidden Access
            </p>
        </div>
    </div>
</body>
</html>
