@extends('home.app')
@section('title', 'Tentang Kami - Knowledge Management System')

@section('content')

{{-- HERO SECTION: TENTANG KAMI --}}
<section class="relative w-full overflow-hidden pt-18 md:pt-24 pb-12 md:pb-20 bg-white">
    <img src="{{ asset('assets/img/tentang_kami_kms.svg') }}" alt="Pattern Tentang Kami"
        class="absolute inset-0 w-full h-full object-contain object-left-top z-0 pointer-events-none select-none"
        style="min-height:360px;" />

    {{-- GRADIENT PUTIH OVERLAY KANAN --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="w-full h-full bg-gradient-to-r from-white/5 via-white/60 to-white"></div>
    </div>

    <main
        class="relative max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center px-4 md:px-8 min-h-[400px]">
        <figure class="w-full hidden md:block h-[320px]"></figure>
        <header class="flex flex-col items-start justify-center">
            <div class="flex flex-wrap items-end gap-x-3">
                <h1 class="text-[2.2rem] sm:text-5xl md:text-6xl leading-[1.1] font-black text-blue-700 tracking-tight mb-2 md:mb-3"
                    style="font-family: 'Figtree', sans-serif;">
                    TENTANG<br>
                    <span class="underline underline-offset-8 decoration-4 decoration-blue-400">KAMI</span>
                </h1>
            </div>
            <p class="text-gray-800 text-base md:text-lg font-medium max-w-xl mt-1 md:mt-2 mb-6">
                Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung merupakan penyelenggara urusan
                pemerintahan dan mempunyai tugas di bidang Komunikasi dan Informatika, Statistik dan Persandian. Dinas
                Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung dipimpin oleh seorang Kepala Dinas
                yang berkedudukan di bawah dan bertanggung jawab kepada Gubernur melalui Sekretaris Daerah.
            </p>
            <a href="{{ route('home') }}"
                class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-full shadow text-sm transition-all duration-200">
                Mulai
            </a>
        </header>
    </main>
</section>

{{-- VISI, MISI, TUGAS, FUNGSI --}}
<section class="relative py-12 md:py-16 bg-transparent" id="visi-misi">
    <div class="max-w-[1200px] mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- VISI & MISI --}}
        <article class="bg-white/95 rounded-2xl shadow-lg p-6 flex flex-col">
            <header>
                <h2 class="font-bold text-lg md:text-xl mb-2 tracking-wide">Visi</h2>
            </header>
            <p class="text-gray-700 mb-3 text-sm md:text-base leading-relaxed">
                Terwujudnya Pusat Informasi Dan Komunikasi Untuk Menunjang Pembangunan Daerah Menuju Lampung Unggul Dan
                Berdaya Saing
            </p>
            <header class="mt-3">
                <h3 class="font-bold text-md md:text-lg mb-1">Misi</h3>
            </header>
            <ul class="list-disc pl-5 space-y-2 text-sm md:text-base text-gray-700">
                <li>Meningkatkan Daya Dukung Infrastruktur Teknologi Komunikasi dan Informasi untuk Memperluas Akses
                    Masyarakat terhadap Informasi Pembangunan Daerah.</li>
                <li>Meningkatkan Kompetensi Sumber Daya Manusia bidang Komunikasi dan Informatika secara Profesional.
                </li>
                <li>Meningkatkan Kualitas Layanan Komunikasi dan Informasi kepada Masyarakat dalam rangka Mewujudkan
                    Masyarakat Berbudaya Informasi.</li>
            </ul>
        </article>
        {{-- TUGAS --}}
        <article class="bg-white/95 rounded-2xl shadow-lg p-6 flex flex-col">
            <header>
                <h2 class="font-bold text-lg md:text-xl mb-2 tracking-wide">Tugas</h2>
            </header>
            <p class="text-gray-700 text-sm md:text-base leading-relaxed">
                Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi Serta
                Takerja Perangkat Daerah, Dinas Komunikasi, Informatika dan Statistik mempunyai tugas membantu Gubernur
                melaksanakan Urusan Pemerintahan di bidang Komunikasi, Informatika, bidang Persandian dan bidang
                Statistik berdasarkan asas otonomi yang menjadi kewenangan, tugas dekonsentrasi dan tugas pembantuan
                serta tugas lain sesuai dengan kebijakan yang ditetapkan oleh Gubernur berdasarkan peraturan
                perundang-undangan.
            </p>
        </article>
        {{-- FUNGSI --}}
        <article class="bg-white/95 rounded-2xl shadow-lg p-6 flex flex-col">
            <header>
                <h2 class="font-bold text-lg md:text-xl mb-2 tracking-wide">Fungsi</h2>
            </header>
            <ol class="list-decimal pl-5 space-y-2 text-sm md:text-base text-gray-700">
                <li>Perumusan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik.</li>
                <li>Pelaksanaan kebijakan di bidang Komunikasi, Informatika, Persandian dan Statistik.</li>
                <li>Pelaksanaan evaluasi dan pelaporan di bidang Komunikasi, Informatika, Persandian dan Statistik.</li>
                <li>Pelaksanaan administrasi di bidang Komunikasi, Informatika, Persandian dan Statistik.</li>
                <li>Pelaksanaan fungsi lain yang diberikan oleh Gubernur.</li>
            </ol>
        </article>
    </div>
</section>

{{-- BIDANG--}}
<section class="w-full py-12 bg-[#2b6cb0]">
    <div class="w-full text-center px-0">
        <img src="{{ asset('assets/img/group_bidang.png') }}" alt="Bidang" class="w-full h-auto object-cover mx-auto">
    </div>
</section>

{{-- STRUKTUR ORGANISASI DISKOMINFOTIK --}}
<section class="w-full py-10 md:py-16 bg-white">
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center px-4 md:px-8">
        {{-- Gambar Struktur Organisasi --}}
        <figure class="w-full flex justify-center md:justify-end">
            <img src="{{ asset('assets/img/struktur_organisasi_kms.png') }}" alt="Struktur Organisasi Diskominfotik"
                class="w-full max-w-[440px] rounded-xl shadow-md border object-contain bg-white" loading="lazy">
        </figure>

        {{-- Keterangan dan Tombol --}}
        <header class="flex flex-col h-full justify-center items-center md:items-start text-center md:text-left">
            <h2
                class="text-xl md:text-2xl font-bold text-gray-900 mb-2 md:mb-4 tracking-tight self-center md:self-start">
                Struktur Organisasi
            </h2>
            <ol class="list-decimal pl-6 text-base text-gray-800 mb-4 leading-relaxed text-left">
                <li>
                    Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan Fungsi
                    Serta Tatakerja Perangkat Daerah, Susunan Organisasi Dinas Komunikasi, Informatika dan Statistik,
                    terdiri dari:
                    <ul class="list-disc pl-5 mt-2 space-y-1 text-gray-700 text-base">
                        <li>a. Kepala Dinas;</li>
                        <li>b. Sekretariat;</li>
                        <li>c. Bidang Pengelolaan dan Layanan Informasi Publik;</li>
                        <li>d. Bidang Pengelolaan Komunikasi Publik;</li>
                        <li>e. Bidang Teknologi Informasi dan Komunikasi;</li>
                        <li>f. Bidang Tata Kelola Pemerintahan Berbasis Elektronik;</li>
                        <li>g. Bidang Persandian dan Statistik;</li>
                        <li>h. Unit Pelaksana Teknis Daerah (UPTD); dan</li>
                        <li>i. Kelompok Jabatan Fungsional.</li>
                    </ul>
                </li>
            </ol>
            <a href="https://diskominfotik.lampungprov.go.id/pages/struktur-organisasi" target="_blank" rel="noopener"
                class="mt-2 px-8 py-2 rounded-full bg-blue-700 hover:bg-blue-800 text-white font-semibold shadow text-base transition-all duration-200">
                Lihat
            </a>
        </header>
    </div>
</section>
@endsection