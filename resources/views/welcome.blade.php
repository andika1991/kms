<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Knowledge Management System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8fafc] font-figtree">

    {{-- HEADER --}}
    <header class="bg-white shadow-sm sticky top-0 z-20">
        <div class="max-w-[1400px] mx-auto flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-7">
            </div>
            <nav class="flex gap-8">
                <a href="#" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Tentang
                    Kami</a>
                <a href="#" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Kegiatan</a>
                <a href="#" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Dokumen</a>
            </nav>
            <a href="{{ route('login') }}"
                class="bg-blue-900 text-white text-sm font-semibold px-6 py-2 rounded-xl shadow hover:bg-blue-700 transition">Masuk</a>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative min-h-[320px] md:min-h-[360px] flex items-center"
        style="background: url('{{ asset('assets/img/Background-line-landing-page.png') }}') center/cover no-repeat;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="max-w-[1400px] mx-auto w-full flex justify-between items-center relative z-10 px-6 py-10 md:py-16">
            <div>
                <h1 class="text-white text-3xl md:text-4xl font-bold mb-2 drop-shadow leading-snug">
                    Knowledge Management<br>System
                </h1>
                <p class="text-white text-base md:text-lg font-semibold drop-shadow">
                    Dinas Komunikasi Informatika dan Statistik Provinsi Lampung
                </p>
            </div>
            <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Diskominfotik Lampung"
                class="h-28 md:h-36 drop-shadow-xl hidden md:block">
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-8 mt-[-70px] pb-10 z-20 relative">

        {{-- Sidebar Bidang --}}
        <aside
            class="bg-white shadow-lg rounded-2xl px-8 py-8 w-full max-w-[250px] mx-auto lg:mx-0 mb-8 lg:mb-0 flex flex-col items-center">
            <h3 class="font-bold text-lg mb-6 text-left w-full">Bidang</h3>
            <ul class="flex flex-col gap-5 w-full">
                @php
                $bidangs = [
                'Sekretariat',
                'PLIP',
                'PKP',
                'TIK',
                'SanStik'
                ];
                @endphp
                @foreach ($bidangs as $bidang)
                <li class="flex items-center gap-4">
                    <span class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow">
                        {{-- Icon koper/briefcase --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 20 20" class="w-6 h-6">
                            <path
                                d="M6 7V6a4 4 0 1 1 8 0v1h2.25A1.75 1.75 0 0 1 18 8.75v7.5A1.75 1.75 0 0 1 16.25 18H3.75A1.75 1.75 0 0 1 2 16.25v-7.5A1.75 1.75 0 0 1 3.75 7H6Zm2-1a2 2 0 1 1 4 0v1H8V6Z" />
                        </svg>
                    </span>
                    <span class="font-medium text-base">{{ $bidang }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Grid Pengetahuan --}}
        <section class="flex-1">
            <h2 class="font-bold text-xl mb-5">Pengetahuan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @php
                $pengetahuan = [
                [
                'img' => 'assets/img/pengetahuan_1.png',
                'title' => 'Matriks Renstra Dinas Kominfotik Provinsi Lampung Tahun 2015-2019',
                'link' => '#'
                ],
                [
                'img' => 'assets/img/pengetahuan_2.png',
                'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun 2017 Download',
                'link' => '#'
                ],
                [
                'img' => 'assets/img/pengetahuan_3.png',
                'title' => 'Matriks Renstra Dinas Kominfotik Provinsi Lampung Tahun 2015-2019',
                'link' => '#'
                ],
                [
                'img' => 'assets/img/pengetahuan_4.png',
                'title' => 'PK Dinas Kominfotik Provinsi Lampung Tahun 2017 Download',
                'link' => '#'
                ]
                ];
                @endphp
                @foreach ($pengetahuan as $item)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden flex flex-col">
                    <img src="{{ asset($item['img']) }}" class="w-full h-48 object-cover" alt="Foto Pengetahuan">
                    <div class="p-5 flex flex-col gap-2">
                        <h3 class="font-semibold text-base leading-tight">{{ $item['title'] }}</h3>
                        <a href="{{ $item['link'] }}"
                            class="text-blue-700 text-sm underline hover:text-blue-900">Download</a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-blue-900 text-white pt-8 pb-6 mt-8">
        <div class="container mx-auto grid md:grid-cols-3 gap-8 px-6">
            <div>
                <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo"
                    class="h-8 mb-2">
                <p class="text-xs font-semibold">Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</p>
                <p class="text-xs">Jl. WR Monginsidi No.69 Bandar Lampung<br>Telepon: (0721) 481007<br>Facebook:
                    /diskominfo.lpg<br>Instagram: /diskominfotiklampung</p>
            </div>
            <div>
                <h4 class="font-bold mb-3">Menu</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                    <li><a href="#" class="hover:underline">Kegiatan</a></li>
                    <li><a href="#" class="hover:underline">Dokumen</a></li>
                    <li><a href="#" class="hover:underline">Kontak</a></li>
                </ul>
            </div>
            <div class="flex items-center gap-4 mt-5 md:mt-0">
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img
                        src="{{ asset('img/instagram.svg') }}" alt="IG" class="h-7"></a>
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img
                        src="{{ asset('img/facebook.svg') }}" alt="FB" class="h-7"></a>
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img
                        src="{{ asset('img/youtube.svg') }}" alt="YT" class="h-7"></a>
            </div>
        </div>
    </footer>

</body>

</html>