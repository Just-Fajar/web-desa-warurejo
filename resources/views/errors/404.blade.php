{{--
    ERROR 404 - PAGE NOT FOUND
    
    Custom error page untuk halaman tidak ditemukan
    
    DESIGN:
    - Split layout (2 columns)
    - Left: Error info dengan green gradient
    - Right: Navigation options
    - Responsive: Stack to 1 column on mobile
    
    LEFT SECTION:
    - Logo desa dengan branding
    - Large 404 number dengan float animation
    - Error explanation (halaman tidak ditemukan)
    - Stats badges (tahun, layanan 24/7)
    
    RIGHT SECTION:
    - Map icon dengan pulse animation
    - "Ayo Kembali ke Jalur!" message
    - Navigation buttons:
      * Kembali ke Beranda (primary green)
      * Lihat Berita Desa (outline)
      * Potensi Desa (outline)
      * Galeri Desa (outline)
    - Help text dengan contact link
    
    ANIMATIONS:
    - float: 404 number moves up/down (3s infinite)
    - pulse-soft: Icon opacity animation (2s infinite)
    - Background gradient decorations
    
    BUTTONS:
    - Gradient green primary button
    - White outline hover buttons
    - Transform hover effect (lift -0.5px)
    - Icon + text combination
    
    SEO:
    - meta robots: noindex, nofollow (don't index error pages)
    - Proper title: 404 - Halaman Tidak Ditemukan
    
    USAGE:
    Laravel automatically shows this when:
    - Route not found
    - Model not found (404 abort)
    - Resource deleted
    
    CUSTOMIZATION:
    Edit text, colors, links as needed
    Maintain consistent branding
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>404 - Halaman Tidak Ditemukan | Desa Warurejo</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-green-50 min-h-screen flex items-center justify-center p-4">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-72 h-72 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse delay-1000"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 max-w-4xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 gap-0">
                <!-- Left Side - Error Info -->
                <div class="p-12 flex flex-col justify-center bg-gradient-to-br from-green-600 to-green-700 text-white">
                    <div class="mb-8">
                        <!-- Logo -->
                        <div class="flex items-center space-x-3 mb-6">
                            <img src="{{ asset('images/Logo-Kabupaten.png') }}" alt="Logo Desa" class="h-16 w-16 bg-white rounded-full p-2">
                            <div>
                                <h2 class="text-2xl font-bold">Desa Warurejo</h2>
                                <p class="text-green-100 text-sm">Kabupaten Madiun</p>
                            </div>
                        </div>
                        
                        <!-- 404 Number -->
                        <div class="float-animation">
                            <h1 class="text-8xl font-black mb-4 text-white/90">404</h1>
                        </div>
                        
                        <h3 class="text-3xl font-bold mb-3">Halaman Tidak Ditemukan</h3>
                        <p class="text-green-100 text-lg leading-relaxed">
                            Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                            Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
                        </p>
                    </div>

                    <!-- Stats (Optional) -->
                    <div class="grid grid-cols-2 gap-4 pt-6 border-t border-green-500/30">
                        <div>
                            <div class="text-3xl font-bold mb-1">{{ date('Y') }}</div>
                            <div class="text-green-100 text-sm">Tahun Ini</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold mb-1">24/7</div>
                            <div class="text-green-100 text-sm">Layanan Online</div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Navigation -->
                <div class="p-12 flex flex-col justify-center bg-white">
                    <div class="text-center mb-8">
                        <div class="inline-block p-6 bg-green-50 rounded-full mb-6 pulse-soft">
                            <i class="fas fa-map-marked-alt text-6xl text-green-600"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-3">Ayo Kembali ke Jalur!</h4>
                        <p class="text-gray-600 mb-8">
                            Jangan khawatir, kami akan membantu Anda menemukan jalan kembali
                        </p>
                    </div>

                    <!-- Navigation Links -->
                    <div class="space-y-3">
                        <a href="{{ route('home') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-home text-xl"></i>
                            <span>Kembali ke Beranda</span>
                        </a>

                        <a href="{{ route('berita.index') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-green-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-newspaper text-xl text-green-600"></i>
                            <span>Lihat Berita Desa</span>
                        </a>

                        <a href="{{ route('potensi.index') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-green-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-seedling text-xl text-green-600"></i>
                            <span>Potensi Desa</span>
                        </a>

                        <a href="{{ route('galeri.index') }}" 
                           class="flex items-center justify-center space-x-3 w-full px-6 py-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 hover:border-green-600 rounded-xl font-semibold transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-images text-xl text-green-600"></i>
                            <span>Galeri Desa</span>
                        </a>
                    </div>

                    <!-- Help Text -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-500">
                            Butuh bantuan? 
                            <a href="https://wa.me/6283114796959" 
   class="text-green-600 hover:text-green-700 font-semibold hover:underline">
    Hubungi Kami
</a>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8">
            <p class="text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Error Code: 404 - Page Not Found
            </p>
        </div>
    </div>
</body>
</html>
