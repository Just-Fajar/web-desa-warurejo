@extends('public.layouts.app')

@section('title', 'Struktur Organisasi - Desa Warurejo')

@section('content')
    {{-- Hero Section --}}
    <section class="pt-32 pb-8 bg-gray-50 border-b border-gray-100 relative overflow-hidden">
        {{-- Decorative Background Elements --}}
        <div
            class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-teal-100 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-teal-50 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl scroll-reveal">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-0.5 w-8 bg-teal-600"></div>
                    <span class="text-teal-600 font-bold uppercase tracking-wider text-sm">Pemerintahan</span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
                    Struktur <span class="text-teal-600">Organisasi</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-600 max-w-2xl leading-relaxed font-medium">
                    Susunan Organisasi Pemerintahan Desa Warurejo.
                </p>
            </div>
        </div>
    </section>

    {{-- Struktur Organisasi Content --}}
    <section class="py-12 sm:py-16 md:py-24 bg-white relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">

            {{-- HEADER STRUKTUR --}}
            <div class="flex flex-col items-center justify-center mb-12 sm:mb-16 scroll-reveal">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
                    <span class="text-primary-600 font-bold uppercase tracking-wider text-sm sm:text-base">Pemerintahan
                        Desa</span>
                    <div class="h-0.5 w-12 sm:w-16 bg-primary-600"></div>
                </div>

                <h2
                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 text-center leading-tight mb-6 sm:mb-8 max-w-4xl">
                    Struktur Organisasi dan Tata Kerja
                </h2>

                <p
                    class="text-gray-500 italic text-center max-w-3xl mx-auto text-base sm:text-lg md:text-xl leading-relaxed mt-2">
                    "Struktur organisasi ini disusun berdasarkan pedoman SOTK guna mendukung pelayanan publik yang optimal, profesional, dan transparan."
                </p>
            </div>

            {{-- INFO BOX (Optional Minimalist) --}}
            <div
                class="bg-primary-50 rounded-2xl p-6 md:p-8 mb-12 sm:mb-16 border border-primary-100 scroll-reveal max-w-4xl mx-auto text-center">
                <svg class="w-8 h-8 text-primary-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    Struktur Organisasi dan Tata Kerja Pemerintah Desa Warurejo disusun sesuai kebutuhan dan perkembangan desa dengan berlandaskan semangat gotong royong, profesionalisme, serta dedikasi untuk memberikan pelayanan terbaik dan meningkatkan kesejahteraan masyarakat.
                </p>
            </div>

            {{-- LEVEL 1: KEPALA DESA --}}
            <div class="mb-10 sm:mb-16 scroll-reveal">
                @if(isset($struktur['kepala']) && $struktur['kepala'])
                    <div class="max-w-md mx-auto relative group">
                        <div
                            class="bg-white border border-gray-100 rounded-2xl md:rounded-4xl shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden relative transition duration-300">
                            <div class="aspect-w-3 aspect-h-4 bg-gray-100 relative">
                                <img src="{{ $struktur['kepala']->avatar_url }}"
                                    data-fallback="{{ $struktur['kepala']->avatar_url }}"
                                    alt="{{ $struktur['kepala']->nama }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block bg-indigo-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider text-white shadow-md">
                                        KEPALA DESA
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 text-center bg-white border-t border-gray-50">
                                <h3 class="text-2xl font-extrabold text-gray-900 mb-1.5">{{ strtoupper($struktur['kepala']->nama) }}</h3>
                                <p class="text-gray-500 font-medium text-sm mb-1">{{ $struktur['kepala']->jabatan }}</p>
                                @if($struktur['kepala']->periode_jabatan)
                                    <p class="text-indigo-600 text-xs font-bold mb-2">Periode: {{ $struktur['kepala']->periode_jabatan }}</p>
                                @endif
                                @if($struktur['kepala']->whatsapp)
                                    <div class="mt-3 flex justify-center">
                                        <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $struktur['kepala']->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-500 hover:bg-emerald-600 text-white transition shadow-sm">
                                            <i class="fab fa-whatsapp mr-2 text-white"></i> {{ $struktur['kepala']->whatsapp }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Connecting Line -->
                    <div class="w-px h-10 sm:h-16 bg-gray-200 mx-auto transform translate-y-2"></div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center max-w-md mx-auto">
                        <p class="text-yellow-800 font-medium">Data Kepala Desa belum tersedia</p>
                    </div>
                @endif
            </div>

            {{-- LEVEL 2: SEKRETARIS DESA --}}
            <div class="mb-10 sm:mb-16 scroll-reveal">
                @if(isset($struktur['sekretaris']) && $struktur['sekretaris'])
                    <div class="max-w-sm mx-auto relative group">
                        <div
                            class="bg-white border border-gray-100 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden relative transition duration-300">
                            <div class="aspect-w-1 aspect-h-1 bg-gray-100 relative">
                                <img src="{{ $struktur['sekretaris']->avatar_url }}"
                                    data-fallback="{{ $struktur['sekretaris']->avatar_url }}"
                                    alt="{{ $struktur['sekretaris']->nama }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block bg-emerald-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-white shadow-md">
                                        SEKRETARIS DESA
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 text-center bg-white border-t border-gray-50">
                                <h4 class="text-xl font-bold text-gray-900 mb-1.5">{{ strtoupper($struktur['sekretaris']->nama) }}</h4>
                                <p class="text-gray-500 font-medium text-xs mb-1">{{ $struktur['sekretaris']->jabatan }}</p>
                                @if($struktur['sekretaris']->periode_jabatan)
                                    <p class="text-emerald-600 text-[11px] font-bold mb-2">Periode: {{ $struktur['sekretaris']->periode_jabatan }}</p>
                                @endif
                                @if($struktur['sekretaris']->whatsapp)
                                    <div class="mt-3 flex justify-center">
                                        <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $struktur['sekretaris']->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-500 hover:bg-emerald-600 text-white transition shadow-sm">
                                            <i class="fab fa-whatsapp mr-2 text-white"></i> {{ $struktur['sekretaris']->whatsapp }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Connecting Line -->
                    <div class="w-px h-10 sm:h-16 bg-gray-200 mx-auto transform translate-y-2"></div>
                @endif
            </div>

            {{-- HORIZONTAL CONNECTOR (KAUR & KASI) --}}
            @php
                $hasKaurOrKasi = (isset($struktur['kaur']) && count($struktur['kaur']) > 0) || (isset($struktur['kasi']) && count($struktur['kasi']) > 0);
            @endphp
            @if($hasKaurOrKasi)
                <div
                    class="hidden sm:block w-3/4 max-w-4xl mx-auto border-t-2 border-gray-200 h-8 transform -translate-y-2 relative">
                    <!-- Optional connecting vertical notches -->
                    <div class="absolute left-1/4 top-0 w-px h-8 bg-gray-200"></div>
                    <div class="absolute right-1/4 top-0 w-px h-8 bg-gray-200"></div>
                </div>
            @endif

            {{-- LEVEL 3: KEPALA URUSAN & KEPALA SEKSI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-12 mb-10 sm:mb-16">

                {{-- Kolom Kaur --}}
                @if(isset($struktur['kaur']) && count($struktur['kaur']) > 0)
                    <div class="scroll-reveal">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 text-center">Kepala Urusan</h3>
                        <div class="flex flex-col gap-6">
                            @foreach($struktur['kaur'] as $kaur)
                                <div
                                    class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-amber-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition duration-300 flex items-center gap-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100 relative">
                                        <img src="{{ $kaur->avatar_url }}"
                                            data-fallback="{{ $kaur->avatar_url }}"
                                            alt="{{ $kaur->nama }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span
                                            class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">{{ $kaur->level_label }}</span>
                                        <h4
                                            class="font-bold text-gray-900 mb-0.5 text-base sm:text-lg group-hover:text-amber-600 transition">
                                            {{ strtoupper($kaur->nama) }}</h4>
                                        <p class="text-gray-500 text-xs sm:text-sm mb-1">{{ $kaur->jabatan }}</p>
                                        @if($kaur->periode_jabatan)
                                            <p class="text-amber-700 text-[11px] font-semibold mb-1">Periode: {{ $kaur->periode_jabatan }}</p>
                                        @endif
                                        @if($kaur->whatsapp)
                                            <div class="mt-1.5">
                                                <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $kaur->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                                                    <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> {{ $kaur->whatsapp }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Kolom Kasi --}}
                @if(isset($struktur['kasi']) && count($struktur['kasi']) > 0)
                    <div class="scroll-reveal">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 text-center">Kepala Seksi</h3>
                        <div class="flex flex-col gap-6">
                            @foreach($struktur['kasi'] as $kasi)
                                <div
                                    class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-blue-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition duration-300 flex items-center gap-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100 relative">
                                        <img src="{{ $kasi->avatar_url }}"
                                            data-fallback="{{ $kasi->avatar_url }}"
                                            alt="{{ $kasi->nama }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span
                                            class="inline-block px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">{{ $kasi->level_label }}</span>
                                        <h4
                                            class="font-bold text-gray-900 mb-0.5 text-base sm:text-lg group-hover:text-blue-600 transition">
                                            {{ strtoupper($kasi->nama) }}</h4>
                                        <p class="text-gray-500 text-xs sm:text-sm mb-1">{{ $kasi->jabatan }}</p>
                                        @if($kasi->periode_jabatan)
                                            <p class="text-blue-700 text-[11px] font-semibold mb-1">Periode: {{ $kasi->periode_jabatan }}</p>
                                        @endif
                                        @if($kasi->whatsapp)
                                            <div class="mt-1.5">
                                                <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $kasi->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                                                    <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> {{ $kasi->whatsapp }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- LEVEL 4: STAFF --}}
            @php
                $hasStaff = (isset($struktur['staff_kaur']) && count($struktur['staff_kaur']) > 0) || (isset($struktur['staff_kasi']) && count($struktur['staff_kasi']) > 0);
            @endphp
            @if($hasStaff)
                <div class="mb-10 sm:mb-16 scroll-reveal mt-12 bg-gray-50/50 p-6 md:p-8 rounded-3xl border border-gray-100">
                    <h3
                        class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-8 text-center uppercase tracking-wider ">
                        Staff Desa</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8">
                        {{-- Staff Kaur --}}
                        @if(isset($struktur['staff_kaur']))
                            @foreach($struktur['staff_kaur'] as $staff)
                                <div
                                    class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-orange-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition duration-300 flex items-center gap-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100 relative">
                                        <img src="{{ $staff->avatar_url }}"
                                            data-fallback="{{ $staff->avatar_url }}"
                                            alt="{{ $staff->nama }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span
                                            class="inline-block px-2.5 py-0.5 bg-orange-100 text-orange-800 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">Staff Kaur</span>
                                        <h4
                                            class="font-bold text-gray-900 mb-0.5 text-base sm:text-lg group-hover:text-orange-600 transition">
                                            {{ strtoupper($staff->nama) }}</h4>
                                        <p class="text-gray-500 text-xs sm:text-sm mb-1">{{ $staff->jabatan }}</p>
                                        @if($staff->periode_jabatan)
                                            <p class="text-orange-700 text-[11px] font-semibold mb-1">Periode: {{ $staff->periode_jabatan }}</p>
                                        @endif
                                        @if($staff->whatsapp)
                                            <div class="mt-1.5 mb-1">
                                                <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $staff->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                                                    <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> {{ $staff->whatsapp }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($staff->atasan)
                                            <p class="text-orange-700 text-[11px] font-medium mt-1">dibawah {{ $staff->atasan->nama }} - {{ $staff->atasan->jabatan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Staff Kasi --}}
                        @if(isset($struktur['staff_kasi']))
                            @foreach($struktur['staff_kasi'] as $staff)
                                <div
                                    class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-teal-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition duration-300 flex items-center gap-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 rounded-xl overflow-hidden bg-gray-100 relative">
                                        <img src="{{ $staff->avatar_url }}"
                                            data-fallback="{{ $staff->avatar_url }}"
                                            alt="{{ $staff->nama }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <span
                                            class="inline-block px-2.5 py-0.5 bg-teal-100 text-teal-800 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">Staff Kasi</span>
                                        <h4
                                            class="font-bold text-gray-900 mb-0.5 text-base sm:text-lg group-hover:text-teal-600 transition">
                                            {{ strtoupper($staff->nama) }}</h4>
                                        <p class="text-gray-500 text-xs sm:text-sm mb-1">{{ $staff->jabatan }}</p>
                                        @if($staff->periode_jabatan)
                                            <p class="text-teal-700 text-[11px] font-semibold mb-1">Periode: {{ $staff->periode_jabatan }}</p>
                                        @endif
                                        @if($staff->whatsapp)
                                            <div class="mt-1.5 mb-1">
                                                <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $staff->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                                                    <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> {{ $staff->whatsapp }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($staff->atasan)
                                            <p class="text-teal-700 text-[11px] font-medium mt-1">dibawah {{ $staff->atasan->nama }} - {{ $staff->atasan->jabatan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            {{-- LEVEL 5: KEPALA DUSUN --}}
            @if(isset($struktur['kadus']) && count($struktur['kadus']) > 0)
                <div class="mb-10 sm:mb-16 scroll-reveal mt-12 bg-purple-50/30 p-6 md:p-8 rounded-3xl border border-purple-100/50">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-8 text-center uppercase tracking-wider">
                        Kepala Dusun
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($struktur['kadus'] as $kadus)
                            <div class="group bg-white rounded-2xl p-4 border border-gray-100 hover:border-purple-200 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition duration-300 flex flex-col items-center text-center">
                                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 relative mb-4 ring-4 ring-purple-50 group-hover:ring-purple-100 transition duration-300">
                                    <img src="{{ $kadus->avatar_url }}"
                                        data-fallback="{{ $kadus->avatar_url }}"
                                        alt="{{ $kadus->nama }}" class="w-full h-full object-cover">
                                </div>
                                <span class="inline-block px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                                    Kepala Dusun
                                </span>
                                <h4 class="font-bold text-gray-900 mb-1 text-base group-hover:text-purple-600 transition">
                                    {{ strtoupper($kadus->nama) }}
                                </h4>
                                <p class="text-gray-500 text-xs mb-1">{{ $kadus->jabatan }}</p>
                                @if($kadus->periode_jabatan)
                                    <p class="text-purple-700 text-[11px] font-semibold mb-1">Periode: {{ $kadus->periode_jabatan }}</p>
                                @endif
                                @if($kadus->whatsapp)
                                    <div class="mt-1.5">
                                        <a href="https://wa.me/{{ str_starts_with($num = preg_replace('/[^0-9]/', '', $kadus->whatsapp), '0') ? '62' . substr($num, 1) : $num }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition">
                                            <i class="fab fa-whatsapp mr-1 text-emerald-600"></i> {{ $kadus->whatsapp }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Catatan Perubahan --}}
            <div class="mt-12 text-center scroll-reveal">
                <p class="text-xs sm:text-sm text-gray-500 italic">
                    * Struktur organisasi ini dapat diperbarui sewaktu-waktu sesuai kebutuhan dan perkembangan pemerintahan desa
                </p>
            </div>

    </section>

    <style>
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
    </style>

    <script @nonce>
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
            document.querySelectorAll('.scroll-reveal').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

@endsection