<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>404 - Nyasar Masbro? | Desa Warurejo</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-15px) rotate(2deg);
            }
        }

        @keyframes pulse-slow {
            0%, 100% {
                opacity: 0.6;
                transform: scale(1);
            }
            50% {
                opacity: 0.9;
                transform: scale(1.05);
            }
        }

        .float-anim {
            animation: float 4s ease-in-out infinite;
        }

        .pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Abstract Background Decor -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-emerald-200/40 blur-3xl pulse-slow"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-green-200/30 blur-3xl pulse-slow" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-4xl bg-white/80 backdrop-blur-md border border-slate-100 rounded-[32px] shadow-2xl p-8 md:p-12 relative z-10 grid md:grid-cols-12 gap-8 items-center">
        
        <!-- Left Illustration Col -->
        <div class="md:col-span-5 flex flex-col items-center justify-center text-center">
            <div class="float-anim relative">
                <!-- 404 Text Background Glow -->
                <span class="absolute inset-0 text-emerald-100/70 text-[10rem] font-extrabold -translate-y-8 select-none z-0">404</span>
                
                <!-- Main Icon -->
                <div class="relative z-10 w-44 h-44 bg-linear-to-tr from-emerald-500 to-green-600 rounded-[38px] shadow-xl shadow-emerald-500/20 flex items-center justify-center border border-white/20">
                    <i class="fa-solid fa-compass-drafting text-white text-7xl"></i>
                </div>

                <!-- Floating mini elements -->
                <span class="absolute -top-3 -right-3 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white text-sm shadow-md font-bold">?</span>
                <span class="absolute -bottom-2 -left-2 w-10 h-10 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-map-pin"></i>
                </span>
            </div>
            
            <div class="mt-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold uppercase tracking-wider border border-emerald-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    Halaman Tidak Ditemukan!
                </div>
            </div>
        </div>

        <!-- Right Content Col -->
        <div class="md:col-span-7 flex flex-col justify-between h-full">
            <div>
                <!-- Headline -->
                <span class="text-emerald-600 font-bold text-sm tracking-wide block mb-2 uppercase">Waduh, Gak Ada Angin Gak Ada Hujan...</span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                    Nyasar, Bolo?
                </h1>
                
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-6">
                    Halaman yang lu cari mendadak <span class="text-emerald-600 font-semibold underline decoration-wavy">ghosting</span> atau emang dari awal gak pernah ada. Mungkin typo ketik URL, atau emang jalurnya udah beda. Yuk, balik ke jalan yang benar!
                </p>

                <!-- Gen Z checklist pointers -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3">Analisis Masalah:</span>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-keyboard text-emerald-500"></i>
                            <span>Typo pas ngetik URL (coba cek lagi deh jempolnya)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-link-slash text-emerald-500"></i>
                            <span>Halamannya udah didelete atau pindah lapak</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-clover text-emerald-500"></i>
                            <span>Emang lagi apes aja nyasar ke mari</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-linear-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-2xl font-bold transition-all duration-300 shadow-lg shadow-emerald-500/10 hover:shadow-xl hover:shadow-emerald-500/20 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-house-chimney text-lg"></i>
                    <span>Balik Ke Home</span>
                </a>
                
                <a href="{{ route('berita.index') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-slate-200 hover:border-emerald-600 text-slate-700 hover:text-emerald-700 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-newspaper text-lg"></i>
                    <span>Cari Berita Terkini</span>
                </a>
            </div>

            <!-- Mini footer -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span>Desa Warurejo &copy; {{ date('Y') }}</span>
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    Error Code 404
                </span>
            </div>
        </div>

    </div>
</body>

</html>