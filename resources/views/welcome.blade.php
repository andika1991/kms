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
        <div class="container mx-auto flex items-center justify-between py-3 px-6">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="Logo Diskominfo" class="h-8">
                <span class="font-bold text-sm text-blue-900 ml-1"></span>
            </div>
            <nav class="flex gap-7">
                <a href="#" class="hover:font-bold hover:text-blue-800 transition">Beranda</a>
                <a href="#" class="hover:font-bold hover:text-blue-800 transition">Tentang Kami</a>
                <a href="#" class="hover:font-bold hover:text-blue-800 transition">Kegiatan</a>
                <a href="#" class="hover:font-bold hover:text-blue-800 transition">Dokumen</a>
            </nav>
            <div>
                <a href="{{ route('login') }}" class="bg-blue-900 text-white px-5 py-2 rounded-xl shadow hover:bg-blue-700 transition">Masuk</a>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="relative bg-gray-900 h-[340px] flex items-center justify-center" style="background: url('{{ asset('assets/img/Background-line-landing-page.png') }}') center/cover no-repeat;">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="container relative z-10 flex items-center justify-between px-6">
            <div>
                <h1 class="text-white text-4xl font-bold mb-2 leading-tight drop-shadow">Knowledge Management System</h1>
                <p class="text-white text-lg font-semibold drop-shadow">Dinas Komunikasi Informatika dan Statistik Provinsi Lampung</p>
            </div>
            <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo" class="h-32 drop-shadow-lg hidden md:block">
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="container mx-auto flex flex-col lg:flex-row gap-6 mt-[-80px] pb-10 z-20 relative">

        {{-- Sidebar Bidang --}}
        <aside class="bg-white shadow-lg rounded-2xl p-6 w-full lg:w-64 mb-6 lg:mb-0">
            <h3 class="font-bold text-lg mb-5">Bidang</h3>
            <ul class="flex flex-col gap-3">
                @foreach (['Sekretariat','PLIP','PKP','TIK','SanStik'] as $bidang)
                <li class="flex items-center gap-3">
                    <span class="bg-orange-400 text-white rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor"><!-- icon here --></svg>
                    </span>
                    <span class="font-medium">{{ $bidang }}</span>
                </li>
                @endforeach
            </ul>
        </aside>

        {{-- Grid Pengetahuan --}}
        <section class="flex-1">
            <h2 class="font-bold text-xl mb-4">Pengetahuan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @for ($i = 0; $i < 4; $i++)
                <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
                    <img src="{{ asset('img/foto-kegiatan.jpg') }}" class="w-full h-44 object-cover" alt="Foto Pengetahuan">
                    <div class="p-4">
                        <h3 class="font-semibold text-base mb-1 leading-tight">Matriks Renstra Dinas Kominfotik Provinsi Lampung Tahun 2015-2019</h3>
                        <a href="#" class="text-blue-700 text-sm underline hover:text-blue-900">Download</a>
                    </div>
                </div>
                @endfor
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-blue-900 text-white pt-8 pb-6 mt-8">
        <div class="container mx-auto grid md:grid-cols-3 gap-8 px-6">
            <div>
                <img src="{{ asset('img/logo-diskominfo.png') }}" alt="Logo Diskominfo" class="h-8 mb-2">
                <p class="text-xs font-semibold">Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</p>
                <p class="text-xs">Jl. WR Monginsidi No.69 Bandar Lampung<br>Telepon: (0721) 481007<br>Facebook: /diskominfo.lpg<br>Instagram: /diskominfotiklampung</p>
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
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img src="{{ asset('img/instagram.svg') }}" alt="IG" class="h-7"></a>
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img src="{{ asset('img/facebook.svg') }}" alt="FB" class="h-7"></a>
                <a class="bg-white/20 rounded-full p-2 hover:bg-white/30 transition" href="#"><img src="{{ asset('img/youtube.svg') }}" alt="YT" class="h-7"></a>
            </div>
        </div>
    </footer>

</body>
</html>
