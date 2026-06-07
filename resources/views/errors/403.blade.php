<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 - Lu Gak Punya Akses, Rill! | Desa Warurejo</title>

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

        @keyframes shake {
            0%, 100% {
                transform: translateX(0) rotate(0deg);
            }
            15% {
                transform: translateX(-8px) rotate(-3deg);
            }
            30% {
                transform: translateX(6px) rotate(2deg);
            }
            45% {
                transform: translateX(-6px) rotate(-2deg);
            }
            60% {
                transform: translateX(4px) rotate(1deg);
            }
            75% {
                transform: translateX(-2px) rotate(-1deg);
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

        .shake-anim {
            animation: shake 0.8s ease-in-out infinite;
            animation-delay: 2s;
        }

        .pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Abstract Background Decor -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-red-200/40 blur-3xl pulse-slow"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-rose-200/30 blur-3xl pulse-slow" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-4xl bg-white/80 backdrop-blur-md border border-slate-100 rounded-[32px] shadow-2xl p-8 md:p-12 relative z-10 grid md:grid-cols-12 gap-8 items-center">
        
        <!-- Left Illustration Col -->
        <div class="md:col-span-5 flex flex-col items-center justify-center text-center">
            <div class="shake-anim relative">
                <!-- 403 Text Background Glow -->
                <span class="absolute inset-0 text-red-100/70 text-[10rem] font-extrabold -translate-y-8 select-none z-0">403</span>
                
                <!-- Main Icon -->
                <div class="relative z-10 w-44 h-44 bg-linear-to-tr from-red-500 to-rose-600 rounded-[38px] shadow-xl shadow-red-500/20 flex items-center justify-center border border-white/20">
                    <i class="fa-solid fa-user-shield text-white text-7xl"></i>
                </div>

                <!-- Floating mini elements -->
                <span class="absolute -top-3 -right-3 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white text-sm shadow-md font-bold">!</span>
                <span class="absolute -bottom-2 -left-2 w-10 h-10 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-lock"></i>
                </span>
            </div>
            
            <div class="mt-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 text-red-700 text-xs font-semibold uppercase tracking-wider border border-red-100">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    Akses Ditolak!
                </div>
            </div>
        </div>

        <!-- Right Content Col -->
        <div class="md:col-span-7 flex flex-col justify-between h-full">
            <div>
                <!-- Headline -->
                <span class="text-red-600 font-bold text-sm tracking-wide block mb-2 uppercase">Eits, Jangan Asal Terobos...</span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                    Lu Gak Punya Akses, Rill!
                </h1>
                
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-6">
                    Halaman ini <span class="text-red-600 font-semibold underline decoration-wavy">private banget</span>, bukan buat konsumsi publik. Lu harus login admin dulu atau emang akun lu belum dikasih izin buat lewat sini. Gak usah maksain masuk, entar kena mental!
                </p>

                <!-- Gen Z checklist pointers -->
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3">Penyebab Utama:</span>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-sign-in-alt text-red-500"></i>
                            <span>Lu belum login admin (login dulu gih, gass!)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-key text-red-500"></i>
                            <span>Role / Hak akses lu kurang tinggi buat nembus pintu ini</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-eye-slash text-red-500"></i>
                            <span>Halaman rahasia internal admin Desa Warurejo</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-linear-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-2xl font-bold transition-all duration-300 shadow-lg shadow-red-500/10 hover:shadow-xl hover:shadow-red-500/20 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-house-chimney text-lg"></i>
                    <span>Balik Ke Home</span>
                </a>

                <button id="backBtn" 
                   class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border-2 border-slate-200 hover:border-red-600 text-slate-700 hover:text-red-700 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-arrow-left text-lg text-red-600"></i>
                    <span>Kembali</span>
                </button>
            </div>

            <!-- Mini footer -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span>Desa Warurejo &copy; {{ date('Y') }}</span>
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    Error Code 403
                </span>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backBtn = document.getElementById('backBtn');
            if (backBtn) {
                backBtn.addEventListener('click', function() {
                    window.history.back();
                });
            }
        });
    </script>
</body>

</html>