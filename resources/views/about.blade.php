<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - KMS Diskominfotik Lampung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
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

    {{-- HERO SECTION --}}
    <section class="relative flex items-center justify-center min-h-[320px] md:min-h-[420px] bg-cover bg-center"
        style="background-image: url('{{ asset('assets/img/about_figure.png') }}');">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white drop-shadow-lg mb-6">Tentang Kami</h1>
            <div class="inline-block rounded-xl shadow-lg p-6 text-gray-200 text-sm md:text-base max-w-3xl mx-auto">
                Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung merupakan penyelenggara urusan
                pemerintahan dan mempunyai tugas di bidang Komunikasi dan Informatika, Statistik dan Persandian. Dinas
                Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung dipimpin oleh seorang Kepala Dinas
                yang berkedudukan di bawah dan bertanggung jawab kepada Gubernur melalui Sekretaris Daerah.
            </div>
        </div>
    </section>

    {{-- VISI & MISI --}}
    <section class="max-w-5xl mx-auto mt-12 flex flex-col md:flex-row gap-8 px-4">
        <div class="md:w-2/5 flex items-center justify-center">
            <img src="{{ asset('assets/img/visi_misi.png') }}" alt="Kantor Dinas Kominfotik"
                class="rounded-2xl shadow-lg w-[260px] md:w-[300px] h-auto object-contain" />
        </div>
        <div class="md:w-3/5 flex flex-col justify-center">
            <h2 class="font-bold text-lg mb-1">Visi</h2>
            <p class="mb-3 text-sm md:text-base">Terwujudnya Pusat Informasi Dan Komunikasi Untuk Menunjang Pembangunan
                Daerah Menuju Lampung Unggul Dan Berdaya Saing</p>
            <h2 class="font-bold text-lg mb-1 mt-4">Misi</h2>
            <ol class="list-decimal pl-5 space-y-1 text-sm md:text-base">
                <li>Meningkatkan Daya Dukung Infrastruktur Teknologi Komunikasi dan Informasi untuk Memperluas Akses
                    Masyarakat terhadap Informasi Pembangunan Daerah.</li>
                <li>Meningkatkan Kompetensi Sumber Daya Manusia bidang Komunikasi dan Informatika secara Profesional.
                </li>
                <li>Meningkatkan Kualitas Layanan Komunikasi dan Informasi kepada Masyarakat dalam rangka Mewujudkan
                    Masyarakat Berbudaya Informasi</li>
            </ol>
        </div>
    </section>

    {{-- TUGAS & FUNGSI --}}
    <section class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 my-12 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-2 text-[#254759]">Tugas</h2>
            <p class="text-sm leading-relaxed">
                Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta
                Takerja Perangkat Daerah, Dinas Komunikasi, Informatika dan Statistik mempunyai tugas membantu Gubernur
                melaksanakan Urusan Pemerintahan di bidang Komunikasi, Informatika, bidang Persandian dan bidang
                Statistik berdasarkan asas otonomi yang menjadi kewenangan, tugas dekonsentrasi dan tugas pembantuan
                serta tugas lain sesuai dengan kebijakan yang ditetapkan oleh Gubernur berdasarkan peraturan
                perundang-undangan.
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-2 text-[#254759]">Fungsi</h2>
            <p class="text-sm leading-relaxed">
                Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta
                Takerja Perangkat Daerah, Dinas Komunikasi, Informatika dan Statistik mempunyai fungsi:
                <br>a. perumusan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>b. pelaksanaan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>c. pelaksanaan evaluasi dan pelaporan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>d. pelaksanaan administrasi di bidang Komunikasi, Informatika, Persandian dan Statistik; dan
                <br>e. pelaksanaan fungsi lain yang diberikan oleh Gubernur.
            </p>
        </div>
    </section>

    {{-- BIDANG --}}
    <section class="w-full py-12 bg-[#2b6cb0]">
        <div class="w-full text-center px-0">
            <img src="{{ asset('assets/img/group_bidang.png') }}" alt="Bidang"
                class="w-full h-auto object-cover mx-auto">
        </div>
    </section>

    {{-- STRUKTUR ORGANISASI --}}
    <section class="max-w-3xl mx-auto mt-12 mb-20 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
            <h2 class="font-bold text-lg mb-2 text-[#254759]">Struktur Organisasi</h2>
            <ol class="list-decimal pl-6 text-left mb-4 text-sm">
                <li>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi
                    Serta Takerja Perangkat Daerah, Susunan Organisasi Dinas Komunikasi, Informatika dan Statistik,
                    terdiri dari:
                    <ul class="list-disc ml-4 mt-1">
                        <li>a. Kepala Dinas;</li>
                        <li>b. Sekretariat;</li>
                        <li>c. Bidang Pengelolaan dan Layanan Informasi Publik.....</li>
                    </ul>
                </li>
            </ol>
            <a href="#"
                class="inline-block bg-blue-700 hover:bg-blue-800 text-white px-7 py-2 rounded-full mt-2 font-semibold shadow transition">Lihat</a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-[#0B3C6A] text-white pt-12 pb-10 mt-8">
        <div class="max-w-[1200px] mx-auto px-6 flex flex-col items-center">

            {{-- Logo Tengah Atas --}}
            <div class="mb-8">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Logo Diskominfo Footer"
                    class="h-16">
            </div>

            {{-- Konten Tiga Kolom --}}
            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">

                {{-- Informasi Kontak --}}
                <div class="space-y-2 text-sm text-white/80 leading-relaxed">
                    <p class="font-bold text-white text-base">Dinas Komunikasi, Informatika dan Statistik Provinsi
                        Lampung</p>
                    <p>Alamat : Jl. WR Monginsidi No.69 Bandar Lampung</p>
                    <p>Telepon : (0721) 481107</p>
                    <p>Facebook : www.facebook.com/diskominfo.lpg</p>
                    <p>Instagram : www.instagram.com/diskominfotiklampung</p>
                </div>

                {{-- Menu Navigasi --}}
                <div class="md:mx-auto">
                    <h4 class="font-bold text-white text-base mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-white/80">
                        <li><a href="/" class="hover:underline hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kegiatan</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                {{-- Media Sosial --}}
                <div class="md:ml-auto md:text-right">
                    <h4 class="font-bold text-white text-base mb-4">Ikuti Kami</h4>
                    <div class="flex items-center justify-center md:justify-end gap-3">
                        {{-- Menggunakan Font Awesome untuk ikon yang lebih user-friendly --}}
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-facebook-f text-white"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-instagram text-white"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>