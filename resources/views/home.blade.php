@extends('home.app')
@section('title', 'Knowledge Management System')

@section('content')

{{-- HERO SECTION --}}
<section class="relative py-16 md:py-28 bg-cover bg-no-repeat bg-center overflow-hidden">
    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm"></div>
    <img src="{{ asset('assets/img/background_knowledge_management_system.png') }}" alt="background"
        class="absolute inset-0 w-full h-full object-cover object-center">

    <div class="relative z-10 max-w-[1200px] mx-auto px-6 flex flex-col md:flex-row items-center gap-8">
        {{-- Left side (text) --}}
        <div class="text-center md:text-left md:max-w-lg">
            <h1 class="text-4xl md:text-5xl font-bold text-blue-700 drop-shadow-lg leading-tight mb-4">
                Knowledge <br> Management <br> System
            </h1>
            <p class="text-lg md:text-xl font-medium text-gray-700 mb-6">
                <strong>Dinas Komunikasi Informatika dan Statistik Provinsi Lampung</strong><br>
                Merupakan sistem untuk mengelola pengetahuan dalam Diskominfotik Lampung.
            </p>

            <form action="#" method="GET" class="max-w-md mx-auto md:mx-0">
                <div class="flex rounded-full bg-white shadow-lg overflow-hidden border border-gray-200">
                    <input type="text" name="q" placeholder="Cari Dokumen atau Pengetahuan"
                        class="w-full px-4 py-3 border-none focus:ring-0 focus:outline-none rounded-l-full">
                    <button type="submit"
                        class="px-4 bg-blue-700 text-white hover:bg-blue-800 transition duration-300 rounded-r-full">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right side (Logo) --}}
        <div class="hidden md:flex justify-end items-center flex-1 -ml-32">
            <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Diskominfotik Lampung"
                class="w-60 h-60 object-contain">
        </div>
    </div>
</section>

{{-- KEGIATAN DISKOMINFOTIK --}}
<section class="relative py-12 md:py-20 flex items-center min-h-[600px]">
    <div class="absolute inset-0 pointer-events-none z-0">
        <img src="{{ asset('assets/img/background_kegiatan_diskominfotik.png') }}"
            alt="Background Kegiatan Diskominfotik" class="w-full h-full object-cover"
            style="filter: grayscale(1) contrast(1.1) brightness(0.98);" draggable="false" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-transparent to-transparent"></div>
    </div>
    <div class="relative w-full max-w-[1200px] mx-auto flex flex-col md:flex-row items-center px-6 gap-10 z-10">
        <div class="flex-1 text-white">
            <h2 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow leading-tight">
                Kegiatan<br>Diskominfotik
            </h2>
            <p class="mb-8 text-base md:text-lg drop-shadow">
                Berbagai aktivitas atau program yang dilaksanakan oleh Dinas Komunikasi, Informatika dan Statistik
                Provinsi Lampung dalam rangka mencapai tujuan dan sasaran yang telah ditetapkan.
            </p>
            <a href="{{ route('kegiatan') }}"
                class="inline-block px-6 py-2 bg-white text-blue-700 rounded-full shadow-md hover:bg-gray-100 font-semibold text-sm transition-all duration-200">
                Kegiatan
            </a>
        </div>
        <div class="flex-1 flex justify-center md:justify-end w-full">
            <div class="relative w-full max-w-xs">
                <!-- Slider Content -->
                <div id="slider-content" class="transition-transform duration-500 ease-in-out">
                    @foreach($kegiatans as $i => $kegiatan)
                    <div class="kegiatan-slide @if($i !== 0) hidden @endif">
                        <!-- Redesigned Card -->
                        <div class="rounded-2xl shadow-xl bg-white p-3 w-full mx-auto">
                            <img src="{{ $kegiatan->fotokegiatan->first() ? asset('storage/'.$kegiatan->fotokegiatan->first()->path_foto) : asset('assets/img/no-image.png') }}"
                                alt="{{ $kegiatan->nama_kegiatan }}" class="w-full h-64 object-cover rounded-xl" />
                            <div class="pt-4 pb-2 text-center">
                                <p class="font-semibold text-gray-800 text-base mb-1 leading-tight">
                                    {{ $kegiatan->nama_kegiatan }}
                                </p>
                                <span
                                    class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kegiatan->waktu)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Panah Kiri (Posisi Diperbaiki) -->
                <button id="btnPrevKegiatan"
                    class="absolute top-1/2 left-[-50px] -translate-y-1/2 z-20 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-blue-600 hover:text-white rounded-full shadow-md w-9 h-9 flex items-center justify-center transition-all duration-200"
                    aria-label="Prev">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 h-5"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <!-- Panah Kanan (Posisi Diperbaiki) -->
                <button id="btnNextKegiatan"
                    class="absolute top-1/2 right-[-52px] -translate-y-1/2 z-20 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-blue-600 hover:text-white rounded-full shadow-md w-9 h-9 flex items-center justify-center transition-all duration-200"
                    aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 h-5"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- DOKUMEN & ARTIKEL PENGETAHUAN --}}
<section class="py-10 md:py-16">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Dokumen Section --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-start mb-6 gap-4">
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold text-blue-700 mb-2">Dokumen</h3>
                        <p class="text-gray-600 text-sm">Akses berbagai dokumen resmi, panduan, dan regulasi terkait
                            komunikasi, informatika, dan statistik dari Pemerintah Provinsi Lampung.</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-2xl font-bold">{{ $totalDokumen }}</span>
                        <span class="text-sm">Total Dokumen</span>
                        <a href="{{ route('dokumen') }}"
                            class="mt-3 px-4 py-1.5 border border-blue-700 text-blue-700 rounded-full hover:bg-blue-700 hover:text-white transition text-sm">Selengkapnya</a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($dokumens as $dokumen)
                    <a href="{{ route('dokumen.show', $dokumen->id) }}"
                        class="border rounded-lg p-3 shadow hover:shadow-md transition block group focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>

                        <p class="text-sm font-semibold mb-1 group-hover:text-blue-700">{{ $dokumen->nama_dokumen }}</p>
                        <p class="text-xs text-gray-500 mb-2">{{ $dokumen->deskripsi }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $dokumen->created_at->format('d/m/Y') }}</span>
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i> {{ number_format($dokumen->views_count) }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Artikel Section --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-start mb-6 gap-4">
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold text-blue-700 mb-2">Artikel Pengetahuan</h3>
                        <p class="text-gray-600 text-sm">Jelajahi inovasi teknologi, data statistik terkini, dan program
                            digitalisasi dari Diskominfotik Lampung.</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-2xl font-bold">{{ $totalArtikel }}</span>
                        <span class="text-sm">Total Artikel Pengetahuan</span>
                        <a href="{{ route('pengetahuan') }}"
                            class="mt-3 px-4 py-1.5 border border-blue-700 text-blue-700 rounded-full hover:bg-blue-700 hover:text-white transition text-sm">Selengkapnya</a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($artikels as $artikel)
                    <div class="border rounded-lg p-3 shadow hover:shadow-md transition">
                        <img src="{{ asset('storage/'.$artikel->thumbnail) }}"
                            class="w-full h-24 object-cover rounded mb-2">
                        <p class="text-sm font-semibold mb-2">{{ $artikel->judul }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <a href="{{ route('artikel.show', $artikel->slug) }}"
                                class="text-blue-700 hover:underline">Lihat</a>
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let slides = document.querySelectorAll('.kegiatan-slide');
    let current = 0;
    const showSlide = (idx) => {
        slides.forEach((slide, i) => {
            slide.classList.toggle('hidden', i !== idx);
        });
    };
    document.getElementById('btnPrevKegiatan').onclick = function() {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
    };
    document.getElementById('btnNextKegiatan').onclick = function() {
        current = (current + 1) % slides.length;
        showSlide(current);
    };
    // Support swipe for mobile (optional)
    let startX = null;
    const slider = document.getElementById('slider-content');
    slider.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });
    slider.addEventListener('touchend', function(e) {
        if (!startX) return;
        let endX = e.changedTouches[0].clientX;
        if (endX - startX > 30) { // swipe right
            document.getElementById('btnPrevKegiatan').click();
        } else if (startX - endX > 30) { // swipe left
            document.getElementById('btnNextKegiatan').click();
        }
        startX = null;
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const listBidang = document.querySelectorAll('.bidang-item');
    const listSubbidangKanan = document.getElementById('listSubbidangKanan');
    const subbidangWrapperKanan = document.getElementById('subbidangWrapperKanan');
    const listArtikel = document.getElementById('listArtikel');

    listBidang.forEach(item => {
        item.addEventListener('click', function() {
            const bidangId = this.dataset.id;

            // Kosongkan artikel dan subbidang
            listArtikel.innerHTML = '<p class="text-gray-500">Memuat artikel...</p>';
            listSubbidangKanan.innerHTML = '';
            subbidangWrapperKanan.classList.add('hidden');

            // Ambil artikel berdasarkan bidang
            fetch(`/artikel/bidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    listArtikel.innerHTML = '';
                    if (data.length === 0) {
                        listArtikel.innerHTML =
                            '<p class="text-gray-500">Belum ada artikel pada bidang ini.</p>';
                    } else {
                        data.forEach(artikel => {
                            const card = document.createElement('div');
                            card.className =
                                'border rounded-lg p-4 shadow hover:shadow-md transition';
                            card.innerHTML = `
  <h4 class="text-lg font-semibold text-gray-800 mb-2">${artikel.judul}</h4>
  <img src="/storage/${artikel.thumbnail}" alt="${artikel.judul}" class="w-full h-40 object-cover mb-2 rounded" />
  <p class="text-sm text-gray-600 mb-2">Oleh: ${artikel.pengguna?.name || '-'}</p>
  <p class="text-gray-700 text-sm mb-3">${artikel.isi.substring(0, 150)}...</p>
<a href="/artikel/${artikel.slug}" class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600 transition">
  Lihat
</a>

`;

                            listArtikel.appendChild(card);
                        });
                    }
                });

            // Ambil subbidang
            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    listSubbidangKanan.innerHTML = '';
                    if (data.length > 0) {
                        subbidangWrapperKanan.classList.remove('hidden');
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className =
                                'px-4 py-2 bg-blue-100 hover:bg-blue-200 text-sm rounded shadow text-blue-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function() {
                                fetch(`/artikel/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        listArtikel.innerHTML = '';
                                        if (data.length === 0) {
                                            listArtikel.innerHTML =
                                                '<p class="text-gray-500">Belum ada artikel pada subbidang ini.</p>';
                                            return;
                                        }

                                        data.forEach(artikel => {
                                            const card =
                                                document
                                                .createElement(
                                                    'div');
                                            card.className =
                                                'border rounded-lg p-4 shadow hover:shadow-md transition';
                                            card.innerHTML = `
  <h4 class="text-lg font-semibold text-gray-800 mb-2">${artikel.judul}</h4>
  <img src="/storage/${artikel.thumbnail}" alt="${artikel.judul}" class="w-full h-40 object-cover mb-2 rounded" />
  <p class="text-sm text-gray-600 mb-2">Oleh: ${artikel.pengguna?.name || '-'}</p>
  <p class="text-gray-700 text-sm mb-3">${artikel.isi.substring(0, 150)}...</p>
<a href="/artikel/${artikel.slug}" class="inline-block bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600 transition">
  Lihat
</a>

`;

                                            listArtikel
                                                .appendChild(
                                                    card);
                                        });
                                    });
                            });

                            listSubbidangKanan.appendChild(btn);
                        });
                    }
                });
        });

    });
});
</script>
@endpush
</body>

</html>