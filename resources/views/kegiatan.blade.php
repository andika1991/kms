@extends('home.app')
@section('title', 'Kegiatan - Knowledge Management System')

@section('content')
<section class="py-6">
    <div class="max-w-[1100px] mx-auto px-6">
        <div class="bg-[#2b6cb0] shadow-lg rounded-lg flex flex-col md:flex-row items-center justify-between py-2 px-4">
            <h1 class="text-white text-lg font-bold py-2 px-4">Daftar Kegiatan</h1>

            <!-- Form pencarian kegiatan -->
            <form action="{{ route('kegiatan') }}" method="GET" class="relative w-full md:w-1/2 mx-auto mb-4">
                <input name="q" type="text" placeholder="Cari Kegiatan..."
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
        <ul class="flex flex-col gap-5" id="listBidangKegiatan">
            @foreach ($bidangs as $bidang)
            <li class="bidang-item-kegiatan flex items-center gap-4 cursor-pointer group p-2 rounded-lg hover:bg-gray-100 transition-colors"
                data-id="{{ $bidang->id }}">
                <span class="bg-blue-600 flex items-center justify-center rounded-full w-10 h-10 shadow">
                    <i class="fas fa-folder-open text-white text-lg"></i>
                </span>
                <span class="font-medium text-base text-gray-700 group-hover:text-blue-700">
                    {{ $bidang->nama }}
                </span>
            </li>
            @endforeach
        </ul>
    </aside>

    {{-- Kegiatan Section --}}
    <section class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-6" id="kegiatanContainer">
        <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-3">Kegiatan</h3>

        <div id="subbidangWrapperKeg" class="mb-4 hidden">
            <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
            <div id="listSubbidangKeg" class="flex flex-wrap gap-2"></div>
        </div>

        <div id="listKegiatan" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <p class="text-gray-500 col-span-full">Silakan pilih bidang untuk melihat kegiatan.</p>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const listKegiatan = document.getElementById('listKegiatan');
    const listBidangKegiatan = document.querySelectorAll('.bidang-item-kegiatan');
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
        card.className = 'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col';

        const thumbnail = kegiatan.fotokegiatan?.length
            ? `<img src="/storage/${kegiatan.fotokegiatan[0].path_foto}" class="h-40 w-full object-cover" alt="Thumbnail Kegiatan">`
            : `<div class="h-40 w-full bg-gray-200 flex items-center justify-center text-gray-400">Tidak ada gambar</div>`;

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

    listBidangKegiatan.forEach(item => {
        item.addEventListener('click', function () {
            const bidangId = this.dataset.id;

            listKegiatan.innerHTML = '<p class="text-gray-500 col-span-full">Memuat kegiatan...</p>';
            listSubbidangKeg.innerHTML = '';
            subbidangWrapperKeg.classList.add('hidden');

            fetch(`/subbidang/${bidangId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        subbidangWrapperKeg.classList.remove('hidden');
                        listSubbidangKeg.innerHTML = '';
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className = 'px-4 py-2 bg-blue-100 hover:bg-blue-200 text-sm rounded shadow text-blue-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function () {
                                listKegiatan.innerHTML = '<p class="text-gray-500 col-span-full">Memuat kegiatan...</p>';
                                fetch(`/kegiatan/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => renderKegiatan(data))
                                    .catch(() => {
                                        listKegiatan.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat kegiatan subbidang.</p>';
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
                    listKegiatan.innerHTML = '<p class="text-red-500 col-span-full">Gagal memuat kegiatan bidang.</p>';
                });
        });
    });
});
</script>
@endpush
