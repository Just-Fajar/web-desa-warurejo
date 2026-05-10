@extends('public.layouts.app')

@section('title', 'Peta Desa - Desa Warurejo')

@section('content')

    {{-- Main Map Section --}}
    <section class="pt-32 pb-16 lg:pt-40 lg:pb-24 bg-gray-50 relative overflow-hidden">
        {{-- Decorative Background Elements --}}
        <div
            class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-amber-100 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-amber-50 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center max-w-7xl mx-auto">

                {{-- Left Content: Text & Contact --}}
                <div class="lg:col-span-5 scroll-reveal-left">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-px bg-amber-500"></div>
                        <span class="text-amber-600 text-sm font-semibold tracking-widest uppercase">Hubungi Kami</span>
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight leading-tight">
                        Datang & Kunjungi <br> <span class="text-amber-600">Kantor Desa</span>
                    </h1>

                    <p class="text-lg text-gray-600 mb-10 leading-relaxed font-medium">
                        Kami siap melayani kebutuhan administrasi warga pada jam kerja. Jangan ragu untuk menghubungi kami
                        melalui kontak di bawah ini.
                    </p>

                    <div class="space-y-8">
                        {{-- Contact Items --}}
                        <a href="https://maps.app.goo.gl/G3PAkybFHZyfbzvC9?g_st=aw" target="_blank"
                            class="flex items-start gap-4 group hover:cursor-pointer transition-transform hover:-translate-y-1">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 text-amber-600 border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shrink-0 shadow-sm group-hover:shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-gray-900 text-lg mb-1 group-hover:text-amber-600 transition-colors">
                                    Alamat Kantor</h3>
                                <p class="text-gray-500 leading-relaxed group-hover:text-gray-700 transition-colors">Jl.
                                    Flamboyan, Templek, Warurejo, Kec. Balerejo, Kabupaten Madiun, Jawa Timur 63152</p>
                            </div>
                        </a>

                        <div class="flex items-start gap-4 group">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 text-amber-600 border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shrink-0 shadow-sm group-hover:shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <h3
                                    class="font-bold text-gray-900 text-lg mb-1 group-hover:text-amber-600 transition-colors">
                                    Kontak Resmi</h3>
                                <a href="mailto:desawarurejo@gmail.com"
                                    class="text-gray-500 hover:text-amber-600 transition-colors inline-block w-fit">desawarurejo@gmail.com</a>
                                <a href="https://wa.me/62085168687700?text=Halo%20Admin%20Desa%20Warurejo,%20saya%20ingin%20bertanya"
                                    class="text-gray-500 hover:text-amber-600 transition-colors inline-block mt-0.5 w-fit">0851-6868-7700</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 group">
                            <div
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-50 text-amber-600 border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg mb-1">Jam Pelayanan</h3>
                                <p class="text-gray-500">Senin - Kamis: 08.00 - 15.00</p>
                                <p class="text-gray-500 mt-0.5">Jumat: 08.00 - 12.00</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Content: Map --}}
                <div class="lg:col-span-7 h-full w-full scroll-reveal-right mt-12 lg:mt-0">
                    <div
                        class="bg-white p-3 rounded-[2rem] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] border border-gray-100 relative">
                        <div class="relative w-full h-[400px] md:h-[500px] lg:h-[600px] rounded-[1.5rem] overflow-hidden">
                            <iframe
                                src="https://maps.google.com/maps?q=Kantor+Kepala+Desa+Warurejo+Balerejo+Madiun&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                class="transition-opacity duration-500" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                            {{-- Floating Mini Info Box (Google Maps style) --}}
                            <div
                                class="absolute bottom-6 left-6 right-6 lg:left-auto lg:right-6 lg:w-72 bg-white/95 backdrop-blur-md p-4 rounded-xl shadow-lg border border-white">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-sm">Kantor Desa Warurejo</h4>
                                </div>
                                <a href="https://maps.app.goo.gl/G3PAkybFHZyfbzvC9?g_st=aw" target="_blank"
                                    class="block text-amber-600 text-xs font-semibold hover:text-amber-700 transition">
                                    Buka peta lebih besar &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection