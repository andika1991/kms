<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    {{-- Font Awesome untuk ikon di sidebar --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-100" style="background-image: url('{{ asset('assets/img/body-bg-pattern.png') }}');">

    {{-- HEADER --}}
    <header class="bg-white shadow-md sticky top-0 z-20">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-6 py-3">
            <a href="/">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-9">
            </a>
            {{-- Navigasi untuk Desktop --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-blue-700 text-sm font-semibold transition">Beranda</a>
                <a href="#" class="text-gray-600 text-sm hover:text-blue-700 transition">Tentang Kami</a>
                <a href="{{ route('pengetahuan') }}"
                    class="text-gray-600 text-sm hover:text-blue-700 transition">Pengetahuan</a>
                <a href="{{ route('dokumen') }}"
                    class="text-gray-600 text-sm hover:text-blue-700 transition">Dokumen</a>
            </nav>
            {{-- Tombol Masuk --}}
            <a href="{{ route('login') }}"
                class="bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                Masuk
            </a>
            {{-- Tambahkan tombol menu untuk mobile jika diperlukan --}}
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="relative py-20 bg-cover bg-center text-white"
        style="background-image: url('{{ asset('assets/img/Beranda.png') }}');">
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="max-w-[1200px] mx-auto w-full flex justify-between items-center relative z-10 px-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-2 drop-shadow-lg leading-tight">
                    Knowledge Management<br>System
                </h1>
                <p class="text-base md:text-lg font-semibold drop-shadow">
                    Dinas Komunikasi Informatika dan Statistik Provinsi Lampung
                </p>
            </div>
            <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Diskominfotik Lampung"
                class="h-32 md:h-40 drop-shadow-xl hidden md:block">
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 px-6 py-10">

        {{-- Sidebar Bidang --}}
        <aside class="lg:col-span-1 bg-white shadow-xl rounded-2xl p-6 h-fit">
            <h3 class="font-bold text-lg mb-6 text-gray-800 border-b pb-3">Bidang</h3>
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
                <li
                    class="flex items-center gap-4 cursor-pointer group p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <span
                        class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow transition-transform group-hover:scale-110">
                        <i class="fas {{ $bidang['icon'] }} text-white text-lg"></i>
                    </span>
                    <span
                        class="font-medium text-base text-gray-700 group-hover:text-blue-700">{{ $bidang['nama'] }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Konten Kanan (Pengetahuan & Dokumen) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Bagian Pengetahuan --}}
            <section class="bg-white shadow-xl rounded-2xl p-6">
                <h2 class="font-bold text-xl mb-6 text-gray-800 border-b pb-3">Pengetahuan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @php
                    $pengetahuan = [
                    // Item 1
                    ['img' => 'assets/img/pengetahuan_1.png', 'title' => 'Matriks Renstra Dinas Kominfotik Provinsi
                    Lampung Tahun 2015-2019'],
                    // Item 2
                    ['img' => 'assets/img/pengetahuan_2.png', 'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun
                    2017 Download'],
                    // PERBAIKAN: Menambahkan 2 item lagi agar sesuai desain
                    // Item 3 (duplikat dari item 1)
                    ['img' => 'assets/img/pengetahuan_1.png', 'title' => 'Matriks Renstra Dinas Kominfotik Provinsi
                    Lampung Tahun 2015-2019'],
                    // Item 4 (duplikat dari item 2)
                    ['img' => 'assets/img/pengetahuan_2.png', 'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun
                    2017 Download'],
                    ];
                    @endphp
                    @foreach ($pengetahuan as $item)
                    <div
                        class="border border-gray-200 rounded-xl overflow-hidden flex flex-col group transition-shadow hover:shadow-lg">
                        <img src="{{ asset($item['img']) }}" class="w-full h-44 object-cover" alt="Foto Pengetahuan">
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="font-semibold text-base leading-tight text-gray-800 flex-grow mb-4">
                                {{ $item['title'] }}</h3>
                            <a href="#"
                                class="text-blue-700 text-sm font-semibold self-start group-hover:underline">Download</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Bagian Dokumen --}}
            <section class="bg-white shadow-xl rounded-2xl p-6">
                <h2 class="font-bold text-xl mb-6 text-gray-800 border-b pb-3">Dokumen</h2>
                <div class="space-y-6">
                    @php
                    $dokumen = [
                    ['img' => 'assets/img/struktur_bagan_dokumen.png', 'title' => 'Struktur Instansi'],
                    ['img' => 'assets/img/struktur_bagan_dokumen.png', 'title' => 'Struktur Instansi']
                    ];
                    @endphp
                    @foreach ($dokumen as $item)
                    {{-- PERBAIKAN UTAMA ADA DI DALAM DIV INI --}}
                    <div
                        class="flex flex-col sm:flex-row items-start gap-6 p-4 rounded-lg border border-transparent hover:border-gray-200 hover:bg-gray-50 transition-colors duration-300">

                        {{-- Kolom Gambar --}}
                        <div class="w-full sm:w-1/3 flex-shrink-0">
                            <a href="#">
                                <img src="{{ asset($item['img']) }}" alt="{{ $item['title'] }}"
                                    class="w-full h-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                            </a>
                        </div>

                        {{-- Kolom Teks (Dibuat menjadi flex column) --}}
                        <div class="flex-grow flex flex-col w-full">
                            {{-- Bagian Atas (Judul & List) --}}
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-gray-800">{{ $item['title'] }}</h3>
                                <ol class="list-decimal list-inside text-sm text-gray-600 mt-2 space-y-1">
                                    <li>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi,
                                        Tugas dan Fungsi Serta Tatakerja Perangkat Daerah, Susunan Organisasi Dinas
                                        Komunikasi, Informatika dan Statistik, terdiri dari:</li>
                                    <li>a. Kepala Dinas;</li>
                                    <li>b. Sekretariat;</li>
                                    <li>c. Bidang Pengelolaan dan Layanan Informasi Publik...</li>
                                </ol>
                            </div>

                            {{-- Bagian Bawah (Tombol Download) --}}
                            <div class="mt-4">
                                <a href="#"
                                    class="inline-block bg-blue-600 text-white text-sm font-semibold px-5 py-2 rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:scale-105">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
    </main>

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
                        <li><a href="/" class="hover:underline hover:text-white">Home</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kegiatan</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Dokumen</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">Kontak</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Media Sosial --}}
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