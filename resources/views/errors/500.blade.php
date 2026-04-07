{{--
    ERROR 500 - INTERNAL SERVER ERROR
    
    Custom error page untuk server error
    
    DESIGN:
    - Split layout (2 columns)
    - Left: Error info dengan red-orange gradient
    - Right: Actions & retry options
    - Responsive: Stack to 1 column on mobile
    
    LEFT SECTION:
    - Logo desa dengan branding
    - Warning triangle dengan shake animation
    - Large 500 number
    - Error explanation (kesalahan server)
    - Server status info:
      * Rotating cog icon
      * "Sedang dalam perbaikan"
      * Estimasi waktu: 5-15 menit
    
    RIGHT SECTION:
    - Tools icon dengan pulse
    - "Kami Sedang Memperbaiki" message
    - Action buttons:
      * Muat Ulang Halaman (primary) - location.reload()
      * Kembali ke Beranda (outline)
      * Halaman Sebelumnya (outline) - history.back()
    - What You Can Do list:
      * Tunggu beberapa menit
      * Kembali ke halaman utama
      * Hubungi admin jika berlanjut
    - Report error link
    - Error reference ID (unique per error)
    
    ANIMATIONS:
    - shake: Triangle icon shakes (0.5s)
    - rotate-slow: Cog icon rotates (10s infinite)
    - Background gradient decorations
    
    WHEN TRIGGERED:
    - Unhandled exceptions
    - Database connection errors
    - File system errors
    - Syntax errors in production
    - Third-party API failures
    
    ERROR REFERENCE:
    - Unique ID: uniqid('ERR-')
    - Timestamp: date('Y-m-d H:i:s')
    - Useful untuk logging & debugging
    
    SEO:
    - meta robots: noindex, nofollow
    - Title: 500 - Terjadi Kesalahan Server
    
    PRODUCTION SETUP:
    - Enable APP_DEBUG=false untuk show this page
    - Log errors ke storage/logs/laravel.log
    - Set up error monitoring (Sentry, Bugsnag)
    - Email notifications untuk 500 errors
    
    CUSTOMIZATION:
    - Update contact email
    - Adjust estimated repair time
    - Add error tracking integration
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>500 - Terjadi Kesalahan Server | Desa Warurejo</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        
        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .rotate-slow {
            animation: rotate-slow 10s linear infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-orange-50 min-h-screen flex items-center justify-center p-4">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 right-10 w-72 h-72 bg-red-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse delay-1000"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 max-w-4xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Side - Error Info -->
                <div class="p-12 flex flex-col justify-center bg-gradient-to-br from-red-600 to-orange-600 text-white">
                    <div class="mb-8">
                        <!-- Logo -->
                        <div class="flex items-center space-x-3 mb-6">
                            <img src="{{ asset('images/Logo-Kabupaten.png') }}" alt="Logo Desa" class="h-16 w-16 bg-white rounded-full p-2">
                            <div>
                                <h2 class="text-2xl font-bold">Desa Warurejo</h2>
                                <p class="text-red-100 text-sm">Kabupaten Madiun</p>
                            </div>
                        </div>
                        
                        <!-- 500 Number with Icon -->
                        <div class="mb-6">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="shake-animation">
                                    <i class="fas fa-exclamation-triangle text-6xl text-yellow-300"></i>
                                </div>
                                <h1 class="text-7xl font-black text-white/90">500</h1>
                            </div>
                        </div>
                        
                        <h3 class="text-3xl font-bold mb-3">Terjadi Kesalahan Server</h3>
                        <p class="text-red-100 text-lg leading-relaxed mb-6">
                            Maaf, terjadi kesalahan pada server kami. 
                            Tim teknis kami telah diberitahu dan sedang bekerja untuk memperbaiki masalah ini.
                        </p>

                        <!-- Status Info -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="rotate-slow">
                                    <i class="fas fa-cog text-2xl text-white"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-sm">Status Server</div>
                                    <div class="text-red-100 text-xs">Sedang dalam perbaikan</div>
                                </div>
                            </div>
                            <div class="text-xs text-red-100">
                                <i class="fas fa-clock mr-1"></i>
                                Estimasi: 5-15 menit
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Actions -->
                <div class="p-12 flex flex-col justify-center bg-white">
                    <div class="text-center mb-8">
                        <div class="inline-block p-6 bg-red-50 rounded-full mb-6">
                            <i class="fas fa-tools text-6xl text-red-600"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-3">Kami Sedang Memperbaiki</h4>
                        <p class="text-gray-600 mb-8">
                            Mohon maaf atas ketidaknyamanan ini. 
                            Silakan coba beberapa saat lagi atau kembali ke halaman utama.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button 
                           onclick="location.reload()" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-sync-alt text-xl"></i>
                            <span>Muat Ulang Halaman</span>
                        </button>

                        <a href="{{ route('home') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-red-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-home text-xl text-red-600"></i>
                            <span>Kembali ke Beranda</span>
                        </a>

                        <button 
                           onclick="window.history.back()" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-red-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-arrow-left text-xl text-red-600"></i>
                            <span>Halaman Sebelumnya</span>
                        </button>
                    </div>

                    <!-- What You Can Do -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h5 class="font-semibold text-gray-800 mb-3 text-sm">Yang Bisa Anda Lakukan:</h5>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Tunggu beberapa menit dan coba lagi</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Kembali ke halaman utama</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                <span>Hubungi admin jika masalah berlanjut</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-500">
                            Masalah berlanjut? 
                            <a href="mailto:admin@warurejo.desa.id" class="text-red-600 hover:text-red-700 font-semibold hover:underline">
                                Laporkan ke Admin
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8">
            <p class="text-gray-600">
                <i class="fas fa-server mr-2"></i>
                Error Code: 500 - Internal Server Error
            </p>
            <p class="text-gray-500 text-sm mt-2">
                Reference ID: {{ uniqid('ERR-') }} | {{ date('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
