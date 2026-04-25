@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')


    <!-- Hero + Navbar dalam satu section -->
    <section
        class="relative flex flex-col justify-center items-center bg-cover bg-center bg-no-repeat min-h-screen text-white"
        style="background-image: url('{{ asset('images/logo-web-desa.jpg') }}');">


        <!-- Overlay agar teks dan navbar kontras -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Navbar dimasukkan di sini -->
        <div class="absolute top-0 left-0 w-full z-20">
            @include('public.partials.navbar')
        </div>


        <!-- Hero Text -->
        <div class="relative z-10 container mx-auto px-4 py-24 text-center text-white scroll-reveal">

            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Selamat Datang di Website Resmi {{ $profil->nama_desa }}
            </h1>
            <h1 class="text-lg md:text-xl text-white">
                Kecamatan Balerejo, Kabupaten Madiun
            </h1>
        </div>
    </section>

    <!-- Stats Section Animated -->
    {{-- <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

                <!-- Item -->
                <div class="group text-center p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 scroll-reveal-stagger delay-100"
                    style="background-image: url('{{ asset('images/.png') }}'); background-size: cover; background-position: center;">
                    <div class="flex justify-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#E8F5E9" />
                            <path d="M10 32L24 18L38 32H10Z" fill="#81C784" />
                            <path d="M18 32V24H30V32" fill="#4CAF50" />
                            <path d="M21 32V27H27V32" fill="#2E7D32" />
                            <circle cx="34" cy="18" r="5" fill="#66BB6A" />
                            <circle cx="14" cy="20" r="3" fill="#A5D6A7" />
                        </svg>

                    </div>
                    <div class="text-4xl font-bold text-primary-600 mb-1 count" data-target="{{ $totalPotensi }}">0</div>
                    <div class="text-gray-600">Potensi Desa</div>
                </div>

                <div class="group text-center p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 scroll-reveal-stagger delay-200"
                    style="background-image: url('{{ asset('images/image-ber.png') }}'); background-size: cover; background-position: center;">
                    <div class="flex justify-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#E3F2FD" />
                            <rect x="10" y="12" width="28" height="24" rx="3" fill="white" stroke="#64B5F6"
                                stroke-width="2" />
                            <rect x="14" y="16" width="10" height="8" fill="#90CAF9" />
                            <rect x="26" y="16" width="10" height="2" fill="#BBDEFB" />
                            <rect x="26" y="20" width="10" height="2" fill="#BBDEFB" />
                            <rect x="14" y="27" width="22" height="2" fill="#90CAF9" />
                            <rect x="14" y="31" width="22" height="2" fill="#90CAF9" />
                        </svg>

                    </div>
                    <div class="text-4xl font-bold text-primary-600 mb-1 count" data-target="{{ $totalBerita }}">0</div>
                    <div class="text-gray-600">Berita Terbaru</div>
                </div>

                <div class="group text-center p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 scroll-reveal-stagger delay-300"
                    style="background-image: url('{{ asset('images/bg-stats.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="flex justify-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#FFF3E0" />
                            <rect x="10" y="16" width="28" height="20" rx="4" fill="#FFB74D" />
                            <circle cx="24" cy="26" r="7" fill="#FFF" />
                            <circle cx="24" cy="26" r="4" fill="#FF9800" />
                            <rect x="18" y="12" width="12" height="6" rx="2" fill="#F57C00" />
                        </svg>

                    </div>
                    <div class="text-4xl font-bold text-primary-600 mb-1 count" data-target="{{ $totalGaleri }}">0</div>
                    <div class="text-gray-600">Dokumentasi</div>
                </div>

                <div class="group text-center p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 scroll-reveal-stagger delay-400"
                    style="background-image: url('{{ asset('images/bg-stats.jpg') }}'); background-size: cover; background-position: center;">
                    <div class="flex justify-center mb-3">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="48" height="48" rx="10" fill="#EDE7F6" />
                            <circle cx="24" cy="18" r="7" fill="#9575CD" />
                            <path d="M12 36C12 28 19 26 24 26C29 26 36 28 36 36" fill="#B39DDB" />
                        </svg>

                    </div>
                    <div class="text-4xl font-bold text-primary-600 mb-1 count" data-target="{{ $totalVisitors }}">0</div>
                    <div class="text-gray-600">Pengunjung</div>
                </div>

            </div>
        </div>
    </section> --}}


    <!-- Sambutan Kepala Desa -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 scroll-reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-primary-700 mb-2">Selamat Datang</h2>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-start">

                    <!-- Photo (Mobile: Top, Desktop: Right) -->
                    <div class="lg:col-span-2 order-1 lg:order-2 scroll-reveal-right">
                        <div class="relative h-full max-w-sm mx-auto lg:max-w-none">
                            <!-- Yellow Corner Decorations -->
                            <div
                                class="absolute top-0 right-0 w-12 h-12 border-t-4 border-r-4 border-amber-400 rounded-tr-2xl">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-12 h-12 border-b-4 border-l-4 border-amber-400 rounded-bl-2xl">
                            </div>

                            <!-- Photo Container -->
                            <div
                                class="relative bg-gray-100 rounded-lg overflow-hidden shadow-2xl h-full min-h-[350px] lg:min-h-[450px] mt-6 mb-6 lg:mt-0 lg:mb-0">
                                <img src="{{ asset('images/kades_warurejo.jpg') }}"
                                    alt="Kepala Desa {{ $profil->nama_desa }}"
                                    class="max-w-xs md:max-w-sm lg:max-w-md h-auto mx-auto object-contain" loading="lazy"
                                    decoding="async">
                            </div>
                        </div>
                    </div>

                    <!-- Text Content (Mobile: Bottom, Desktop: Left) -->
                    <div class="lg:col-span-3 order-2 lg:order-1 scroll-reveal-left">
                        <h3
                            class="text-xl md:text-2xl lg:text-3xl font-bold text-primary-700 mb-4 text-center lg:text-left">
                            SAMBUTAN KEPALA {{ strtoupper($profil->nama_desa) }}
                        </h3>
                        <div class="w-20 h-1 bg-amber-400 mb-6 mx-auto lg:mx-0"></div>

                        <div class="text-gray-700 space-y-4 text-justify leading-relaxed text-sm md:text-base">
                            <p class="font-medium">Assalamu'alaikum warahmatullahi wabarakatuh,</p>
                            <p>Salam sejahtera bagi kita semua,</p>

                            <p>
                                Dengan penuh rasa syukur, saya menyambut seluruh warga serta para pengunjung di website
                                resmi {{ $profil->nama_desa }}.
                                Kehadiran website ini merupakan bagian dari komitmen kami dalam mewujudkan transparansi,
                                keterbukaan informasi publik, dan pelayanan yang lebih cepat serta mudah diakses oleh
                                masyarakat.
                            </p>

                            <p>
                                Pemerintah {{ $profil->nama_desa }} terus berupaya meningkatkan kualitas pelayanan,
                                pembangunan, dan pemberdayaan masyarakat.
                                Melalui pemanfaatan teknologi informasi, kami berharap website ini menjadi sarana yang
                                efektif dalam menyampaikan informasi, program kerja, kegiatan desa, serta wadah partisipasi
                                masyarakat dalam proses pembangunan.
                            </p>

                            <p>
                                Besar harapan kami, platform ini dapat memperkuat hubungan antara pemerintah desa dan
                                masyarakat, sekaligus menjadi ruang bersama untuk membangun {{ $profil->nama_desa }} yang
                                lebih maju, mandiri, dan sejahtera.
                                Semoga melalui sinergi dan kebersamaan, kita mampu menjadikan desa kita sebagai tempat yang
                                membawa manfaat bagi seluruh warga.
                            </p>

                            <p class="font-medium">Wassalamu'alaikum warahmatullahi wabarakatuh.</p>

                            <div class="mt-8">
                                <p class="text-lg md:text-xl font-bold text-gray-800">
                                    {{ strtoupper($profil->nama_kepala_desa ?? 'Sunarto') }}
                                </p>
                                <p class="text-gray-600">Kepala {{ $profil->nama_desa }}</p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- ============================
    B E R I T A T E R K I N I
    ============================= --}}
    <section class="py-16 md:py-24 bg-gray-50 content-section">
        <div class="container mx-auto px-4 main-content">

            <div class="mb-10 md:mb-12 section-header scroll-reveal">
                <p class="text-xs font-semibold text-gray-500 tracking-[0.2em] uppercase mb-3">Kabar Desa</p>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                    <div>
                        <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-none mb-4">Berita Terkini</h2>
                        <p class="text-gray-500 text-lg md:text-xl">Informasi dan kabar terbaru dari {{ $profil->nama_desa }}</p>
                    </div>
                    @if($latest_berita->count() > 0)
                        <a href="{{ route('berita.index') }}"
                            class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-full font-medium hover:bg-black transition-all shrink-0">
                            Lihat Semua Berita
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    @endif
                </div>
                <hr class="border-gray-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-stretch card-grid content-grid mobile-slider">
                @forelse($latest_berita as $berita)

                    {{-- NEW MODERN CARD DESIGN --}}
                    <div
                        class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all duration-300 group flex flex-col scroll-reveal-stagger ring-1 ring-black/5 hover:ring-primary-500/20">

                        {{-- Image Wrapper --}}
                        <a href="{{ route('berita.show', $berita->slug) }}"
                            class="relative block h-56 rounded-xl overflow-hidden mb-4">
                            <img src="{{ $berita->gambar_utama_url }}" alt="{{ $berita->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy" decoding="async">

                            {{-- Gradient Overlay --}}
                            <div
                                class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            {{-- Date Badge --}}
                            <div
                                class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $berita->published_at?->format('d M Y') }}
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="px-2 pb-2 flex flex-col grow">
                            {{-- Views --}}
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ number_format($berita->views) }} kali dilihat
                                </span>
                            </div>

                            {{-- Judul --}}
                            <a href="{{ route('berita.show', $berita->slug) }}" class="block mb-2">
                                <h3
                                    class="text-lg font-bold text-gray-800 leading-snug group-hover:text-primary-600 transition-colors line-clamp-2">
                                    {{ $berita->judul }}
                                </h3>
                            </a>

                            {{-- Excerpt Expandable --}}
                            <div class="relative mb-4 grow cursor-pointer group/desc"
                                onclick="this.classList.toggle('is-expanded')" title="Klik untuk memperluas teks">
                                <div
                                    class="text-sm text-gray-600 leading-relaxed overflow-hidden max-h-[2.8rem] transition-all duration-500 custom-scrollbar pr-2 desc-text">
                                    {{ $berita->excerpt }}
                                </div>
                                <div
                                    class="absolute bottom-0 right-0 bg-white/95 px-1.5 text-[10px] text-primary-600 font-semibold rounded opacity-0 group-hover/desc:opacity-100 transition-opacity fade-hint shadow-sm">
                                    Klik deskripsi</div>
                                <!-- Fade indicator di bawah teks -->
                                <div
                                    class="absolute bottom-0 left-0 w-full h-5 bg-linear-to-t from-white to-transparent opacity-100 pointer-events-none transition-opacity duration-300 fade-overlay">
                                </div>
                            </div>

                            {{-- Action Area --}}
                            <a href="{{ route('berita.show', $berita->slug) }}"
                                class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between group/action">
                                <span
                                    class="text-primary-600 font-semibold text-sm group-hover/action:text-primary-700 transition-colors">
                                    Baca Selengkapnya
                                </span>
                                <span
                                    class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center group-hover/action:bg-primary-600 group-hover/action:text-white transition-all duration-300 transform group-hover/action:translate-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada berita tersedia</p>
                    </div>
                @endforelse
            </div>

            {{-- Bottom button removed per user request (moved to top) --}}

        </div>
    </section>


    {{-- ============================
    P O T E N S I D E S A
    ============================= --}}
    <section class="py-16 md:py-24 bg-white content-section">
        <div class="container mx-auto px-4 main-content">

            <div class="mb-10 md:mb-12 section-header scroll-reveal">
                <p class="text-xs font-semibold text-gray-500 tracking-[0.2em] uppercase mb-3">Potensi Unggulan</p>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                    <div>
                        <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-none mb-4">Potensi Desa</h2>
                        <p class="text-gray-500 text-lg md:text-xl">Kekayaan dan potensi yang dimiliki {{ $profil->nama_desa }}</p>
                    </div>
                    @if($potensi->count() > 0)
                        <a href="{{ route('potensi.index') }}"
                            class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-full font-medium hover:bg-black transition-all shrink-0">
                            Lihat Semua Potensi
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                            </svg>
                        </a>
                    @endif
                </div>
                <hr class="border-gray-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 items-stretch card-grid content-grid mobile-slider">
                @forelse($potensi->take(3) as $item)

                    {{-- NEW MODERN CARD DESIGN --}}
                    <div
                        class="bg-white rounded-2xl p-3 border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all duration-300 group flex flex-col scroll-reveal-stagger ring-1 ring-black/5 hover:ring-primary-500/20">

                        {{-- Image Wrapper --}}
                        <a href="{{ route('potensi.show', $item->slug) }}"
                            class="relative block h-56 rounded-xl overflow-hidden mb-4">
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_potensi }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy" decoding="async">

                            {{-- Gradient Overlay --}}
                            <div
                                class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            {{-- Date Badge --}}
                            <div
                                class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-700 flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $item->created_at->format('d M Y') }}
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="px-2 pb-2 flex flex-col grow">
                            {{-- Views --}}
                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="bg-gray-50 text-gray-500 px-2.5 py-1 rounded-md text-xs font-medium flex items-center gap-1.5 border border-gray-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ number_format($item->views) }} kali dilihat
                                </span>
                            </div>

                            {{-- Nama Potensi --}}
                            <a href="{{ route('potensi.show', $item->slug) }}" class="block mb-2">
                                <h3
                                    class="text-lg font-bold text-gray-800 leading-snug group-hover:text-primary-600 transition-colors line-clamp-2">
                                    {{ $item->nama }}
                                </h3>
                            </a>

                            {{-- Deskripsi Expandable --}}
                            <div class="relative mb-4 grow cursor-pointer group/desc"
                                onclick="this.classList.toggle('is-expanded')" title="Klik untuk memperluas teks">
                                <div
                                    class="text-sm text-gray-600 leading-relaxed overflow-hidden max-h-[2.8rem] transition-all duration-500 custom-scrollbar pr-2 desc-text">
                                    {{ Str::limit(strip_tags($item->deskripsi), 300) }}
                                </div>
                                <div
                                    class="absolute bottom-0 right-0 bg-white/95 px-1.5 text-[10px] text-primary-600 font-semibold rounded opacity-0 group-hover/desc:opacity-100 transition-opacity fade-hint shadow-sm">
                                    Klik deskripsi</div>
                                <!-- Fade indicator di bawah teks -->
                                <div
                                    class="absolute bottom-0 left-0 w-full h-5 bg-linear-to-t from-white to-transparent opacity-100 pointer-events-none transition-opacity duration-300 fade-overlay">
                                </div>
                            </div>

                            {{-- Action Area --}}
                            <a href="{{ route('potensi.show', $item->slug) }}"
                                class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between group/action">
                                <span
                                    class="text-primary-600 font-semibold text-sm group-hover/action:text-primary-700 transition-colors">
                                    Selengkapnya
                                </span>
                                <span
                                    class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center group-hover/action:bg-primary-600 group-hover/action:text-white transition-all duration-300 transform group-hover/action:translate-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>

                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada data potensi</p>
                    </div>
                @endforelse
            </div>

            {{-- Bottom button removed per user request (moved to top) --}}

        </div>
    </section>



    <!-- Gallery Preview -->
    <section class="py-16 md:py-24 bg-white content-section relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 main-content max-w-7xl">
            <div class="mb-10 md:mb-12 section-header scroll-reveal">
                <p class="text-xs font-semibold text-gray-500 tracking-[0.2em] uppercase mb-3">Dokumentasi</p>
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                    <div>
                        <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-none mb-4">Galeri Desa</h2>
                        <p class="text-gray-500 text-lg md:text-xl">Potret keindahan, kegiatan warga, dan pembangunan di
                            {{ $profil->nama_desa }}.
                        </p>
                    </div>
                    <a href="{{ route('galeri.index') }}"
                        class="inline-flex items-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-full font-medium hover:bg-black transition-all shrink-0">
                        Lihat Semua Galeri
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </a>
                </div>
                <hr class="border-gray-200">
            </div>

            @php
                // Kategori Sesuai Permintaan
                $kategoriList = [
                    ['nama' => 'Kegiatan', 'desc' => 'Potret aktivitas dan kegiatan keseharian warga desa.', 'color' => 'bg-blue-500'],
                    ['nama' => 'Pembangunan', 'desc' => 'Perkembangan pembangunan dan fasilitas umum di desa.', 'color' => 'bg-amber-500'],
                    ['nama' => 'Budaya', 'desc' => 'Melestarikan warisan leluhur dan kegiatan kebudayaan warga.', 'color' => 'bg-rose-500'],
                    ['nama' => 'Keagamaan', 'desc' => 'Kegiatan keagamaan dan perayaan hari besar agama.', 'color' => 'bg-emerald-500'],
                    ['nama' => 'Sosial', 'desc' => 'Kegiatan sosial dan gotong royong masyarakat desa.', 'color' => 'bg-purple-500'],
                    ['nama' => 'Lainnya', 'desc' => 'Berbagai dokumentasi menarik dan momen spesial lainnya.', 'color' => 'bg-gray-400'],
                ];

                $galeriDisplay = [];
                // Melacak gambar yang sudah dipakai agar tidak diulang
                $usedIds = [];

                foreach ($kategoriList as $index => $kat) {
                    // Coba cocokan kategori/judul dengan kata kunci (Kegiatan, Pembangunan, dll)
                    $matched = $galeri->first(function ($item) use ($kat, $usedIds) {
                        if (in_array($item->id ?? $item->id_galeri ?? -1, $usedIds))
                            return false;
                        if ($kat['nama'] === 'Lainnya')
                            return false;

                        $search = strtolower(($item->kategori ?? '') . ' ' . strtolower($item->judul ?? ''));
                        $katLower = strtolower($kat['nama']);
                        $katWord = explode(' ', $katLower)[0];

                        return str_contains($search, $katLower) || str_contains($search, $katWord);
                    });

                    // Jika tidak ada yang cocok, ambil gambar galeri pertama yang belum terpakai
                    if (!$matched) {
                        $matched = $galeri->first(function ($item) use ($usedIds) {
                            return !in_array($item->id ?? $item->id_galeri ?? -1, $usedIds);
                        });
                    }

                    if ($matched) {
                        $usedIds[] = $matched->id ?? $matched->id_galeri ?? -1;
                    }

                    $galeriDisplay[] = [
                        'kategori' => $kat['nama'],
                        'desc' => $kat['desc'],
                        'color' => $kat['color'],
                        'image' => $matched ? $matched->gambar_url : asset('images/logo-web-desa.jpg'),
                        'judul' => $matched ? ($matched->judul ?? $kat['nama']) : $kat['nama']
                    ];
                }
            @endphp

            <div class="flex flex-col md:flex-row w-full h-[600px] gap-2 md:gap-4 scroll-reveal" id="gallery-accordion">
                @foreach($galeriDisplay as $index => $item)
                    <div class="gallery-item relative overflow-hidden rounded-2xl md:rounded-[2rem] transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] cursor-pointer {{ $index == 0 ? 'flex-[4] active' : 'flex-1' }}"
                        onmouseenter="activateGalleryItem(this)" onclick="openImageModal('{{ $item['image'] }}')">

                        <img src="{{ $item['image'] }}" alt="{{ $item['kategori'] }}"
                            class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-1000 gallery-img group-hover:scale-105">

                        {{-- Gradient Overlay --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-gray-900/10 transition-opacity duration-700">
                        </div>

                        {{-- Vertical Content (Muncul saat tidak di-hover) --}}
                        <div
                            class="content-vertical absolute inset-0 flex items-center justify-center md:items-end md:justify-center md:pb-12 transition-opacity duration-300 opacity-100">
                            <h3
                                class="text-white font-extrabold tracking-[0.3em] text-sm md:text-xl uppercase whitespace-nowrap glow-text writing-vertical">
                                {{ strtoupper($item['kategori']) }}
                            </h3>
                        </div>

                        {{-- Horizontal Content (Muncul saat di-hover/aktif) --}}
                        <div
                            class="content-horizontal absolute bottom-0 left-0 p-6 md:p-10 w-full bg-gradient-to-t from-black via-black/80 to-transparent flex flex-col justify-end opacity-0 transform translate-y-8 transition-all duration-500 delay-100 pointer-events-none">
                            <div class="flex items-center gap-4 mb-3 md:mb-4">
                                <div class="w-10 h-1 {{ $item['color'] }} rounded-full"></div>
                                <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white whitespace-nowrap truncate">
                                    {{ $item['kategori'] }}
                                </h3>
                            </div>
                            <p
                                class="text-gray-200 text-sm md:text-base line-clamp-2 md:line-clamp-3 md:pl-14 opacity-90 max-w-2xl font-medium">
                                {{ $item['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Bottom button removed per user request (moved to top) --}}
        </div>

        <!-- Image Modal -->
        <div id="imageModal"
            class="fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center hidden z-[100] transition-opacity">
            <div class="relative max-w-5xl w-full mx-4">
                <button onclick="closeImageModal()"
                    class="absolute -top-12 md:-top-10 right-0 text-white/70 hover:text-white text-4xl font-bold transition-colors">
                    &times;
                </button>
                <img id="modalImage" src="" class="w-full max-h-[85vh] object-contain rounded-xl shadow-2xl">
            </div>
        </div>

    </section>

    <style>
        /* Gallery Accordion Styles */
        .writing-vertical {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        @media (max-width: 767px) {
            .writing-vertical {
                writing-mode: horizontal-tb;
                transform: rotate(0deg);
                letter-spacing: 0.1em;
            }
        }

        .gallery-item.active .content-vertical {
            opacity: 0;
            pointer-events: none;
        }

        .gallery-item.active .content-horizontal {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .glow-text {
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.8), 0 0 40px rgba(0, 0, 0, 0.6);
        }
    </style>

    <script>
        function activateGalleryItem(element) {
            // Only apply hover logic if it's desktop, or if you want it on mobile too
            const items = document.querySelectorAll('.gallery-item');
            items.forEach(item => {
                item.classList.remove('flex-[4]', 'active');
                item.classList.add('flex-1');
            });
            element.classList.remove('flex-1');
            element.classList.add('flex-[4]', 'active');
        }
    </script>

    <style>
        /* Navbar slide-down animation */
        .navbar-slide-down {
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            0% {
                opacity: 0;
                transform: translateY(-100%);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scroll-triggered animations */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal-left.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal-right.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-reveal-stagger {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .scroll-reveal-stagger.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Delay classes for stagger effect */
        .delay-100 {
            transition-delay: 0.1s;
        }

        .delay-200 {
            transition-delay: 0.2s;
        }

        .delay-300 {
            transition-delay: 0.3s;
        }

        .delay-400 {
            transition-delay: 0.4s;
        }

        .delay-500 {
            transition-delay: 0.5s;
        }

        .delay-600 {
            transition-delay: 0.6s;
        }

        /* Footer animation */
        .scroll-reveal-footer {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-reveal-footer.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar for Card Description */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: #d1d5db;
        }

        /* Expanded Description Style */
        .is-expanded .desc-text {
            max-height: 12rem !important;
            overflow-y: auto !important;
        }

        .is-expanded .fade-overlay,
        .is-expanded .fade-hint {
            opacity: 0 !important;
            pointer-events: none;
        }

        /* Mobile Slider for Cards */
        @media (max-width: 767px) {
            .mobile-slider {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* IE/Edge */
                padding-bottom: 2rem;
                scroll-behavior: smooth;
                /* Negate mx-4 padding effect on sides for edge-to-edge scroll */
                margin-left: -1rem;
                margin-right: -1rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .mobile-slider::-webkit-scrollbar {
                display: none;
                /* Safari/Chrome */
            }

            .mobile-slider>* {
                flex: 0 0 85%;
                scroll-snap-align: center;
                height: auto;
            }
        }
    </style>

    <script>
        // Scroll-triggered animation observer
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                root: null,
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                    }
                });
            }, observerOptions);

            // Observe all elements with scroll-reveal classes
            document.querySelectorAll('.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-stagger, .scroll-reveal-footer').forEach(el => {
                observer.observe(el);
            });

            // Counter animation for stats
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                        entry.target.classList.add('counted');
                        const target = +entry.target.getAttribute("data-target");
                        let count = 0;

                        const update = () => {
                            const speed = 40;
                            if (count < target) {
                                count += Math.ceil(target / 40);
                                entry.target.textContent = count;
                                requestAnimationFrame(update);
                            } else {
                                entry.target.textContent = target.toLocaleString();
                            }
                        };
                        update();
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.count').forEach(el => {
                counterObserver.observe(el);
            });
        });
    </script>

    <script>
        function openImageModal(imageUrl) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            modalImage.src = imageUrl;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Tutup modal jika klik area gelap
        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target === this) closeImageModal();
        });
    </script>

@endsection