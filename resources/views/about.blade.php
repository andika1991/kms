@extends('home.app')
@section('title', 'Tentang Kami - Knowledge Management System')

@section('content')

{{-- HERO SECTION: TENTANG KAMI --}}
<section class="relative w-full overflow-hidden pt-8 md:pt-14 pb-12 md:pb-20 bg-white">
    {{-- SVG Pattern Background --}}
    <img src="{{ asset('assets/img/tentang_kami_kms.svg') }}" alt="Pattern Tentang Kami"
        class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none" />

    <main class="relative z-10 max-w-[1200px] mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center px-4 md:px-8 min-h-[400px]">
        {{-- Kolom Kiri (kosong, bisa diisi gambar jika perlu) --}}
        <figure class="w-full hidden md:flex justify-center md:justify-start"></figure>

        {{-- Kolom Kanan: Judul + Paragraf --}}
        <header class="flex flex-col md:flex-row md:items-start md:gap-x-6">
            {{-- Blok Judul: Diberi flex-shrink-0 agar lebarnya tidak menyusut saat bersebelahan dengan teks. --}}
            <div class="flex-shrink-0">
                <h1 class="text-[2.2rem] sm:text-5xl md:text-6xl leading-[1.1] font-black text-blue-700 tracking-tight">
                    TENTANG<br>
                    <span class="underline underline-offset-8 decoration-4 decoration-blue-400">KAMI</span>
                </h1>
            </div>
            <div class="mt-4 md:pt-[4.125rem]">
                <p class="text-gray-700 text-base md:text-lg font-medium max-w-xl">
                    Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung merupakan penyelenggara
                    urusan pemerintahan dan mempunyai tugas di bidang Komunikasi dan Informatika, Statistik dan
                    Persandian. Dinas Komunikasi, Informatika dan Statistik Pemerintah Provinsi Lampung dipimpin oleh
                    seorang Kepala Dinas yang berkedudukan di bawah dan bertanggung jawab kepada Gubernur melalui
                    Sekretaris Daerah.
                </p>

                {{-- Tombol diberi margin atas (mt-6) untuk jarak dari paragraf di atasnya. --}}
                <a href="#visi-misi"
                    class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-full shadow text-sm transition-all duration-200 mt-6">
                    Mulai
                </a>
            </div>
        </header>
    </main>
</section>

{{-- VISI, MISI, TUGAS, FUNGSI --}}
<section class="relative py-12 md:py-16 bg-transparent" id="visi-misi">
    <div class="max-w-[1200px] mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- VISI & MISI --}}
        <article class="bg-white/95 rounded-2xl shadow-lg p-6 flex flex-col">
            <header>
                <h2 class="font-bold text-blue-700 text-lg md:text-xl mb-2 tracking-wide">Visi</h2>
            </header>
            <p class="text-gray-700 mb-3 text-sm md:text-base leading-relaxed">
                Terwujudnya Pusat Informasi Dan Komunikasi Untuk Menunjang Pembangunan Daerah Menuju Lampung Unggul Dan
                Berdaya Saing
            </p>
            <header class="mt-3">
                <h3 class="font-bold text-blue-600 text-md md:text-lg mb-1">Misi</h3>
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
                <h2 class="font-bold text-blue-700 text-lg md:text-xl mb-2 tracking-wide">Tugas</h2>
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
                <h2 class="font-bold text-blue-700 text-lg md:text-xl mb-2 tracking-wide">Fungsi</h2>
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

{{-- BIDANG DAN STRUKTUR ORGANISASI tetap gunakan bawaan lama --}}
<section class="w-full py-12 bg-[#2b6cb0]">
    <div class="w-full text-center px-0">
        <img src="{{ asset('assets/img/group_bidang.png') }}" alt="Bidang" class="w-full h-auto object-cover mx-auto">
    </div>
</section>

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

@endsection