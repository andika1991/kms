<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Latar belakang berpola ditambahkan ke body --}}

<body class="bg-gray-100 font-figtree" style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}');">

    {{-- 1. WRAPPER UNTUK HEADER & HERO --}}
    {{-- Latar belakang gedung sekarang diterapkan di sini --}}
    <div class="relative"
        style="background: url('{{ asset('assets/img/Background-line-landing-page.png') }}') center/cover no-repeat;">

        {{-- Overlay gelap untuk membuat teks lebih terbaca --}}
        <div class="absolute inset-0 bg-black/40"></div>

        {{-- HEADER (dibuat melayang di atas) --}}
        <header class="absolute top-0 left-0 w-full z-20">
            <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-5">
                <div class="flex items-center gap-2">
                    {{-- Gunakan logo versi putih agar terlihat --}}
                    <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-8">
                </div>
                <nav class="hidden md:flex gap-8">
                    {{-- Teks diubah menjadi putih --}}
                    <a href="#" class="text-white text-sm font-semibold border-b-2 border-white pb-1">Beranda</a>
                    <a href="#" class="text-white/80 text-sm hover:text-white transition">Tentang Kami</a>
                    <a href="#" class="text-white/80 text-sm hover:text-white transition">Kegiatan</a>
                    <a href="#" class="text-white/80 text-sm hover:text-white transition">Dokumen</a>
                </nav>
                <a href="#"
                    class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">Masuk</a>
            </div>
        </header>

        {{-- HERO CONTENT (sekarang berada di dalam wrapper yang sama dengan header) --}}
        <section class="relative min-h-[420px] md:min-h-[460px] flex items-center">
            <div class="max-w-[1200px] mx-auto w-full flex justify-between items-center relative z-10 px-6">
                <div>
                    <h1 class="text-white text-4xl md:text-5xl font-bold mb-2 drop-shadow-md leading-tight">
                        Knowledge Management<br>System
                    </h1>
                    <p class="text-white/90 text-base md:text-lg font-semibold drop-shadow">
                        Dinas Komunikasi Informatika dan Statistik Provinsi Lampung
                    </p>
                </div>
                <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Diskominfotik Lampung"
                    class="h-32 md:h-40 drop-shadow-xl hidden md:block">
            </div>
        </section>
    </div>


    {{-- MAIN CONTENT --}}
    <main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 px-6 mt-[-80px] pb-10 z-10 relative">

        {{-- Sidebar Bidang --}}
        <aside class="lg:col-span-1 bg-white shadow-xl rounded-2xl p-6 h-fit">
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
        {{-- PERBAIKAN: Struktur disederhanakan menjadi satu elemen <section> --}}
        <section class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-6">
            <h2 class="font-bold text-xl mb-6 text-gray-800">Pengetahuan</h2>
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
                <div class="border border-gray-200 rounded-xl overflow-hidden flex flex-col group">
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
        </section>

    </main>

    {{-- FOOTER (Struktur Anda sudah bagus) --}}
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
                    <li><a href="#" class="hover:underline hover:text-white">Home</a></li>
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