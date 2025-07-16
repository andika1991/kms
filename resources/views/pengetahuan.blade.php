<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengetahuan - KMS Diskominfo Lampung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100" style="background-image: url('{{ asset('img/body-bg-pattern.png') }}');">
    {{-- HEADER --}}
    <header class="bg-white shadow-md sticky top-0 z-20">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-3">
            <a href="/">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9">
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} text-sm transition">
                    Beranda
                </a>
                <a href="{{ route('about') }}"
                    class="{{ request()->routeIs('about') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} text-sm transition">
                    Tentang Kami
                </a>
                <a href="{{ route('pengetahuan') }}"
                    class="{{ request()->routeIs('pengetahuan') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} text-sm transition">
                    Pengetahuan
                </a>
                <a href="{{ route('dokumen') }}"
                    class="{{ request()->routeIs('dokumen') ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }} text-sm transition">
                    Dokumen
                </a>
            </nav>


            {{-- Logika untuk Tombol Login & Dashboard --}}
            <div class="flex items-center">
                @auth
                {{-- Jika pengguna sudah login --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
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

    {{-- TITLE & SEARCH BAR SECTION --}}
    <section class="bg-white py-6 border-b border-gray-200">
        <div class="max-w-[1100px] mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-gray-800">Pengetahuan</h1>
            <div class="relative w-full max-w-sm">
                <input type="text" placeholder="Cari Pengetahuan"
                    class="w-full py-2 pl-4 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 px-6 py-10">

        {{-- Sidebar Bidang --}}
        <aside class="lg:col-span-1 bg-white shadow-lg rounded-2xl p-6 h-fit">
            <h3 class="font-bold text-lg mb-6 text-gray-800">Bidang</h3>
            <ul class="flex flex-col gap-5">
                @php
                $bidangs = ['Sekretariat', 'PLIP', 'PKP', 'TIK', 'SanStik', 'UPTD'];
                @endphp
                @foreach ($bidangs as $bidang)
                <li class="flex items-center gap-4 cursor-pointer group">
                    <span
                        class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow transition-transform group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 20 20" class="w-5 h-5">
                            <path
                                d="M6 7V6a4 4 0 1 1 8 0v1h2.25A1.75 1.75 0 0 1 18 8.75v7.5A1.75 1.75 0 0 1 16.25 18H3.75A1.75 1.75 0 0 1 2 16.25v-7.5A1.75 1.75 0 0 1 3.75 7H6Zm2-1a2 2 0 1 1 4 0v1H8V6Z" />
                        </svg>
                    </span>
                    <span class="font-medium text-base text-gray-700 group-hover:text-blue-700">{{ $bidang }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Grid Pengetahuan --}}
        <section class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                $pengetahuan = [
                ['img' => 'assets/img/pengetahuan_1.png', 'title' => 'Matriks Renstra Dinas Kominfotik Provinsi Lampung
                Tahun 2015-2019'],
                ['img' => 'assets/img/pengetahuan_2.png', 'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun 2017
                Download'],
                ['img' => 'assets/img/pengetahuan_3.png', 'title' => 'Matriks Renstra Dinas Kominfotik Provinsi Lampung
                Tahun 2015-2019'],
                ['img' => 'assets/img/pengetahuan_4.png', 'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun 2017
                Download']
                ];
                @endphp
                @foreach ($pengetahuan as $item)
                <div
                    class="bg-white border border-gray-200 rounded-xl overflow-hidden flex flex-col group shadow hover:shadow-lg transition-shadow">
                    <img src="{{ asset($item['img']) }}" class="w-full h-44 object-cover" alt="Foto Pengetahuan">
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-semibold text-base leading-tight text-gray-800 flex-grow">{{ $item['title'] }}
                        </h3>
                        <a href="#"
                            class="text-blue-700 text-sm font-semibold mt-4 self-start group-hover:underline">Download</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <nav class="flex items-center justify-center gap-2 mt-10">
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i
                        class="fas fa-chevron-left"></i></a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-700 text-white font-bold">1</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">2</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">3</a>
                <span class="text-gray-500">...</span>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">20</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i
                        class="fas fa-chevron-right"></i></a>
            </nav>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#0B3C6A] text-white pt-10 pb-8 mt-8">
        <div class="max-w-[1200px] mx-auto grid md:grid-cols-3 gap-8 px-6 text-sm">
            <div class="space-y-3">
                <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo" class="h-10">
                <p class="font-semibold">Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</p>
                <div class="text-white/70 text-xs leading-relaxed">
                    <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                    <p>Telepon : (0721) 481107</p>
                    <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                    <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                </div>
            </div>
            <div class="md:mx-auto">
                <h4 class="font-bold mb-4">Menu</h4>
                <ul class="space-y-2 text-white/80">
                    <li><a href="/" class="hover:underline hover:text-white">Home</a></li>
                    <li><a href="#" class="hover:underline hover:text-white">Tentang Kami</a></li>
                    <li><a href="#" class="hover:underline hover:text-white">Kegiatan</a></li>
                    <li><a href="#" class="hover:underline hover:text-white">Dokumen</a></li>
                    <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                </ul>
            </div>
            <div class="md:mx-auto">
                <h4 class="font-bold mb-4">Ikuti Kami</h4>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:opacity-80 transition"><img
                            src="{{ asset('assets/img/facebook-icon.svg') }}" alt="Facebook" class="h-8"></a>
                    <a href="#" class="hover:opacity-80 transition"><img
                            src="{{ asset('assets/img/instagram-icon.svg') }}" alt="Instagram" class="h-8"></a>
                    <a href="#" class="hover:opacity-80 transition"><img
                            src="{{ asset('assets/img/youtube-icon.svg') }}" alt="YouTube" class="h-8"></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>