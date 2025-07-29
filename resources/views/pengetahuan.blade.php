@extends('home.app')
@section('title', 'Pengetahuan Knowledge Management System')

@section('content')
<section class="relative pb-16">
    {{-- Background Biru Diperpanjang --}}
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>
    {{-- Konten Header (di atas background biru) --}}
    <div class="relative max-w-[1100px] mx-auto px-6 pt-7 pb-28">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-white text-3xl font-black tracking-tight leading-tight">Pengetahuan</h1>
                <p class="text-white/80 text-sm mt-1 max-w-xl">Jelajahi inovasi teknologi, data statistik terkini, dan program digitalisasi dari Dinas Komunikasi, Informatika, dan Statistik Provinsi Lampung.</p>
            </div>
            <!-- FORM PENCARIAN -->
            <form action="{{ route('artikel.search') }}" method="GET" id="searchForm" class="relative mt-4 md:mt-0 w-full max-w-xs">
                <input name="q" id="searchArtikel" type="text" placeholder="Cari Pengetahuan"
                    class="w-full bg-transparent placeholder-white/80 text-white border-b-2 border-white/50 py-2 pr-10 pl-2 outline-none focus:border-white transition-colors duration-300 text-base" autocomplete="off" />
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-white/80 hover:text-white" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content (Sidebar & Artikel disatukan dalam satu kartu putih) --}}
    <main class="relative -mt-24 max-w-[1100px] mx-auto bg-white rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Sidebar Bidang --}}
            <aside class="lg:col-span-1 h-fit">
                <h3 class="font-bold text-lg mb-6 text-gray-800 border-b pb-3">Bidang</h3>
                <ul class="flex flex-col gap-4" id="listBidang">
                    @foreach ($bidangs as $bidang)
                    <li class="bidang-item flex items-center gap-4 cursor-pointer group px-2 py-2 rounded-lg hover:bg-blue-50 transition-colors"
                        data-id="{{ $bidang->id }}">
                        <span class="bg-blue-100 flex items-center justify-center rounded-lg w-9 h-9 shadow-sm transition-transform group-hover:scale-105 group-hover:bg-blue-500">
                            {{-- Icon diubah agar bisa ganti warna --}}
                            <i class="fas fa-layer-group text-blue-600 group-hover:text-white transition-colors text-lg"></i>
                        </span>
                        <span class="font-semibold text-gray-700 group-hover:text-blue-800 text-base tracking-tight">
                            {{ $bidang->nama }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </aside>

            {{-- Artikel Section --}}
            <section class="lg:col-span-2" id="artikelContainer">
                {{-- Filter Buttons --}}
           
                <div id="subbidangWrapperKanan" class="mb-4 hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
                    <div id="listSubbidangKanan" class="flex flex-wrap gap-2"></div>
                </div>

                <div id="listArtikel" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Pesan default --}}
                    <p class="text-gray-500 col-span-full">Silakan pilih bidang atau cari untuk melihat artikel.</p>
                    {{-- Artikel akan dimuat di sini oleh JavaScript --}}
                </div>

                {{-- Tombol "Tampilkan lebih banyak" --}}
                <div class="mt-10 text-center">
                    <button class="bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-lg shadow hover:bg-blue-700 transition-all duration-200">
                        Tampilkan lebih banyak
                    </button>
                </div>
            </section>
        </div>
    </main>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const listArtikel = document.getElementById('listArtikel');
    const searchInput = document.getElementById('searchArtikel');
    const listBidang = document.querySelectorAll('.bidang-item');
    const listSubbidangKanan = document.getElementById('listSubbidangKanan');
    const subbidangWrapperKanan = document.getElementById('subbidangWrapperKanan');

    let searchTimeout = null;

    function renderArtikels(data) {
        listArtikel.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            listArtikel.innerHTML = '<p class="text-gray-500 col-span-full">Maaf, artikel tidak ditemukan.</p>';
            return;
        }

        data.forEach(artikel => {
            const card = document.createElement('div');
            card.className = 'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col';
            card.innerHTML = `
                <img src="/storage/${artikel.thumbnail}" class="w-full h-44 object-cover" alt="${artikel.judul}">
                <div class="p-4 flex flex-col flex-grow">
                    <h4 class="font-semibold text-base text-gray-800 mb-1">${artikel.judul}</h4>
                    <p class="text-sm text-gray-500 mb-2">Oleh: ${artikel.pengguna?.name || '-'}</p>
                    <p class="text-gray-700 text-sm flex-grow">${artikel.isi.substring(0, 100)}...</p>
                    <a href="/artikel/${artikel.slug}" class="mt-3 text-blue-600 text-sm hover:underline font-semibold">Baca Selengkapnya</a>
                </div>`;
            listArtikel.appendChild(card);
        });
    }

    // Event listener untuk pencarian live


    // Event listener untuk klik bidang
    listBidang.forEach(item => {
        item.addEventListener('click', function () {
            const bidangId = this.dataset.id;
            searchInput.value = '';

            listArtikel.innerHTML = '<p class="text-gray-500 col-span-full">Memuat artikel...</p>';
            listSubbidangKanan.innerHTML = '';
            subbidangWrapperKanan.classList.add('hidden');

            fetch(`/artikel/bidang/${bidangId}`)
                .then(res => res.json())
                .then(data => renderArtikels(data))
                .catch(() => {
                    listArtikel.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat artikel bidang.</p>';
                });

            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        subbidangWrapperKanan.classList.remove('hidden');
                        listSubbidangKanan.innerHTML = '';
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className = 'px-4 py-2 bg-blue-100 hover:bg-blue-200 text-sm rounded shadow text-blue-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function () {
                                listArtikel.innerHTML = '<p class="text-gray-500 col-span-full">Memuat artikel...</p>';
                                fetch(`/artikel/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => renderArtikels(data))
                                    .catch(() => {
                                        listArtikel.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat artikel subbidang.</p>';
                                    });
                            });

                            listSubbidangKanan.appendChild(btn);
                        });
                    } else {
                        subbidangWrapperKanan.classList.add('hidden');
                    }
                })
                .catch(() => {
                    subbidangWrapperKanan.classList.add('hidden');
                });
        });
    });

    // Tangani submit form search agar navigasi ke halaman pencarian dengan query
    const searchForm = document.getElementById('searchForm');
    searchForm.addEventListener('submit', function (e) {
        const query = searchInput.value.trim();
        if (query.length < 2) {
            e.preventDefault();
            alert('Masukkan minimal 2 huruf untuk mencari artikel.');
        }
        // Jika valid, submit form akan langsung navigasi ke /pengetahuan/search?q=...
    });
});
</script>
@endpush
