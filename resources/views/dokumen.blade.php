<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumen - KMS Diskominfo Lampung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100" style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}');">

    {{-- HEADER --}}
    <header class="bg-white shadow-sm sticky top-0 z-20">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-4">
            <a href="/">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9">
            </a>
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Tentang Kami</a>
                <a href="{{ route('pengetahuan') }}" class="text-gray-600 text-sm hover:text-blue-700 transition">Pengetahuan</a>
                <a href="{{ route('dokumen') }}" class="text-blue-700 text-sm font-semibold transition">Dokumen</a>
            </nav>
            <a href="{{ route('login') }}" class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                Masuk
            </a>
        </div>
    </header>

    {{-- TITLE & SEARCH BAR SECTION --}}
    <section class="bg-white py-6 border-b border-gray-200">
        <div class="max-w-[1100px] mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-gray-800">Dokumen</h1>
            <div class="relative w-full max-w-sm">
                <input type="text" placeholder="Cari Dokumen" class="w-full py-2 pl-4 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 px-6 py-10">

        {{-- Sidebar Bidang dengan Ikon Berbeda --}}
        <aside class="lg:col-span-1 bg-white shadow-lg rounded-2xl p-6 h-fit">
            <h3 class="font-bold text-lg mb-6 text-gray-800">Bidang</h3>
            <ul class="flex flex-col gap-5">
                @php
                $bidangs = [
                    ['nama' => 'Sekretariat', 'icon' => 'fa-building-user'],
                    ['nama' => 'PLIP', 'icon' => 'fa-landmark'],
                    ['nama' => 'PKP', 'icon' => 'fa-people-group'],
                    ['nama' => 'TIK', 'icon' => 'fa-laptop-code'],
                    ['nama' => 'SanStik', 'icon' => 'fa-chart-simple']
                ];
                @endphp
                @foreach ($bidangs as $bidang)
                <li class="flex items-center gap-4 cursor-pointer group">
                    <span class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow transition-transform group-hover:scale-110">
                        {{-- Menggunakan Font Awesome untuk ikon --}}
                        <i class="fas {{ $bidang['icon'] }} text-white text-lg"></i>
                    </span>
                    <span class="font-medium text-base text-gray-700 group-hover:text-blue-700">{{ $bidang['nama'] }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Daftar Dokumen --}}
        <section class="lg:col-span-3">
            <div class="space-y-6">
                @php
                // Data contoh untuk 3 item dokumen
                $dokumen = [
                    ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                    ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                    ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                ];
                @endphp
                @foreach ($dokumen as $item)
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center gap-6">
                    {{-- Gambar Dokumen --}}
                    <div class="w-1/3">
                        <img src="{{ asset($item['img']) }}" alt="{{ $item['title'] }}" class="w-full h-auto object-cover rounded-lg border border-gray-200">
                    </div>
                    {{-- Detail Dokumen --}}
                    <div class="w-2/3">
                        <h3 class="text-lg font-bold text-gray-800">{{ $item['title'] }}</h3>
                        <ol class="list-decimal list-inside text-sm text-gray-600 mt-2 space-y-1">
                            <li>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta Tatakerja Perangkat Daerah Pemerintah Provinsi Lampung</li>
                            <li>a. Kepala Dinas;</li>
                            <li>b. Sekretariat;</li>
                            <li>c. Bidang Pengelolaan dan Layanan Informasi Publik...</li>
                        </ol>
                        <a href="#" class="inline-block text-blue-700 text-sm font-semibold mt-4 hover:underline">
                            Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <nav class="flex items-center justify-center gap-2 mt-10">
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-700 text-white font-bold">1</a>
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">2</a>
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">3</a>
                <span class="text-gray-500">...</span>
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">20</a>
                <a href="#" class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i class="fas fa-chevron-right"></i></a>
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
                    <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/facebook-icon.svg') }}" alt="Facebook" class="h-8"></a>
                    <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/instagram-icon.svg') }}" alt="Instagram" class="h-8"></a>
                    <a href="#" class="hover:opacity-80 transition"><img src="{{ asset('assets/img/youtube-icon.svg') }}" alt="YouTube" class="h-8"></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>