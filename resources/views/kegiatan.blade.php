@extends('home.app')
@section('title', 'Kegiatan - Knowledge Management System')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
.portal-kegiatan-slider {
    position: relative;
}

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

#scrollBidangKegiatanMobile::-webkit-scrollbar {
    display: none;
}

@keyframes bounce-x {

    0%,
    100% {
        transform: translateX(0);
    }

    50% {
        transform: translateX(-6px);
    }
}

.animate-bounce-x {
    animation: bounce-x 1.2s infinite;
}
</style>
@endpush

@section('content')
<section class="relative pb-16">
    <div class="absolute top-0 left-0 right-0 h-96 w-full">
        <div class="h-full w-full bg-cover bg-center"
            style="background-image:url('{{ asset('assets/img/background_section_kegiatan.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-black/30"></div>
    </div>

    <div class="relative max-w-[1100px] mx-auto px-6 pt-7 pb-48">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-white text-3xl font-black tracking-tight leading-tight drop-shadow-lg">Kegiatan</h1>
                <p class="text-white/90 text-sm mt-1 max-w-xl drop-shadow">
                    Berbagai aktivitas atau program yang dilaksanakan oleh Dinas Komunikasi Informatika dan Statistik
                    Provinsi Lampung
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

        {{-- Swiper Slider --}}
        <div class="swiper-container portal-kegiatan-slider mb-10">
            <div class="swiper-wrapper">
                @forelse($kegiatan as $item)
                @php
                $thumb = optional($item->fotokegiatan->first())->path_foto ?? null;
                @endphp
                <div class="swiper-slide">
                    <a href="/kegiatan/detail/{{ $item->id }}"
                        class="block rounded-xl overflow-hidden relative group h-80">
                        <img src="{{ $thumb ? url('storage/'.$thumb) : asset('assets/img/default_kegiatan.jpg') }}"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/default_kegiatan.jpg') }}';"
                            alt="{{ $item->nama_kegiatan }}" loading="lazy"
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
            {{-- Mobile Bidang Horizontal --}}
            <div class="lg:hidden relative">
                <div class="overflow-x-auto -mx-4 px-4 pb-2" id="scrollBidangKegiatanMobile">
                    <ul class="flex gap-4 w-max pr-6" id="listBidangKegiatanMobile">
                        @foreach ($bidangs as $bidang)
                        <li class="bidang-item-kegiatan flex-shrink-0 flex items-center gap-2 cursor-pointer group px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors border border-gray-200 bg-white"
                            data-id="{{ $bidang->id }}">
                            <i class="fas fa-folder-open text-blue-600 group-hover:text-blue-800 text-lg"></i>
                            <span
                                class="text-sm font-medium text-gray-700 group-hover:text-blue-800">{{ $bidang->nama }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div
                    class="pointer-events-none absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-white to-transparent flex items-center justify-end pr-3">
                    <i class="fas fa-arrow-right animate-bounce-x text-gray-400 text-sm"></i>
                </div>
                <p class="text-xs text-gray-400 mt-2 px-1">← Geser ke samping untuk melihat semua bidang</p>
            </div>

            {{-- Desktop Bidang Vertical --}}
            <aside class="lg:col-span-1 h-fit hidden lg:block">
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
                    <p class="text-gray-500 col-span-full">Silakan pilih bidang untuk melihat kegiatan.</p>
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
    // Slider
    const sliderContainer = document.querySelector('.portal-kegiatan-slider');
    const slideCount = sliderContainer.querySelectorAll('.swiper-slide').length;
    if (slideCount > 0) {
        new Swiper('.portal-kegiatan-slider', {
            effect: 'fade',
            fadeEffect: {
                crossFade: true
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

    // Base URL & default image for safe thumbnail rendering
    const DEFAULT_THUMB = `{{ asset('assets/img/default_kegiatan.jpg') }}`;
    const STORAGE_BASE = `{{ url('storage') }}`;

    function potong(text, n) {
        const t = (text || '').replace(/<[^>]*>?/gm, '');
        return t.length > n ? t.slice(0, n) + '...' : t;
    }

    function tglId(iso) {
        if (!iso) return '-';
        const d = new Date(iso);
        return d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    function renderKegiatan(data) {
        listKegiatan.innerHTML = '';
        if (!Array.isArray(data) || data.length === 0) {
            listKegiatan.innerHTML = '<p class="text-gray-500 col-span-full">Tidak ada kegiatan ditemukan.</p>';
            return;
        }

        data.forEach(kegiatan => {
            const fotoPath = kegiatan?.fotokegiatan?. [0]?.path_foto;
            const imgSrc = fotoPath ? encodeURI(`${STORAGE_BASE}/${fotoPath}`) : DEFAULT_THUMB;

            const card = document.createElement('a');
            card.href = `/kegiatan/detail/${kegiatan.id}`;
            card.className =
                'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col group';

            card.innerHTML = `
                <img src="${imgSrc}" alt="Thumbnail Kegiatan"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='${DEFAULT_THUMB}';"
                     class="h-40 w-full object-cover group-hover:opacity-[.96] transition" />
                <div class="p-4 flex flex-col">
                    <h4 class="font-semibold text-base text-gray-800 mb-1 line-clamp-2">${kegiatan.nama_kegiatan || '-'}</h4>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">${potong(kegiatan.deskripsi_kegiatan, 110)}</p>
                    <p class="text-xs text-gray-500 mt-auto">Waktu: ${tglId(kegiatan.created_at)}</p>
                </div>`;
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

            // Subbidang
            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        subbidangWrapperKeg.style.display = 'block';
                        listSubbidangKeg.innerHTML = '';
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

            // Kegiatan by bidang
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