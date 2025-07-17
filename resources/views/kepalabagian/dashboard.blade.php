@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Dashboard')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER KONTEN --}}
        <div class="p-6 md:p-8 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Selamat Datang di KMS Diskominfotik Lampung
                    </h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-gray-700 text-sm font-medium mt-4">
                Halo, selamat datang <b>{{ Auth::user()->name }}</b>!
                Role Anda: <b>{{ Auth::user()->role->nama_role ?? '-' }}</b>
            </div>
        </div>

        {{-- BODY KONTEN --}}
        <div class="p-6 md:p-8">
            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Card 1 --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1422</div>
                        <div class="text-sm mt-1 opacity-90">Total Dokumen Masuk</div>
                    </div>
                    <i class="fa-solid fa-file-arrow-down text-4xl opacity-50"></i>
                </div>
                {{-- Card 2 --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1234</div>
                        <div class="text-sm mt-1 opacity-90">Total Artikel Dibagikan</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl opacity-50"></i>
                </div>
                {{-- Card 3 --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-red-500 to-red-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1422</div>
                        <div class="text-sm mt-1 opacity-90">Total Dokumen Masuk</div>
                    </div>
                    <i class="fa-solid fa-file-import text-4xl opacity-50"></i>
                </div>
                {{-- Card 4 --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1234</div>
                        <div class="text-sm mt-1 opacity-90">Total Artikel Dibagikan</div>
                    </div>
                    <i class="fa-solid fa-paper-plane text-4xl opacity-50"></i>
                </div>
            </div>

            {{-- GROUP: Perbandingan Dokumen & Dokumen Teratas --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 flex flex-col lg:flex-row gap-8">
                {{-- Chart Area --}}
                <div class="lg:w-7/12 w-full">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Perbandingan Dokumen</h3>
                    <div class="w-full h-60 flex items-center justify-center bg-gray-50 rounded-lg">
                        <span class="text-gray-400 text-sm">[Grafik/Chart Area]</span>
                    </div>
                </div>
                {{-- Dokumen Teratas --}}
                <div class="lg:w-5/12 w-full flex flex-col justify-center">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4 text-center lg:text-left">Dokumen
                        Teratas</h3>
                    <ul class="space-y-3">
                        <li
                            class="flex justify-between items-center text-sm text-gray-700 bg-[#f3f7fb] rounded-md px-3 py-2">
                            <span>Renja Diskominfotik 2025</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">56 <i
                                    class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-gray-700 px-3 py-2">
                            <span>LKJ Diskominfotik 2025</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">23 <i
                                    class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li
                            class="flex justify-between items-center text-sm text-gray-700 bg-[#f3f7fb] rounded-md px-3 py-2">
                            <span>Renstra Diskominfotik 2025</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">19 <i
                                    class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-gray-700 px-3 py-2">
                            <span>Rencana Aksi Diskominfotik 2025</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">12 <i
                                    class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li
                            class="flex justify-between items-center text-sm text-gray-700 bg-[#f3f7fb] rounded-md px-3 py-2">
                            <span>Renstra Diskominfotik 2025</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">19 <i
                                    class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- GROUP: 3 Kolom KMS + Chart --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- KMS --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-[#2171b8] mb-2">Knowledge Management System</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-2">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi
                        informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi. Melalui
                        tampilan yang intuitif, dashboard ini memuat statistik real-time tampilan yang intuitif,
                        dashboard ini memuat statistik real-time.
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Seperti total dokumen resmi yang tersimpan (1.238 dokumen), artikel pengetahuan yang
                        dipublikasikan (312 artikel), serta permintaan akses terbaru dari pengguna internal. Setiap
                        dokumen dikelompokkan berdasarkan kategori seperti Regulasi, Pedoman.
                    </p>
                </div>
                {{-- Chart 1 --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Perkembangan Pengetahuan</h3>
                    <div class="w-full h-40 flex items-center justify-center bg-gray-50 rounded-lg">
                        <span class="text-gray-400 text-sm">[Bar Chart]</span>
                    </div>
                </div>
                {{-- Chart 2 --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Perkembangan Artikel</h3>
                    <div class="w-full h-40 flex items-center justify-center bg-gray-50 rounded-lg">
                        <span class="text-gray-400 text-sm">[Bar Chart]</span>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
</x-app-layout>