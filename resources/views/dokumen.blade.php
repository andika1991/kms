@extends('home.app')
@section('title', 'Dokumen Knowledge Management System')

@section('content')
    {{-- TITLE & SEARCH BAR SECTION --}}
    <section class="py-6">
        <div class="max-w-[1100px] mx-auto px-6">
            <div
                class="bg-[#2b6cb0] shadow-lg rounded-lg flex flex-col md:flex-row items-center justify-between py-2 px-4">
                <h1 class="text-white text-lg font-bold py-2 px-4">Dokumen</h1>
                <div class="relative w-full md:w-auto">
                    <input type="text" placeholder="Cari Dokumen"
                        class="bg-transparent placeholder-white text-white border-b-2 border-white py-2 pl-2 pr-8 outline-none focus:border-white transition">
                    <button
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-200 transition">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 px-6 py-10">

        {{-- Sidebar Bidang dengan Ikon Berbeda --}}
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

        {{-- Daftar Dokumen --}}
        <section class="lg:col-span-3">
            <div class="space-y-6">
                @php
                // Data contoh untuk 3 item dokumen
                $dokumen = [
                ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                ['img' => 'assets/img/bagan_struktur_pengetahuan.png', 'title' => 'Struktur Instansi'],
                ];
                @endphp
                @foreach ($dokumen as $item)
                <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center gap-6">
                    {{-- Gambar Dokumen --}}
                    <div class="w-1/3">
                        <img src="{{ asset($item['img']) }}" alt="{{ $item['title'] }}"
                            class="w-full h-auto object-cover rounded-lg border border-gray-200">
                    </div>
                    {{-- Detail Dokumen --}}
                    <div class="w-2/3">
                        <h3 class="text-lg font-bold text-gray-800">{{ $item['title'] }}</h3>
                        <ol class="list-decimal list-inside text-sm text-gray-600 mt-2 space-y-1">
                            <li>Berdasarkan Peraturan Gubernur Nomor 59 Tahun 2021 tentang Susunan Organisasi, Tugas dan
                                Fungsi Serta Tatakerja Perangkat Daerah Pemerintah Provinsi Lampung</li>
                            <li>a. Kepala Dinas;</li>
                            <li>b. Sekretariat;</li>
                            <li>c. Bidang Pengelolaan dan Layanan Informasi Publik...</li>
                        </ol>
                        <a href="#" class="inline-block text-blue-700 text-sm font-semibold mt-4 hover:underline">
                            Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <nav class="flex items-center justify-center gap-2 mt-10">
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i
                        class="fas fa-chevron-left"></i></a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-700 text-white font-bold">1</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">2</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">3</a>
                <span class="text-gray-500">...</span>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-700 hover:bg-gray-200">20</a>
                <a href="#"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-200"><i
                        class="fas fa-chevron-right"></i></a>
            </nav>
        </section>
        @endsection

@push('scripts')
<script>

</script>
@endpush