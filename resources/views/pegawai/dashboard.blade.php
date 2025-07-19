@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Dashboard Pegawai')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Selamat Datang di KMS Pegawai Diskominfotik Lampung</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari di Dashboard..."
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
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
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlah_kegiatan ?? 10 }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Kegiatan</div>
                    </div>
                    <i class="fa-solid fa-list-check text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlah_pengetahuan ?? 7 }}</div>
                        <div class="text-sm mt-1 opacity-90">Knowledge Shared</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlah_dokumen ?? 13 }}</div>
                        <div class="text-sm mt-1 opacity-90">Dokumen Dikelola</div>
                    </div>
                    <i class="fa-solid fa-folder-open text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-indigo-500 to-indigo-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlah_forum ?? 4 }}</div>
                        <div class="text-sm mt-1 opacity-90">Forum Diskusi</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl opacity-50"></i>
                </div>
            </div>

            {{-- CHARTS & DOKUMEN TERPOPULER --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 flex flex-col lg:flex-row gap-8">
                {{-- Chart Progress Kegiatan --}}
                <div class="lg:w-7/12 w-full">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Progress Pengelolaan Kegiatan</h3>
                    <div class="w-full h-60 flex items-center justify-center bg-gray-50 rounded-lg">
                        {{-- Chart dinamis, jika sudah tersedia --}}
                        <img src="{{ asset('assets/img/chart_dummy_pegawai.png') }}" class="h-44" alt="Chart Progress Kegiatan">
                    </div>
                </div>
                {{-- Dokumen Terpopuler --}}
                <div class="lg:w-5/12 w-full flex flex-col justify-center">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4 text-center lg:text-left">Dokumen Terpopuler</h3>
                    <ul class="space-y-3">
                        <li class="flex justify-between items-center text-sm text-gray-700 bg-[#f3f7fb] rounded-md px-3 py-2">
                            <span>Surat Keputusan</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">42 <i class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-gray-700 px-3 py-2">
                            <span>Berita Acara Rapat</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">35 <i class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-gray-700 bg-[#f3f7fb] rounded-md px-3 py-2">
                            <span>Laporan Tahunan</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">28 <i class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                        <li class="flex justify-between items-center text-sm text-gray-700 px-3 py-2">
                            <span>Template Administrasi</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-500">17 <i class="fa-solid fa-eye text-xs"></i></span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- GROUP: 3 Kolom Info + Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Info Pegawai --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-[#2171b8] mb-2">Info Kegiatan Pegawai</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-2">
                        Pantau aktivitas, kontribusi, dan update dokumen serta pengetahuan di lingkungan Diskominfotik. Semua proses terintegrasi dalam dashboard ini.
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Setiap perubahan dan upload terbaru tercatat otomatis untuk memudahkan kolaborasi tim.
                    </p>
                </div>
                {{-- Bar Chart Pengetahuan --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Statistik Knowledge Sharing</h3>
                    <div class="w-full h-40 flex items-center justify-center bg-gray-50 rounded-lg">
                        <img src="{{ asset('assets/img/chart_dummy_knowledge.png') }}" class="h-32" alt="Bar Chart Pengetahuan">
                    </div>
                </div>
                {{-- Bar Chart Forum --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col justify-between">
                    <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">Aktivitas Forum Diskusi</h3>
                    <div class="w-full h-40 flex items-center justify-center bg-gray-50 rounded-lg">
                        <img src="{{ asset('assets/img/chart_dummy_forum.png') }}" class="h-32" alt="Bar Chart Forum">
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>
</x-app-layout>
