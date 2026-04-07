{{--
    PUBLIC NAVBAR COMPONENT
    
    Navigation bar dengan interaktif effects dan responsive menu
    
    FEATURES:
    - Fixed position dengan transparent to solid transition on scroll
    - Alpine.js reactive scroll detection
    - Logo dengan hover scale effect
    - Dropdown menu dengan smooth animation
    - Mobile hamburger menu (slide from right)
    - Active link highlighting (route-based)
    - Smooth color transitions (transparent → white bg)
    
    ALPINE.JS STATE:
    - mobileMenuOpen: Boolean untuk toggle mobile menu
    - scrolled: Boolean untuk detect scroll > 50px
    - x-init: Event listener untuk window scroll
    
    SCROLL EFFECTS:
    - scrolled=false: Transparent bg, white text (untuk hero overlay)
    - scrolled=true: White bg, dark text, shadow-md
    - Smooth transition 500ms
    
    DESKTOP MENU (md+):
    - Horizontal links dengan underline animation (.nav-link)
    - Dropdown menu dengan hover expand
    - Smooth dropdown animation (fadeIn + slideDown)
    - Active route bold + underline
    
    MOBILE MENU (<md):
    - Hamburger button (3 lines)
    - Slide-in menu from right
    - Full-height overlay
    - Close on link click
    - Backdrop blur effect
    
    DROPDOWN MENUS:
    - Profil dropdown: Visi/Misi, Sejarah, Struktur Organisasi
    - Hover to expand (desktop)
    - Click to expand (mobile)
    - Caret icon rotation animation
    
    ACTIVE HIGHLIGHTING:
    - request()->routeIs() detection
    - Bold font + .nav-link-active class
    - Underline animation
    
    CSS CLASSES:
    - .nav-link: Underline animation dari app.css
    - .dropdown-menu: Fadeout to visible transition
    - .navbar-slide-down: Entry animation
    
    RESPONSIVE:
    - Mobile: Hamburger menu, stacked links
    - Desktop: Horizontal menu, dropdowns
    
    USAGE:
    @include('public.partials.navbar') di layouts/app.blade.php
--}}
<nav 
    x-data="{ mobileMenuOpen: false, scrolled: {{ request()->routeIs('home') ? 'false' : 'true' }} }"
    x-init="if({{ request()->routeIs('home') ? 'true' : 'false' }}) { scrolled = window.scrollY > 50; window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 }) }"
    :class="scrolled ? 'bg-white shadow-md' : 'bg-transparent backdrop-blur-md'"
    class="fixed top-0 left-0 right-0 w-full z-[100] transition-all duration-500 navbar-slide-down"
>
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo dan Judul dengan Animasi -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <div class="relative">
                    <img src="{{ asset('images/Logo-Kabupaten.png') }}" 
                         alt="Logo Desa" 
                         class="h-12 w-12 transition-all duration-300 ease-out group-hover:scale-105 group-active:rotate-6">
                </div>
                <div>
                    <h1 :class="scrolled ? 'text-gray-800' : 'text-white'" 
                        class="text-xl font-bold transition-colors duration-300">
                        Desa Warurejo
                    </h1>
                    <p :class="scrolled ? 'text-gray-600' : 'text-green-100'" 
                       class="text-xs transition-colors duration-300">
                        Kabupaten Madiun
                    </p>
                </div>
            </a>

            <!-- Menu Desktop dengan Underline Animation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}" 
                   :class="scrolled ? 'text-gray-700' : 'text-white'" 
                   class="nav-link font-medium {{ request()->routeIs('home') ? 'font-bold nav-link-active' : '' }}">
                    Beranda
                </a>

                <!-- Dropdown Profil -->
                <div class="relative group">
                    <button :class="scrolled ? 'text-gray-700' : 'text-white'" 
                            class="nav-link font-medium flex items-center {{ request()->routeIs('profil.*') ? 'font-bold nav-link-active' : '' }}">
                        Profil
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200 group-hover:rotate-180" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profil.visi-misi') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Visi & Misi
                        </a>
                        <a href="{{ route('profil.sejarah') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Sejarah
                        </a>
                        <a href="{{ route('profil.struktur-organisasi') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Struktur Organisasi
                        </a>
                    </div>
                </div>

                <!-- Dropdown Informasi -->
                <div class="relative group">
                    <button :class="scrolled ? 'text-gray-700' : 'text-white'" 
                            class="nav-link font-medium flex items-center">
                        Informasi
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200 group-hover:rotate-180" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu w-56">
                        <a href="{{ route('berita.index') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Berita
                        </a>
                        <a href="{{ route('potensi.index') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Potensi
                        </a>
                        <a href="{{ route('galeri.index') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Dokumentasi
                        </a>
                        <a href="{{ route('peta-desa') }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Peta Desa
                        </a>
                        <a href="https://wa.me/62085168687700" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            Tanya Jawab & Pengaduan
                        </a>
                    </div>
                </div>

                <!-- Dropdown Publikasi -->
                <div class="relative group">
                    <button :class="scrolled ? 'text-gray-700' : 'text-white'" 
                            class="nav-link font-medium flex items-center {{ request()->routeIs('publikasi.*') ? 'font-bold nav-link-active' : '' }}">
                        Publikasi
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200 group-hover:rotate-180" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu w-64">
                        <a href="{{ route('publikasi.index', ['kategori' => 'APBDes']) }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            APBDes
                        </a>
                        <a href="{{ route('publikasi.index', ['kategori' => 'RPJMDes']) }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            RPJMDes
                        </a>
                        <a href="{{ route('publikasi.index', ['kategori' => 'RKPDes']) }}" class="dropdown-item">
                            <span class="dropdown-item-icon"></span>
                            RKPDes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tombol Mobile dengan Animasi -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    :class="scrolled ? 'text-gray-700' : 'text-white'" 
                    class="md:hidden p-2 rounded-lg hover:bg-gray-100/10 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" 
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M4 6h16M4 12h16M4 18h16"
                          class="transition-all duration-200"/>
                    <path x-show="mobileMenuOpen" 
                          stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M6 18L18 6M6 6l12 12"
                          class="transition-all duration-200"/>
                </svg>
            </button>
        </div>

        <!-- Menu Mobile dengan Smooth Slide-in dan Backdrop Blur -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenuOpen = false"
             class="md:hidden mt-2 mb-4">
            
            <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="max-h-[70vh] overflow-y-auto">
                    <!-- Beranda -->
                    <a href="{{ route('home') }}" 
                       class="mobile-menu-item {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-700 font-semibold' : '' }}"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Beranda
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>
                    
                    <!-- Profil Section -->
                    <div class="px-4 py-2 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Profil</p>
                    </div>
                    <a href="{{ route('profil.visi-misi') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Visi & Misi
                    </a>
                    <a href="{{ route('profil.sejarah') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Sejarah
                    </a>
                    <a href="{{ route('profil.struktur-organisasi') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Struktur Organisasi
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Informasi Section -->
                    <div class="px-4 py-2 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Informasi</p>
                    </div>
                    <a href="{{ route('berita.index') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Berita
                    </a>
                    <a href="{{ route('potensi.index') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Potensi
                    </a>
                    <a href="{{ route('galeri.index') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Dokumentasi
                    </a>
                    <a href="{{ route('peta-desa') }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Peta Desa
                    </a>
                    <a href="https://wa.me/62085168687700" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        Tanya Jawab & Pengaduan
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Publikasi Section -->
                    <div class="px-4 py-2 bg-gray-50">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Publikasi</p>
                    </div>
                    <a href="{{ route('publikasi.index', ['kategori' => 'APBDes']) }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        APBDes
                    </a>
                    <a href="{{ route('publikasi.index', ['kategori' => 'RPJMDes']) }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        RPJMDes
                    </a>
                    <a href="{{ route('publikasi.index', ['kategori' => 'RKPDes']) }}" 
                       class="mobile-menu-item pl-10"
                       @click="mobileMenuOpen = false">
                        <span class="text-lg"></span>
                        RKPDes
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
