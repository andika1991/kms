<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', config('app.name', 'Knowledge Management System'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    {{-- Font Awesome untuk ikon di sidebar --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js untuk interaktivitas -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-figtree bg-gray-100" style="background-image: url('{{ asset('img/body-bg-pattern.png') }}');">
    {{-- HEADER --}}
    <header class="bg-white shadow-md sticky top-0 z-20">
        <div
            class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 flex items-center justify-between py-3 md:py-4">

            <a href="/">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9" />
            </a>

            {{-- Mobile Hamburger Menu --}}
            <div x-data="{ open: false }" class="md:hidden">
                <button @click="open = !open" aria-label="Toggle navigation"
                    class="text-gray-600 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 rounded-md">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <nav x-show="open" @click.away="open = false"
                    class="absolute top-full left-0 w-full bg-white shadow-md border-t md:hidden z-30"
                    x-transition>
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} block px-6 py-3 border-b border-gray-100 text-sm transition">
                        Beranda
                    </a>
                    <a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} block px-6 py-3 border-b border-gray-100 text-sm transition">
                        Tentang Kami
                    </a>
                    <a href="{{ route('pengetahuan') }}"
                        class="{{ request()->routeIs('pengetahuan') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} block px-6 py-3 border-b border-gray-100 text-sm transition">
                        Pengetahuan
                    </a>
                    <a href="{{ route('dokumen') }}"
                        class="{{ request()->routeIs('dokumen') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} block px-6 py-3 border-b border-gray-100 text-sm transition">
                        Dokumen
                    </a>
                    <a href="{{ route('kegiatan') }}"
                        class="{{ request()->routeIs('kegiatan') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} block px-6 py-3 border-b border-gray-100 text-sm transition">
                        Kegiatan
                    </a>
                </nav>
            </div>

            {{-- Desktop Navigation --}}
            <nav
                class="hidden md:flex items-center gap-8 whitespace-nowrap text-sm font-medium select-none">
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} transition">
                    Beranda
                </a>
                <a href="{{ route('about') }}"
                    class="{{ request()->routeIs('about') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} transition">
                    Tentang Kami
                </a>
                <a href="{{ route('pengetahuan') }}"
                    class="{{ request()->routeIs('pengetahuan') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} transition">
                    Pengetahuan
                </a>
                <a href="{{ route('dokumen') }}"
                    class="{{ request()->routeIs('dokumen') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} transition">
                    Dokumen
                </a>
                <a href="{{ route('kegiatan') }}"
                    class="{{ request()->routeIs('kegiatan') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} transition">
                    Kegiatan
                </a>
            </nav>

            {{-- Logika untuk Tombol Login & Dashboard --}}
            <div class="flex items-center ml-4">
                @auth
                {{-- Jika pengguna sudah login --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    {{-- Menu Dropdown disesuaikan dengan role_group --}}
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                        style="display: none;">
                        <div class="py-1">
                            @php
                            $dashboardRoute = '#';

                            switch(Auth::user()->role->role_group) {
                            case 'admin':
                            $dashboardRoute = route('admin.dashboard');
                            break;
                            case 'kepalabagian':
                            $dashboardRoute = route('kepalabagian.dashboard');
                            break;
                            case 'pegawai':
                            $dashboardRoute = route('pegawai.dashboard');
                            break;
                            case 'magang':
                            $dashboardRoute = route('magang.dashboard');
                            break;
                            case 'kasubbidang':
                            $dashboardRoute = route('kasubbidang.dashboard');
                            break;
                            case 'sekretaris':
                            $dashboardRoute = route('sekretaris.dashboard');
                            break;
                            case 'Kadis':
                            $dashboardRoute = route('kadis.dashboard');
                            break;
                            default:
                            $dashboardRoute = route('home');
                            break;
                            }
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Dashboard
                            </a>

                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                {{-- Jika pengguna belum login --}}
                <a href="{{ route('login') }}"
                    class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                    Masuk
                </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-[#0B3C6A] text-white pt-12 pb-10 mt-8">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 md:px-8 flex flex-col items-center">

            {{-- Logo Tengah Atas --}}
            <div class="mb-8">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Logo Diskominfo Footer"
                    class="h-16" />
            </div>

            {{-- Konten Tiga Kolom --}}
            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">

                {{-- Kolom 1: Informasi Kontak --}}
                <div class="space-y-2 text-sm text-white/80 leading-relaxed">
                    <p class="font-bold text-white text-base">Dinas Komunikasi, Informatika dan Statistik Provinsi
                        Lampung</p>
                    <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                    <p>Telepon : (0721) 481107</p>
                    <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                    <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                </div>

                {{-- Kolom 2: Menu Navigasi --}}
                <div class="md:mx-auto">
                    <h4 class="font-bold text-white text-base mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="{{ route('home') }}" class="hover:underline hover:text-white">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:underline hover:text-white">Tentang Kami</a>
                        </li>
                        <li><a href="{{ route('pengetahuan') }}"
                                class="hover:underline hover:text-white">Pengetahuan</a></li>
                        <li><a href="{{ route('dokumen') }}" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="{{ route('kegiatan') }}" class="hover:underline hover:text-white">Kegiatan</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Media Sosial --}}
                <div class="md:ml-auto md:text-right">
                    <h4 class="font-bold text-white text-base mb-4">Ikuti Kami</h4>
                    <div class="flex items-center justify-center md:justify-end gap-3">
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/share/175mUXN9ow/?mibextid=wwXIfr" target="_blank"
                            rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/diskominfotik.lampung?igsh=MTRqb3VlOWxzbG9yeQ=="
                            target="_blank" rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                        {{-- Youtube --}}
                        <a href="https://youtube.com/@diskominfotiklampung?si=9-Py4fdTCy2hOeBX" target="_blank"
                            rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>