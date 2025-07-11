@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    {{-- Sidebar & Main --}}
    <div class="flex min-h-screen bg-[#f6faff]">
        @include('layouts.navigation-kepalabagian')
        <main class="flex-1 ml-60 p-6">
            {{-- Header Bar --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h2 class="font-bold text-xl md:text-2xl text-gray-800 leading-tight mb-1">
                        Selamat Datang di KMS Diskominfotik Lampung
                    </h2>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-5">
                        <span class="text-gray-500 text-sm">{{ $tanggal }}</span>
                        <span class="text-gray-700 text-sm font-medium">
                            | Halo, selamat datang <b>{{ Auth::user()->name }}</b>!
                            Role Anda: <b>{{ Auth::user()->role->nama_role ?? '-' }}</b>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <div class="relative w-48">
                        <input type="text" placeholder="Cari"
                            class="w-full rounded-full pl-10 pr-4 py-2 border border-gray-300 focus:outline-none focus:ring focus:ring-blue-100 bg-gray-50 text-sm" />
                        <span class="absolute left-3 top-2.5 text-gray-400">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="8" cy="8" r="7" />
                                <path d="M16 16l-3-3" />
                            </svg>
                        </span>
                    </div>
                    <div class="bg-gray-100 rounded-full p-2">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="10" cy="7" r="4" />
                            <path d="M16 17c0-2.5-3-4-6-4s-6 1.5-6 4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- ISI DASHBOARD KAMU DISINI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="flex items-center p-5 rounded-xl shadow bg-green-600 text-white">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1422</div>
                        <div class="text-xs mt-1">Total Dokumen Masuk</div>
                    </div>
                    <svg class="w-8 h-8 opacity-60 ml-3" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2" fill="white" />
                        <path stroke="green" d="M8 8h8M8 12h8M8 16h6" />
                    </svg>
                </div>
                <div class="flex items-center p-5 rounded-xl shadow bg-blue-600 text-white">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1234</div>
                        <div class="text-xs mt-1">Total Artikel Dibagikan</div>
                    </div>
                    <svg class="w-8 h-8 opacity-60 ml-3" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2" fill="white" />
                        <path stroke="blue" d="M8 8h8M8 12h8M8 16h6" />
                    </svg>
                </div>
                <div class="flex items-center p-5 rounded-xl shadow bg-red-600 text-white">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1422</div>
                        <div class="text-xs mt-1">Total Dokumen Masuk</div>
                    </div>
                    <svg class="w-8 h-8 opacity-60 ml-3" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2" fill="white" />
                        <path stroke="red" d="M8 8h8M8 12h8M8 16h6" />
                    </svg>
                </div>
                <div class="flex items-center p-5 rounded-xl shadow bg-yellow-500 text-white">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">1234</div>
                        <div class="text-xs mt-1">Total Artikel Dibagikan</div>
                    </div>
                    <svg class="w-8 h-8 opacity-60 ml-3" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="4" y="4" width="16" height="16" rx="2" fill="white" />
                        <path stroke="orange" d="M8 8h8M8 12h8M8 16h6" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Chart and KMS Description --}}
                <div class="flex-1 flex flex-col gap-6">
                    <div class="bg-white rounded-xl shadow p-5">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-base">Perbandingan Dokumen</h3>
                        </div>
                        <div class="w-full h-48 flex items-center justify-center bg-gray-100 rounded">
                            <span class="text-gray-400 text-xs">[Grafik/Chart]</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <h3 class="font-semibold text-base mb-3">Knowledge Management System</h3>
                        <p class="text-sm text-gray-700 mb-4">
                            Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi
                            informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi.
                            Melalui tampilan yang intuitif, dashboard ini memuat statistik real-time...
                        </p>
                        <p class="text-xs text-gray-500">
                            Dokumen dikelompokkan berdasarkan kategori seperti total dokumen resmi yang tersimpan (1.238 dokumen), artikel pengetahuan yang dipublikasikan (312 artikel), serta permintaan akses terbaru dari pengguna internal. Setiap dokumen dikelompokkan berdasarkan kategori seperti Regulasi, Pedoman, dsb.
                        </p>
                    </div>
                </div>
                <div class="w-full lg:max-w-xs flex flex-col gap-6">
                    <div class="bg-white rounded-xl shadow p-5">
                        <h3 class="font-semibold text-base mb-3">Dokumen Teratas</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between items-center text-sm">
                                <span>Renja Diskominfotik 2025</span>
                                <span class="flex items-center gap-1">56
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="8" cy="8" r="7" />
                                        <path d="M8 5v4l3 2" />
                                    </svg>
                                </span>
                            </li>
                            <li class="flex justify-between items-center text-sm">
                                <span>LKJ Diskominfotik 2025</span>
                                <span class="flex items-center gap-1">23
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="8" cy="8" r="7" />
                                        <path d="M8 5v4l3 2" />
                                    </svg>
                                </span>
                            </li>
                            <li class="flex justify-between items-center text-sm">
                                <span>Renstra Diskominfotik 2025</span>
                                <span class="flex items-center gap-1">19
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="8" cy="8" r="7" />
                                        <path d="M8 5v4l3 2" />
                                    </svg>
                                </span>
                            </li>
                            <li class="flex justify-between items-center text-sm">
                                <span>Rencana Aksi Diskominfotik 2025</span>
                                <span class="flex items-center gap-1">12
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="8" cy="8" r="7" />
                                        <path d="M8 5v4l3 2" />
                                    </svg>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white rounded-xl shadow p-5">
                        <h3 class="font-semibold text-base mb-3">Perkembangan Pengetahuan</h3>
                        <div class="w-full h-28 flex items-center justify-center bg-gray-100 rounded mb-4">
                            <span class="text-gray-400 text-xs">[Bar Chart Pengetahuan]</span>
                        </div>
                        <h3 class="font-semibold text-base mb-3">Perkembangan Artikel</h3>
                        <div class="w-full h-28 flex items-center justify-center bg-gray-100 rounded">
                            <span class="text-gray-400 text-xs">[Bar Chart Artikel]</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
