@extends('home.app')
@section('title', 'Pengetahuan Knowledge Management System')

@section('content')
<section class="py-6">
    <div class="max-w-[1100px] mx-auto px-6">
        <div class="bg-[#2b6cb0] shadow-lg rounded-lg flex flex-col md:flex-row items-center justify-between py-2 px-4">
            <h1 class="text-white text-lg font-bold py-2 px-4">Pengetahuan</h1>

            <!-- FORM PENCARIAN -->
            <form action="{{ route('artikel.search') }}" method="GET" class="relative w-full md:w-1/2 mx-auto mb-4" id="searchForm">
                <input name="q" id="searchArtikel" type="text" placeholder="Cari Pengetahuan"
                    class="w-full bg-transparent placeholder-white text-white border-b-2 border-white py-2 pl-2 pr-10 outline-none focus:border-white transition" autocomplete="off" />

                <button type="submit"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-200 transition" aria-label="Search">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</section>

<main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 px-6 py-10">
    {{-- Sidebar Bidang --}}
    <aside class="lg:col-span-1 bg-white shadow-xl rounded-2xl p-6 h-fit">
        <h3 class="font-bold text-lg mb-6 text-gray-800 border-b pb-3">Bidang</h3>
        <ul class="flex flex-col gap-5" id="listBidang">
            @foreach ($bidangs as $bidang)
            <li class="bidang-item flex items-center gap-4 cursor-pointer group p-2 rounded-lg hover:bg-gray-100 transition-colors"
                data-id="{{ $bidang->id }}">
                <span
                    class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow transition-transform group-hover:scale-110">
                    <i class="fas fa-layer-group text-white text-lg"></i>
                </span>
                <span class="font-medium text-base text-gray-700 group-hover:text-blue-700">
                    {{ $bidang->nama }}
                </span>
            </li>
            @endforeach
        </ul>
    </aside>

    {{-- Artikel Section --}}
    <section class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-6" id="artikelContainer">
        <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-3">Artikel</h3>

        <div id="subbidangWrapperKanan" class="mb-4 hidden">
            <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
            <div id="listSubbidangKanan" class="flex flex-wrap gap-2"></div>
        </div>

        <div id="listArtikel" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <p class="text-gray-500 col-span-full">Silakan pilih bidang atau cari untuk melihat artikel.</p>
        </div>
    </section>
</main>
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
