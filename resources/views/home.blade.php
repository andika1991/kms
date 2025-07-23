@extends('home.app')
@section('title', 'Knowledge Management System')

@section('content')

    {{-- HERO SECTION --}}
    <section class="relative py-20 bg-cover bg-center text-white"
        style="background-image: url('{{ asset('assets/img/Background-line-landing-page.png') }}');">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="max-w-[1200px] mx-auto w-full flex justify-between items-center relative z-10 px-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-2 drop-shadow-lg leading-tight">
                    Knowledge Management<br>System
                </h1>
                <p class="text-base md:text-lg font-semibold drop-shadow">
                    Dinas Komunikasi Informatika dan Statistik Provinsi Lampung
                </p>
            </div>
            <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" alt="Diskominfotik Lampung"
                class="h-32 md:h-40 drop-shadow-xl hidden md:block">
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
                <span class="bg-[#F49A24] flex items-center justify-center rounded-full w-10 h-10 shadow transition-transform group-hover:scale-110">
                    <i class="fas fa-layer-group text-white text-lg"></i>
                </span>
                <span class="font-medium text-base text-gray-700 group-hover:text-blue-700">
                    {{ $bidang->nama }}
                </span>
            </li>
            @endforeach
        </ul>

    
    </aside>
   <section class="lg:col-span-2 bg-white shadow-xl rounded-2xl p-6" id="artikelContainer">
    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-3">Artikel</h3>

    <!-- Subbidang Dropdown/Tab -->
    <div id="subbidangWrapperKanan" class="mb-4 hidden">
        <h4 class="font-semibold text-gray-700 mb-2">Subbidang</h4>
        <div id="listSubbidangKanan" class="flex flex-wrap gap-2"></div>
    </div>

    <!-- Artikel List -->
    <div id="listArtikel" class="grid grid-cols-1 gap-4">
        <p class="text-gray-500">Silakan pilih subbidang untuk melihat artikel.</p>
    </div>
</section>

@endsection


   

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
        const listBidang = document.querySelectorAll('.bidang-item');
        const listSubbidangKanan = document.getElementById('listSubbidangKanan');
        const subbidangWrapperKanan = document.getElementById('subbidangWrapperKanan');
        const listArtikel = document.getElementById('listArtikel');

        listBidang.forEach(item => {
           item.addEventListener('click', function () {
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
                listArtikel.innerHTML = '<p class="text-gray-500">Belum ada artikel pada bidang ini.</p>';
            } else {
                data.forEach(artikel => {
                    const card = document.createElement('div');
                    card.className = 'border rounded-lg p-4 shadow hover:shadow-md transition';
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
                    btn.className = 'px-4 py-2 bg-blue-100 hover:bg-blue-200 text-sm rounded shadow text-blue-700';
                    btn.textContent = sub.nama;
                    btn.dataset.id = sub.id;

                    btn.addEventListener('click', function () {
                        fetch(`/artikel/subbidang/${sub.id}`)
                            .then(res => res.json())
                            .then(data => {
                                listArtikel.innerHTML = '';
                                if (data.length === 0) {
                                    listArtikel.innerHTML = '<p class="text-gray-500">Belum ada artikel pada subbidang ini.</p>';
                                    return;
                                }

                                data.forEach(artikel => {
                                    const card = document.createElement('div');
                                    card.className = 'border rounded-lg p-4 shadow hover:shadow-md transition';
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