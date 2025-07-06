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
<body class="bg-[#f8fafc] font-figtree">

    {{-- HEADER --}}
    <header class="bg-white shadow-sm sticky top-0 z-20">
        <div class="max-w-[1400px] mx-auto flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/KMS_Diskominfotik.png') }}" alt="KMS DISKOMINFOTIK" class="h-7">
            </div>
            <nav class="flex gap-8">
                <a href="{{ url('/') }}" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Beranda</a>
                <a href="{{ route('about') }}" class="text-blue-900 text-sm font-bold">Tentang Kami</a>
                <a href="#" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Kegiatan</a>
                <a href="#" class="text-black/90 text-sm hover:font-bold hover:text-blue-900 transition">Dokumen</a>
            </nav>
            <a href="{{ route('login') }}"
               class="bg-blue-900 text-white text-sm font-semibold px-6 py-2 rounded-xl shadow hover:bg-blue-700 transition">Masuk</a>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="relative min-h-[320px] flex items-center" style="background: url('{{ asset('assets/img/Background-line-landing-page.png') }}') center/cover no-repeat;">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="max-w-[900px] mx-auto w-full relative z-10 text-center flex flex-col items-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow mt-16 mb-3">Tentang Kami</h1>
            <div class="bg-white/90 rounded-lg shadow-lg px-6 py-4 text-black text-base font-medium w-full max-w-3xl">
                Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung merupakan penyelenggara urusan pemerintahan dan mempunyai tugas di bidang Komunikasi dan Informatika, Statistik dan Persandian. Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung dipimpin oleh seorang Kepala Dinas yang berkedudukan di bawah dan bertanggung jawab kepada Gubernur melalui Sekretaris Daerah.
            </div>
        </div>
    </section>

    {{-- VISI & MISI --}}
    <section class="max-w-5xl mx-auto mt-12 flex flex-col md:flex-row gap-8 px-4">
        <div class="md:w-2/5 flex items-center justify-center">
            <img src="{{ asset('assets/img/visi_misi.png') }}"
                 alt="Kantor Dinas Kominfotik"
                 class="rounded-2xl shadow-lg w-[260px] md:w-[300px] h-auto object-contain" />
        </div>
        <div class="md:w-3/5 flex flex-col justify-center">
            <h2 class="font-bold text-lg mb-1">Visi</h2>
            <p class="mb-3 text-sm md:text-base">Terwujudnya Pusat Informasi Dan Komunikasi Untuk Menunjang Pembangunan Daerah Menuju Lampung Unggul Dan Berdaya Saing</p>
            <h2 class="font-bold text-lg mb-1 mt-4">Misi</h2>
            <ol class="list-decimal pl-5 space-y-1 text-sm md:text-base">
                <li>Meningkatkan Daya Dukung Infrastruktur Teknologi Komunikasi dan Informasi untuk Memperluas Akses Masyarakat terhadap Informasi Pembangunan Daerah.</li>
                <li>Meningkatkan Kompetensi Sumber Daya Manusia bidang Komunikasi dan Informatika secara Profesional.</li>
                <li>Meningkatkan Kualitas Layanan Komunikasi dan Informasi kepada Masyarakat dalam rangka Mewujudkan Masyarakat Berbudaya Informasi</li>
            </ol>
        </div>
    </section>

    {{-- TUGAS & FUNGSI --}}
    <section class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 my-12 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-2 text-[#254759]">Tugas</h2>
            <p class="text-sm leading-relaxed">
                Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta Takerja Perangkat Daerah, Dinas Komunikasi, Informatika dan Statistik mempunyai tugas membantu Gubernur melaksanakan Urusan Pemerintahan di bidang Komunikasi, Informatika, bidang Persandian dan bidang Statistik berdasarkan asas otonomi yang menjadi kewenangan, tugas dekonsentrasi dan tugas pembantuan serta tugas lain sesuai dengan kebijakan yang ditetapkan oleh Gubernur berdasarkan peraturan perundang-undangan.
            </p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-2 text-[#254759]">Fungsi</h2>
            <p class="text-sm leading-relaxed">
                Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta Takerja Perangkat Daerah, Dinas Komunikasi, Informatika dan Statistik mempunyai fungsi:
                <br>a. perumusan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>b. pelaksanaan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>c. pelaksanaan evaluasi dan pelaporan di bidang Komunikasi, Informatika, Persandian dan Statistik;
                <br>d. pelaksanaan administrasi di bidang Komunikasi, Informatika, Persandian dan Statistik; dan
                <br>e. pelaksanaan fungsi lain yang diberikan oleh Gubernur.
            </p>
        </div>
    </section>

    {{-- BIDANG --}}
    <section class="w-full bg-[#254759] bg-opacity-95 py-12 relative" style="background: url('{{ asset('assets/img/Background-line-landing-page.png') }}') center/cover no-repeat;">
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <h2 class="font-bold text-xl text-white mb-8 tracking-wide">BIDANG</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 justify-items-center">
                <div class="flex flex-col items-center">
                    <div class="bg-pink-500/90 rounded-full p-5 shadow mb-2">
                        {{-- Icon Bullhorn --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path fill="white" d="M6.5 7.5V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-2.5"/><path stroke="white" d="M6.5 17.5V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-2.5"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Pengelolaan dan<br>Layanan Informasi Publik</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-orange-400 rounded-full p-5 shadow mb-2">
                        {{-- Icon Shield --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path fill="white" d="M12 2l7 5v6.5c0 5.5-5 8.5-7 9-2-0.5-7-3.5-7-9V7l7-5z"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Persandian dan<br>Statistik</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-blue-500 rounded-full p-5 shadow mb-2">
                        {{-- Icon Globe --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="2"/><path stroke="white" d="M2 12h20"/><path stroke="white" d="M12 2a15.3 15.3 0 0 1 0 20"/><path stroke="white" d="M12 2a15.3 15.3 0 0 0 0 20"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Pengelolaan<br>Komunikasi Publik</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-red-400 rounded-full p-5 shadow mb-2">
                        {{-- Icon Cloud --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path fill="white" d="M17 16a4 4 0 1 0-7.33-2.67A5 5 0 1 0 6 20h11a4 4 0 0 0 0-8h-1a4 4 0 0 0 1 8z"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Tata Kelola Pemerintahan<br>Berbasis Elektronik</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-green-500 rounded-full p-5 shadow mb-2">
                        {{-- Icon Laptop Code --}}
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="14" rx="2" fill="white"/><path stroke="white" d="M8 12h8M8 16h8"/></svg>
                    </div>
                    <p class="text-white text-xs font-semibold">Teknologi Informasi<br>dan Komunikasi</p>
                </div>
            </div>
        </div>
    </section>

    {{-- STRUKTUR ORGANISASI --}}
    <section class="max-w-3xl mx-auto mt-12 mb-20 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
            <h2 class="font-bold text-lg mb-2 text-[#254759]">Struktur Organisasi</h2>
            <ol class="list-decimal pl-6 text-left mb-4 text-sm">
                <li>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta Takerja Perangkat Daerah, Susunan Organisasi Dinas Komunikasi, Informatika dan Statistik, terdiri dari:
                    <ul class="list-disc ml-4 mt-1">
                        <li>a. Kepala Dinas;</li>
                        <li>b. Sekretariat;</li>
                        <li>c. Bidang Pengelolaan dan Layanan Informasi Publik.....</li>
                    </ul>
                </li>
            </ol>
            <a href="#" class="inline-block bg-blue-700 hover:bg-blue-800 text-white px-7 py-2 rounded-full mt-2 font-semibold shadow transition">Lihat</a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-blue-900 text-white pt-8 pb-6 mt-8">
        <div class="container mx-auto grid md:grid-cols-3 gap-8 px-6">
            <div>
                <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Logo Diskominfo"
                     class="h-8 mb-2">
                <p class="text-xs font-semibold">Dinas Komunikasi, Informatika dan Statistik Provinsi Lampung</p>
                <p class="text-xs">Jl. WR Monginsidi No.69 Bandar Lampung<br>Telepon: (0721) 481007<br>Facebook: /diskominfo.lpg<br>Instagram: /diskominfotiklampung</p>
            </div>
            <div>
                <h4 class="font-bold mb-3">Menu</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline font-bold">Tentang Kami</a></li>
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
