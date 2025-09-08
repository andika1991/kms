@extends('home.app')
@section('title', 'Dokumen Knowledge Management System')

@section('content')
<section class="relative pb-16">
    {{-- Background Biru Diperpanjang --}}
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>

    {{-- Konten Header --}}
    <div class="relative max-w-[1100px] mx-auto px-6 pt-7 pb-28">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-white text-3xl font-black tracking-tight leading-tight">Dokumen</h1>
                <p class="text-white/80 text-sm mt-1 max-w-xl">
                    Akses berbagai dokumen resmi, panduan, dan regulasi terkait komunikasi, informatika, dan statistik dari Pemerintah Provinsi Lampung.
                </p>
            </div>

            <!-- Form Pencarian -->
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

    {{-- Main Content --}}
    <main class="relative -mt-24 max-w-[1100px] mx-auto bg-white rounded-2xl shadow-xl p-4 sm:p-6 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Sidebar Bidang --}}
            <aside class="lg:col-span-1 h-fit">
                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-3 lg:block hidden">Bidang</h3>

                {{-- Mobile: Horizontal scroll bidang --}}
                <div class="lg:hidden relative">
                    <div class="overflow-x-auto -mx-4 px-4 pb-2" id="scrollBidangDokMobile">
                        <ul class="flex gap-4 w-max pr-6" id="listBidangDokMobile">
                            @foreach ($bidangs as $bidang)
                            <li class="bidang-item-dokumen flex-shrink-0 flex items-center gap-2 cursor-pointer group px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors border border-gray-200 bg-white"
                                data-id="{{ $bidang->id }}">
                                <i class="fas fa-folder text-blue-600 group-hover:text-blue-800 text-lg"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-800">
                                    {{ $bidang->nama }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Scroll indicator --}}
                    <div class="pointer-events-none absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-white to-transparent flex items-center justify-end pr-3">
                        <i class="fas fa-arrow-right animate-bounce-x text-gray-400 text-sm"></i>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 px-1">← Geser ke samping untuk melihat semua bidang</p>
                </div>

                {{-- Desktop: Vertical list bidang --}}
                <ul class="flex flex-col gap-4 hidden lg:flex" id="listBidangDok">
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
                <div id="subbidangWrapperDok" class="mb-4 hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
                    <div id="listSubbidangDok" class="flex flex-wrap gap-2"></div>
                </div>

                <div id="listDokumen" class="flex flex-col gap-6">
                    <p class="text-gray-500 text-center">Silakan pilih bidang untuk melihat dokumen.</p>
                </div>

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

@push('styles')
<style>
    #listBidangDokMobile::-webkit-scrollbar {
        display: none;
    }
    @keyframes bounce-x {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(-6px); }
    }
    .animate-bounce-x { animation: bounce-x 1.2s infinite; }
</style>
@endpush

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
            const kategoriNama =
                dokumen?.kategori_dokumen?.nama_kategoridokumen ||
                dokumen?.kategoriDokumen?.nama_kategoridokumen ||
                dokumen?.kategori_nama || '-';

            const tgl = dokumen?.created_at
                ? new Date(dokumen.created_at).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
                : '';

            const views = typeof dokumen?.views_count !== 'undefined'
                ? dokumen.views_count
                : (Array.isArray(dokumen?.views) ? dokumen.views.length : 0);

            const showHref = `/dokumen/${dokumen.id}`;

            const card = document.createElement('a');
            card.href = showHref;
            card.className = 'border rounded-2xl overflow-hidden shadow hover:shadow-lg transition p-4 block group focus:outline-none focus:ring-2 focus:ring-blue-500';
            card.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="mt-1 shrink-0 h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file text-blue-600"></i>
                    </div>
                    <div class="min-w-0 w-full">
                        <h4 class="font-semibold text-base text-gray-900 mb-0.5 group-hover:text-blue-700 line-clamp-1">
                            ${dokumen?.nama_dokumen ?? '-'}
                        </h4>
                        <p class="text-sm text-gray-500 mb-1">Oleh: ${dokumen?.user?.name || '-'}</p>
                        <p class="text-gray-700 text-sm">
                            ${((dokumen?.deskripsi || '').replace(/<[^>]*>?/gm, '').slice(0, 160))}${(dokumen?.deskripsi || '').length > 160 ? '...' : ''}
                        </p>

                        <!-- Kategori + Tanggal di kiri, Views di kanan -->
                        <div class="mt-3 flex items-end gap-3">
                            <div class="flex flex-col gap-1">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    ${kategoriNama}
                                </span>
                                <span class="text-xs text-gray-500">${tgl}</span>
                            </div>
                            <span class="ml-auto inline-flex items-center gap-1 text-xs text-gray-500" title="Dilihat">
                                <i class="fas fa-eye"></i> ${views}
                            </span>
                        </div>
                    </div>
                </div>
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
                    if (Array.isArray(data) && data.length > 0) {
                        subbidangWrapperDok.classList.remove('hidden');
                        listSubbidangDok.innerHTML = '';
                        data.forEach(sub => {
                            const btn = document.createElement('button');
                            btn.className = 'px-4 py-2 bg-green-100 hover:bg-green-200 text-sm rounded shadow text-green-700';
                            btn.textContent = sub.nama;
                            btn.dataset.id = sub.id;

                            btn.addEventListener('click', function() {
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
