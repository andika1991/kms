@extends('home.app')
@section('title', 'Kegiatan - Knowledge Management System')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
.portal-kegiatan-slider {
    position: relative;
}

/* PERBAIKAN 2: Memindahkan paginasi ke kanan bawah */
.swiper-pagination {
    position: absolute !important;
    bottom: 20px !important;
    right: 20px !important;
    left: auto !important;
    width: auto !important;
    text-align: right;
}

.swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background-color: rgba(255, 255, 255, 0.5);
    opacity: 1;
    transition: background-color 0.3s, width 0.3s;
    margin: 0 3px !important;
}

.swiper-pagination-bullet-active {
    width: 30px;
    border-radius: 5px;
    background-color: #ffffff;
}
</style>
@endpush

@section('content')
<section class="relative pb-16">
    <div class="absolute top-0 left-0 right-0 h-96 w-full">
        <div class="h-full w-full bg-cover bg-center"
            style="background-image: url('{{ asset('assets/img/background_section_kegiatan.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-black/30"></div>
    </div>

    <div class="relative max-w-[1100px] mx-auto px-6 pt-7 pb-48">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-white text-3xl font-black tracking-tight leading-tight drop-shadow-lg">Kegiatan</h1>
                <p class="text-white/90 text-sm mt-1 max-w-xl drop-shadow">
                    Berbagai aktivitas atau program yang dilaksanakan oleh Dinas Komunikasi, Informatika dan Statistik
                    Provinsi Lampung.
                </p>
            </div>
            <form action="{{ route('kegiatan') }}" method="GET" class="relative mt-4 md:mt-0 w-full max-w-xs">
                <input name="q" type="text" placeholder="Cari Kegiatan..."
                    class="w-full bg-transparent placeholder-white/80 text-white border-b-2 border-white/50 py-2 pr-10 pl-2 outline-none focus:border-white transition-colors duration-300 text-base"
                    autocomplete="off" />
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-white/80 hover:text-white"
                    aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <main class="relative -mt-40 max-w-[1100px] mx-auto bg-white rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8">
        {{-- SwiperJS Slider --}}
        <div class="swiper-container portal-kegiatan-slider mb-10">
            <div class="swiper-wrapper">
                @forelse($kegiatan as $item)
                <div class="swiper-slide">
                    <a href="/kegiatan/detail/{{ $item->id }}"
                        class="block rounded-xl overflow-hidden relative group h-80">
                        <img src="{{ asset('storage/' . ($item->fotokegiatan[0]->path_foto ?? 'default.jpg')) }}"
                            alt="{{ $item->nama_kegiatan }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <h2 class="text-white text-xl font-bold leading-tight drop-shadow-md">
                                {{ $item->nama_kegiatan }}</h2>
                        </div>
                    </a>
                </div>
                @empty
                <div class="swiper-slide">
                    <div class="h-80 flex items-center justify-center text-gray-500 bg-gray-100 rounded-xl">Tidak ada
                        kegiatan untuk ditampilkan</div>
                </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-10">
            {{-- Sidebar --}}
            <aside class="lg:col-span-1 h-fit">
                <h3 class="font-bold text-lg mb-6 text-gray-800 border-b pb-3">Bidang</h3>
                <ul class="flex flex-col gap-4" id="listBidangKegiatan">
                    @foreach ($bidangs as $bidang)
                    <li class="bidang-item-kegiatan flex items-center gap-4 cursor-pointer group px-2 py-2 rounded-lg hover:bg-blue-50 transition-colors"
                        data-id="{{ $bidang->id }}">
                        <span
                            class="bg-blue-100 flex items-center justify-center rounded-lg w-9 h-9 shadow-sm transition-transform group-hover:scale-105 group-hover:bg-blue-500">
                            <i
                                class="fas fa-folder-open text-blue-600 group-hover:text-white transition-colors text-lg"></i>
                        </span>
                        <span
                            class="font-semibold text-gray-700 group-hover:text-blue-800 text-base tracking-tight">{{ $bidang->nama }}</span>
                    </li>
                    @endforeach
                </ul>
            </aside>

            {{-- Kegiatan --}}
            <section class="lg:col-span-2" id="kegiatanContainer">
                <div class="mt-6" id="subbidangWrapperKeg" style="display:none;">
                    <h4 class="text-sm font-semibold mb-2 text-gray-600">Subbidang</h4>
                    <div id="listSubbidangKeg" class="flex flex-wrap gap-2"></div>
                </div>
                <h3 class="font-bold text-2xl mb-6 text-center text-blue-700">Daftar Kegiatan</h3>
                <div id="listKegiatan" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                    $showingSearch = isset($query) && $query !== '';
                    @endphp
                    @if($showingSearch)
                    @if($kegiatan->count())
                    @foreach ($kegiatan as $item)
                    <div class="border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col">
                        @if ($item->fotokegiatan->count())
                        <img src="{{ asset('storage/' . $item->fotokegiatan[0]->path_foto) }}"
                            class="h-40 w-full object-cover" alt="Thumbnail Kegiatan">
                        @else
                        <div class="h-40 w-full bg-gray-200 flex items-center justify-center text-gray-400">
                            Tidak ada gambar
                        </div>
                        @endif
                        <div class="p-4 flex flex-col">
                            <h4 class="font-semibold text-base text-gray-800 mb-1">{{ $item->nama_kegiatan }}</h4>
                            <p class="text-sm text-gray-500 mb-1">
                                Waktu: {{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d M Y') }}
                            </p>
                            <a href="{{ route('kegiatan.detail', $item->id) }}"
                                class="mt-3 text-blue-600 text-sm hover:underline font-semibold">Lihat Detail</a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-500 col-span-full">Tidak ditemukan kegiatan dengan kata kunci "{{ $query }}".
                    </p>
                    @endif
                    @else
                    <p class="text-gray-500 col-span-full">Silakan pilih bidang untuk melihat kegiatan.</p>
                    @endif
                </div>
            </section>
        </div>
    </main>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sliderContainer = document.querySelector('.portal-kegiatan-slider');
    const slideCount = sliderContainer.querySelectorAll('.swiper-slide').length;
    if (slideCount > 0) {
        const swiper = new Swiper('.portal-kegiatan-slider', {
            // PERBAIKAN: Menggunakan efek 'fade' untuk transisi yang bersih
            effect: 'fade',
            fadeEffect: {
                crossFade: true // Membuat transisi fade lebih mulus
            },

            slidesPerView: 1,
            loop: slideCount > 1,
            autoplay: slideCount > 1 ? {
                delay: 5000,
                disableOnInteraction: false
            } : false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            }
        });
    }

    const listKegiatan = document.getElementById('listKegiatan');
    const bidangItems = document.querySelectorAll('.bidang-item-kegiatan');
    const listSubbidangKeg = document.getElementById('listSubbidangKeg');
    const subbidangWrapperKeg = document.getElementById('subbidangWrapperKeg');

    function renderKegiatan(data) {
        listKegiatan.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            listKegiatan.innerHTML = '<p class="text-gray-500 col-span-full">Tidak ada kegiatan ditemukan.</p>';
            return;
        }

        data.forEach(kegiatan => {
            const card = document.createElement('div');
            card.className =
                'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col';

            const thumbnail = kegiatan.fotokegiatan?.length ?
                `<img src="/storage/${kegiatan.fotokegiatan[0].path_foto}" class="h-40 w-full object-cover" alt="Thumbnail Kegiatan">` :
                `<div class="h-40 w-full bg-gray-200 flex items-center justify-center text-gray-400">Tidak ada gambar</div>`;

            card.innerHTML = `
                ${thumbnail}
                <div class="p-4 flex flex-col">
                    <h4 class="font-semibold text-base text-gray-800 mb-1">${kegiatan.nama_kegiatan}</h4>
                    <p class="text-sm text-gray-500 mb-1">Waktu: ${kegiatan.waktu ?? '-'}</p>
                    <a href="/kegiatan/detail/${kegiatan.id}" class="mt-3 text-blue-600 text-sm hover:underline font-semibold">Lihat Detail</a>
                </div>
            `;

            listKegiatan.appendChild(card);
        });
    }

    bidangItems.forEach(item => {
        item.addEventListener('click', function() {
            const bidangId = this.dataset.id;

            listKegiatan.innerHTML =
                '<p class="text-gray-500 col-span-full">Memuat kegiatan...</p>';
            listSubbidangKeg.innerHTML = '';
            subbidangWrapperKeg.style.display = 'none';

            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        subbidangWrapperKeg.style.display = 'block';
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className =
                                'px-4 py-2 bg-blue-100 hover:bg-blue-200 text-sm rounded shadow text-blue-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function() {
                                listKegiatan.innerHTML =
                                    '<p class="text-gray-500 col-span-full">Memuat kegiatan...</p>';
                                fetch(`/kegiatan/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => renderKegiatan(data))
                                    .catch(() => {
                                        listKegiatan.innerHTML =
                                            '<p class="text-red-500 col-span-full">Gagal memuat kegiatan subbidang.</p>';
                                    });
                            });

                            listSubbidangKeg.appendChild(btn);
                        });
                    }
                });

            fetch(`/kegiatan/bidang/${bidangId}`)
                .then(res => res.json())
                .then(data => renderKegiatan(data))
                .catch(() => {
                    listKegiatan.innerHTML =
                        '<p class="text-red-500 col-span-full">Gagal memuat kegiatan bidang.</p>';
                });
        });
    });
});
</script>
@endpush