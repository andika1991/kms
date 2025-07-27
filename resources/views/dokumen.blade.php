@extends('home.app')
@section('title', 'Dokumen Knowledge Management System')

@section('content')
<section class="py-6">
    <div class="max-w-[1100px] mx-auto px-6">
        <div class="bg-[#2b6cb0] shadow-lg rounded-lg flex flex-col md:flex-row items-center justify-between py-2 px-4">
            <h1 class="text-white text-lg font-bold py-2 px-4">Dokumen</h1>

           <!-- Form pencarian dokumen -->
<form action="{{ route('dokumen.search') }}" method="GET" class="relative w-full md:w-1/2 mx-auto mb-4" id="searchDokumenForm">
    <input name="q" id="searchDokumenInput" type="text" placeholder="Cari Dokumen..."
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
        <ul class="flex flex-col gap-5" id="listBidangDok">
            @foreach ($bidangs as $bidang)
            <li class="bidang-item-dokumen flex items-center gap-4 cursor-pointer group p-2 rounded-lg hover:bg-gray-100 transition-colors"
                data-id="{{ $bidang->id }}">
                <span class="bg-green-600 flex items-center justify-center rounded-full w-10 h-10 shadow">
                    <i class="fas fa-folder text-white text-lg"></i>
                </span>
                <span class="font-medium text-base text-gray-700 group-hover:text-green-700">
                    {{ $bidang->nama }}
                </span>
            </li>
            @endforeach
        </ul>
    </aside>

    {{-- Dokumen Section --}}
    <section class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-6" id="dokumenContainer">
        <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-3">Dokumen</h3>

        <div id="subbidangWrapperDok" class="mb-4 hidden">
            <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
            <div id="listSubbidangDok" class="flex flex-wrap gap-2"></div>
        </div>

        <div id="listDokumen" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <p class="text-gray-500 col-span-full">Silakan pilih bidang untuk melihat dokumen.</p>
        </div>
        <!-- Container untuk hasil pencarian dokumen -->
<div id="hasilDokumen" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
    {{-- Hasil pencarian akan dimuat di sini via AJAX --}}
</div>

    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const listDokumen = document.getElementById('listDokumen');
    const listBidangDok = document.querySelectorAll('.bidang-item-dokumen');
    const listSubbidangDok = document.getElementById('listSubbidangDok');
    const subbidangWrapperDok = document.getElementById('subbidangWrapperDok');

    function renderDokumen(data) {
        listDokumen.innerHTML = '';

        if (!Array.isArray(data) || data.length === 0) {
            listDokumen.innerHTML = '<p class="text-gray-500 col-span-full">Tidak ada dokumen ditemukan.</p>';
            return;
        }

        data.forEach(dokumen => {
            const card = document.createElement('div');
            card.className = 'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col p-4';

            card.innerHTML = `
                <h4 class="font-semibold text-base text-gray-800 mb-1">${dokumen.nama_dokumen}</h4>
                <p class="text-sm text-gray-500 mb-2">Oleh: ${dokumen.user?.name || '-'}</p>
                <p class="text-gray-700 text-sm flex-grow">${dokumen.deskripsi?.substring(0, 100) || ''}...</p>
                <a href="/storage/${dokumen.path_dokumen}" target="_blank" class="mt-3 text-blue-600 text-sm hover:underline font-semibold">Lihat Dokumen</a>
            `;
            listDokumen.appendChild(card);
        });
    }

    listBidangDok.forEach(item => {
        item.addEventListener('click', function () {
            const bidangId = this.dataset.id;

            listDokumen.innerHTML = '<p class="text-gray-500 col-span-full">Memuat dokumen...</p>';
            listSubbidangDok.innerHTML = '';
            subbidangWrapperDok.classList.add('hidden');

            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        subbidangWrapperDok.classList.remove('hidden');
                        listSubbidangDok.innerHTML = '';
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className = 'px-4 py-2 bg-green-100 hover:bg-green-200 text-sm rounded shadow text-green-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function () {
                                listDokumen.innerHTML = '<p class="text-gray-500 col-span-full">Memuat dokumen...</p>';
                                fetch(`/dokumen/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => renderDokumen(data))
                                    .catch(() => {
                                        listDokumen.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat dokumen subbidang.</p>';
                                    });
                            });

                            listSubbidangDok.appendChild(btn);
                        });
                    }
                });

            // Fetch dokumen by bidang
            fetch(`/dokumen/bidang/${bidangId}`)
                .then(res => res.json())
                .then(data => renderDokumen(data))
                .catch(() => {
                    listDokumen.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat dokumen bidang.</p>';
                });
        });
    });
});


</script>
@endpush
