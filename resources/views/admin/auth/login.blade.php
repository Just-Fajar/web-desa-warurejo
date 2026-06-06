<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Warurejo</title>
    <!-- Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/Logo-Kabupaten.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-animated {
            background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-glass {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-glass:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #34d399;
            /* emerald-400 */
            box-shadow: 0 0 15px rgba(52, 211, 153, 0.3);
            outline: none;
        }

        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Custom scrollbar to avoid weird shifts */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #064e3b;
        }

        ::-webkit-scrollbar-thumb {
            background: #34d399;
            border-radius: 4px;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-animated text-white relative overflow-hidden">

    <!-- Decorative background elements -->
    <div
        class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 pointer-events-none">
    </div>
    <div
        class="fixed top-[20%] right-[-10%] w-[500px] h-[500px] bg-teal-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-50 pointer-events-none">
    </div>
    <div
        class="fixed bottom-[-20%] left-[20%] w-[500px] h-[500px] bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 pointer-events-none">
    </div>

    <!-- Main Container -->
    <div
        class="w-full max-w-[1000px] flex rounded-[2rem] glass-panel shadow-2xl relative z-10 mx-4 overflow-hidden transform">

        <!-- Left Side: Branding / Welcome (Hidden on mobile) -->
        <div
            class="hidden lg:flex w-1/2 p-12 flex-col justify-between border-r border-white/10 relative overflow-hidden group">
            <!-- Background Image overlay for left side -->
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2832&auto=format&fit=crop')] bg-cover bg-center opacity-20 mix-blend-overlay group-hover:scale-105 transition-transform duration-1000">
            </div>
            <!-- Gradient overlay for better text readability -->
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-900/40 to-transparent"></div>

            <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('images/Logo-Kabupaten.webp') }}" alt="Logo"
                    class="w-10 h-10 object-contain drop-shadow-lg">
                <span class="text-xl font-bold tracking-wide">SID - Warurejo</span>
            </div>

            <div class="relative z-10 mt-12 mb-auto">
                <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-6">
                    Sistem<br>Informasi<br><span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-300">Desa</span>
                </h1>
                <p class="text-emerald-50 text-lg max-w-md font-light leading-relaxed">
                    Kelola data, informasi, dan pelayanan masyarakat desa dengan lebih efisien dan terintegrasi.
                </p>

                <div class="mt-8 flex gap-4">
                    <div class="w-12 h-1 bg-emerald-400 rounded-full"></div>
                    <div class="w-4 h-1 bg-emerald-400/30 rounded-full"></div>
                    <div class="w-4 h-1 bg-emerald-400/30 rounded-full"></div>
                </div>
            </div>

            <div class="relative z-10 mt-8">
                <p
                    class="text-sm text-emerald-100/60 inline-flex items-center gap-2 bg-black/20 fit-content px-4 py-2 rounded-full backdrop-blur-md border border-white/5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    Akses Khusus Administrator
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 p-8 sm:p-10 lg:p-12 relative bg-white/5">
            <div class="max-w-md mx-auto h-full flex flex-col justify-center">

                <!-- Mobile Header (Visible only on mobile) -->
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/Logo-Kabupaten.webp') }}" alt="Logo"
                        class="w-16 h-16 mx-auto mb-4 drop-shadow-lg">
                    <h2 class="text-2xl font-bold text-white">Login Admin</h2>
                    <p class="text-emerald-100/70 text-sm mt-1">Desa Warurejo</p>
                </div>

                <div class="hidden lg:block mb-10">
                    <h2 class="text-3xl font-bold text-white tracking-tight mb-2">Selamat Datang!</h2>
                    <p class="text-emerald-100/70">Silakan masuk untuk melanjutkan.</p>
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/50 text-emerald-100 text-sm flex items-start gap-3 backdrop-blur-sm animate-[fadeIn_0.5s_ease]">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div
                        class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-100 text-sm backdrop-blur-sm animate-[fadeIn_0.5s_ease]">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6 relative z-10">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-emerald-50 mb-2">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-emerald-200/50 group-focus-within:text-emerald-400 transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                placeholder="Masukkan Email"
                                class="input-glass w-full pl-12 pr-4 py-3.5 rounded-xl text-sm">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-medium text-emerald-50">Password</label>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-emerald-200/50 group-focus-within:text-emerald-400 transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="input-glass w-full pl-12 pr-12 py-3.5 rounded-xl text-sm">
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mt-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" id="remember" name="remember" class="peer sr-only">
                                <div
                                    class="w-5 h-5 rounded border border-white/20 bg-white/5 peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all duration-200">
                                </div>
                                <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm text-emerald-100/80 group-hover:text-white transition-colors">Ingat
                                saya</span>
                        </label>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full mt-8 py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 
                               hover:from-emerald-500 hover:to-teal-400 text-white font-bold text-sm tracking-wide
                               shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            MASUK SEKARANG
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </form>

                <!-- Back Link -->
                <div class="mt-8 text-center border-t border-white/10 pt-6 relative z-10">
                    <a href="{{ route('home') }}"
                        class="text-sm text-emerald-100/60 hover:text-white inline-flex items-center gap-2 transition-colors group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform duration-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Small footer -->
    <div class="absolute bottom-6 text-center w-full z-10 pointer-events-none">
        <p class="text-xs text-emerald-100/40 tracking-wider">
            &copy; {{ date('Y') }} Sistem Informasi Desa Warurejo. All rights reserved.
        </p>
    </div>

    </style>
</body>

</html>