@extends('home.app')
@section('title', 'Dokumen Knowledge Management System')

@section('content')
<section class="relative pb-16">
    {{-- Background Biru Diperpanjang --}}
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>
    {{-- Konten Header (di atas background biru) --}}
    <div class="relative max-w-[1100px] mx-auto px-6 pt-7 pb-28">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-white text-3xl font-black tracking-tight leading-tight">Dokumen</h1>
                <p class="text-white/80 text-sm mt-1 max-w-xl">
                    Akses berbagai dokumen resmi, panduan, dan regulasi terkait komunikasi, informatika, dan statistik dari Pemerintah Provinsi Lampung. Temukan informasi dengan mudah dan cepat.
                </p>
            </div>
            <!-- Form pencarian dokumen -->
            <form action="{{ route('dokumen.search') }}" method="GET" id="searchDokumenForm" class="relative mt-4 md:mt-0 w-full max-w-xs">
                <input name="q" id="searchDokumenInput" type="text" placeholder="Cari Dokumen..."
                    class="w-full bg-transparent placeholder-white/80 text-white border-b-2 border-white/50 py-2 pr-10 pl-2 outline-none focus:border-white transition-colors duration-300 text-base"
                    autocomplete="off" />
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-white/80 hover:text-white" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content (Sidebar & Dokumen disatukan dalam satu kartu putih) --}}
    <main class="relative -mt-24 max-w-[1100px] mx-auto bg-white rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Sidebar Bidang --}}
            <aside class="lg:col-span-1 h-fit">
                <h3 class="font-bold text-lg mb-6 text-gray-800 border-b pb-3">Bidang</h3>
                <ul class="flex flex-col gap-4" id="listBidangDok">
                    @foreach ($bidangs as $bidang)
                    <li class="bidang-item-dokumen flex items-center gap-4 cursor-pointer group px-2 py-2 rounded-lg hover:bg-blue-50 transition-colors"
                        data-id="{{ $bidang->id }}">
                        <span class="bg-blue-100 flex items-center justify-center rounded-lg w-9 h-9 shadow-sm transition-transform group-hover:scale-105 group-hover:bg-blue-500">
                            <i class="fas fa-folder text-blue-600 group-hover:text-white transition-colors text-lg"></i>
                        </span>
                        <span class="font-semibold text-gray-700 group-hover:text-blue-800 text-base tracking-tight">
                            {{ $bidang->nama }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </aside>

            {{-- Dokumen Section --}}
            <section class="lg:col-span-2" id="dokumenContainer">
                {{-- Filter Buttons --}}
        
                <div id="subbidangWrapperDok" class="mb-4 hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
                    <div id="listSubbidangDok" class="flex flex-wrap gap-2"></div>
                </div>

                <div id="listDokumen" class="flex flex-col gap-6">
                    {{-- Pesan default --}}
                    <p class="text-gray-500 text-center">Silakan pilih bidang untuk melihat dokumen.</p>
                    {{-- Dokumen akan dimuat di sini oleh JavaScript --}}
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
document.addEventListener('DOMContentLoaded', function() {
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
            card.className =
                'border rounded-xl overflow-hidden shadow hover:shadow-lg transition flex flex-col p-4';

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
        item.addEventListener('click', function() {
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
                            btn.className =
                                'px-4 py-2 bg-green-100 hover:bg-green-200 text-sm rounded shadow text-green-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function() {
                                listDokumen.innerHTML =
                                    '<p class="text-gray-500 col-span-full">Memuat dokumen...</p>';
                                fetch(`/dokumen/subbidang/${sub.id}`)
                                    .then(res => res.json())
                                    .then(data => renderDokumen(data))
                                    .catch(() => {
                                        listDokumen.innerHTML =
                                            '<p class="text-red-500 col-span-full">Gagal memuat dokumen subbidang.</p>';
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
                    listDokumen.innerHTML =
                        '<p class="text-red-500 col-span-full">Gagal memuat dokumen bidang.</p>';
                });
        });
    });
});
</script>
@endpush