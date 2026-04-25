<footer class="bg-gray-800 text-white pt-12 pb-6 scroll-reveal-footer">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-5 items-start gap-8 mb-8">

            {{-- Kolom 1: Logo kiri pojok --}}
            <div class="flex justify-start items-center">
                <img src="{{ asset('images/Logo-Kabupaten.png') }}" alt="Logo Kabupaten Madiun"
                    class="h-28 w-28 md:h-32 md:w-32 lg:h-36 lg:w-36 object-contain">
            </div>

            {{-- Kolom 2: Tentang --}}
            <div>
                <h3 class="text-lg font-bold mb-3">Tentang</h3>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-primary-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                        <a href="https://maps.app.goo.gl/nhdvVhwMYMNMbQaH8" target="_blank" class="hover:underline">
                            Jl. Flamboyan, Templek, Warurejo, Kec. Balerejo, Kabupaten Madiun, Jawa Timur 63152
                        </a>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-primary-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <a href="https://wa.me/62085168687700" target="_blank"
                            class="hover:text-white transition">0851-6868-7700</a>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 text-primary-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <a href="mailto:desawarurejo@gmail.com"
                            class="hover:text-white transition">desawarurejo@gmail.com</a>
                    </li>
                </ul>

                {{-- Publikasi (di bawah Tentang) --}}
                <h3 class="text-lg font-bold mt-6 mb-3">Publikasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('publikasi.index', ['kategori' => 'APBDes']) }}"
                            class="hover:text-white transition">APBDes</a></li>
                    <li><a href="{{ route('publikasi.index', ['kategori' => 'RPJMDes']) }}"
                            class="hover:text-white transition">RPJMDes</a></li>
                    <li><a href="{{ route('publikasi.index', ['kategori' => 'RKPDes']) }}"
                            class="hover:text-white transition">RKPDes</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Profil --}}
            <div>
                <h3 class="text-lg font-bold mb-3">Profil</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('profil.visi-misi') }}" class="hover:text-white transition">Visi & Misi</a>
                    </li>
                    <li><a href="{{ route('profil.sejarah') }}" class="hover:text-white transition">Sejarah</a></li>
                    <li><a href="{{ route('profil.struktur-organisasi') }}" class="hover:text-white transition">Struktur
                            Organisasi</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Informasi --}}
            <div>
                <h3 class="text-lg font-bold mb-3">Informasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ route('berita.index') }}" class="hover:text-white transition">Berita</a></li>
                    <li><a href="{{ route('potensi.index') }}" class="hover:text-white transition">Potensi</a></li>
                    <li><a href="{{ route('galeri.index') }}" class="hover:text-white transition">Dokumentasi</a></li>
                    <li><a href="{{ route('peta-desa') }}" class="hover:text-white transition">Peta Desa</a></li>
                    <li><a href="https://wa.me/62085168687700" target="_blank" class="hover:text-white transition">Tanya
                            Jawab & Pengaduan</a></li>
                </ul>
            </div>

            {{-- Kolom 5: Media Sosial --}}
            <div class="flex flex-col items-start md:items-end">
                <h3 class="text-lg font-bold mb-3">Media Sosial</h3>
                <div class="flex space-x-3">
                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/warurejo.balerejo" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition transform hover:scale-110 shadow-md"
                        aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073C0 18.063 4.388 23.027 10.125 23.927v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/desawarurejo?igsh=Mjl6NHY0MXhvZXMw" target="_blank"
                        rel="noopener noreferrer" class="w-10 h-10 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 
                            text-white rounded-full flex items-center justify-center 
                            hover:opacity-90 transition transform hover:scale-110 shadow-md">

                        <!-- LOGO BARU -->
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-.25a1.25 1.25 0 11-.001 2.501A1.25 1.25 0 0116.5 8.75z" />
                        </svg>
                    </a>

                    {{-- YouTube --}}
                    <a href="https://youtube.com/@desawarurejo6792?si=H6HWG4FCIaxvzuXB" target="_blank"
                        rel="noopener noreferrer"
                        class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition transform hover:scale-110 shadow-md"
                        aria-label="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>

                    {{-- TikTok --}}
                    <a href="https://www.tiktok.com/@desa.warurejo" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gray-900 text-white rounded-full flex items-center justify-center 
                            hover:bg-black transition transform hover:scale-110 shadow-md">

                        <!-- LOGO BARU -->
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 8.5a5.5 5.5 0 01-4-1.7v8.2a6 6 0 11-6-6c.3 0 .7 0 1 .1V12a3 3 0 102 2.8V2h3a5.5 5.5 0 004 1.7v4.8z" />
                        </svg>
                    </a>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/62085168687700" target="_blank" rel="noopener noreferrer"
                        class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition transform hover:scale-110 shadow-md"
                        aria-label="WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
            <p class="mb-2">© {{ now()->year }} Desa Warurejo. Developed by Just V.</p>
            </p>
        </div>
    </div>
</footer>