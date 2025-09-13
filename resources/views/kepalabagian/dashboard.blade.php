@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

$jumlahNotifikasi = 0;
if (Auth::check()) {
$jumlahNotifikasi = Notifikasi::where('pengguna_id', Auth::id())
->where('sudahdibaca', false)
->count();
}
@endphp

@section('title', 'Dashboard')

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff]">

        {{-- HEADER --}}
        <header class="border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
                            Selamat Datang di KMS Diskominfotik Lampung
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto">
                        {{-- Search --}}
                        <label class="relative flex-1 sm:flex-none sm:w-64">
                            <span class="sr-only">Cari</span>
                            <input type="text" placeholder="Cari..."
                                class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fa fa-search"></i>
                            </span>
                        </label>

                        {{-- Profile dropdown --}}
                        <div x-data="{ open:false }" class="relative">
                            <button @click="open=!open"
                                class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                                title="Profile">
                                <i class="fa-solid fa-user"></i>
                            </button>
                            <nav x-show="open" @click.outside="open=false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                                style="display:none;">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </nav>
                        </div>

                        {{-- Notifikasi --}}
                        <a href="{{ route('notifikasi.index') }}"
                            class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-blue-600 text-lg hover:shadow-md hover:border-blue-500 transition">
                            <i class="fa-solid fa-bell"></i>
                            @if($jumlahNotifikasi > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 leading-none">
                                {{ $jumlahNotifikasi }}
                            </span>
                            @endif
                        </a>
                    </div>
                </div>

                <p class="text-gray-700 text-sm font-medium mt-4">
                    Halo, selamat datang <b>{{ Auth::user()->name }}</b>!
                    Role Anda: <b>{{ Auth::user()->role->nama_role ?? '-' }}</b>
                </p>
            </div>
        </header>

        {{-- BODY --}}
        <main class="max-w-7xl mx-auto px-4 md:px-8 py-6 space-y-8">

            {{-- KARTU ANGKA RINGKAS --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahDokumen }}</div>
                        <div class="text-sm mt-1/2 opacity-90">Total Dokumen Masuk</div>
                    </div>
                    <i class="fa-solid fa-file-arrow-down text-4xl/none opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahArtikel }}</div>
                        <div class="text-sm mt-1/2 opacity-90">Total Artikel Dibagikan</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl/none opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-red-500 to-red-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahKegiatan }}</div>
                        <div class="text-sm mt-1/2 opacity-90">Total Kegiatan</div>
                    </div>
                    <i class="fa-solid fa-file-import text-4xl/none opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahForum }}</div>
                        <div class="text-sm mt-1/2 opacity-90">Forum Terdaftar</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl/none opacity-70"></i>
                </div>
            </section>

            {{-- GRAFIK & DOKUMEN TERATAS --}}
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Grafik Garis Perkembangan Dokumen & Artikel --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Perkembangan Dokumen & Artikel Tahun Ini</h3>
                    <div class="relative h-72 sm:h-80 md:h-96">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Dokumen Teratas --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Dokumen Teratas</h3>
                    <ul class="space-y-3">
                        @forelse($dokumenTeratas as $doc)
                        <li class="flex justify-between items-center text-sm text-gray-700
                        @class(['bg-[#f3f7fb] rounded-md px-3 py-2' => $loop->odd, 'px-3 py-2' => $loop->even])">
                            <span class="truncate max-w-[75%]">{{ $doc->nama_dokumen }}</span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-600">
                                {{ $doc->views_total }} <i class="fa-solid fa-eye text-xs"></i>
                            </span>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Belum ada dokumen.</li>
                        @endforelse
                    </ul>
                </div>
            </section>

            {{-- KONTEN BAWAH (KMS + RANKING + BAR CHARTS) --}}
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Kotak Keterangan KMS --}}
                <article class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-[#2171b8] text-lg mb-2">Knowledge Management System</h3>
                    <p class="text-sm text-gray-700 leading-relaxed mb-2">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi
                        informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi. Melalui
                        tampilan yang intuitif, dashboard ini memuat statistik real-time.
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Seperti total dokumen resmi yang tersimpan (1.238 dokumen), artikel pengetahuan yang
                        dipublikasikan (312 artikel), serta permintaan akses terbaru dari pengguna internal.
                        Setiap dokumen dikelompokkan berdasarkan kategori seperti Regulasi, Pedoman.
                    </p>
                </article>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Pengunggah Dokumen Teraktif</h3>
                    <ul class="space-y-3">
                        @forelse($rankDokumen as $rank)
                        <li class="flex justify-between items-center text-sm text-gray-700
                        @class(['bg-[#f3f7fb] rounded-md px-3 py-2' => $loop->odd, 'px-3 py-2' => $loop->even])">
                            <span class="truncate max-w-[75%] font-semibold">
                                {{ $loop->index + 1 }}. {{ $rank->user->name ?? 'Pengguna Tidak Dikenal' }}
                            </span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-600">
                                {{ $rank->total_dokumen }} Dokumen
                            </span>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Belum ada data peringkat.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Penulis Artikel Teraktif</h3>
                    <ul class="space-y-3">
                        @forelse($rankArtikel as $rank)
                        <li class="flex justify-between items-center text-sm text-gray-700
                        @class(['bg-[#f3f7fb] rounded-md px-3 py-2' => $loop->odd, 'px-3 py-2' => $loop->even])">
                            <span class="truncate max-w-[75%] font-semibold">
                                {{ $loop->index + 1 }}. {{ $rank->pengguna->name ?? 'Pengguna Tidak Dikenal' }}
                            </span>
                            <span class="font-semibold flex items-center gap-1.5 text-gray-600">
                                {{ $rank->total_artikel }} Artikel
                            </span>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Belum ada data peringkat.</li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Perkembangan Pengetahuan</h3>
                    <div class="relative h-56 sm:h-64 md:h-72">
                        <canvas id="barPengetahuan"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Perkembangan Dokumen</h3>
                    <div class="relative h-56 sm:h-64 md:h-72">
                        <canvas id="barDokumen"></canvas>
                    </div>
                </div>
            </section>
        </main>

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

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const getMonthName = (monthNumber) => {
            const date = new Date();
            date.setMonth(monthNumber - 1);
            return date.toLocaleString('id-ID', { month: 'long' });
        };

        /* ---------- LINE CHART: Dokumen & Artikel per Bulan ---------- */
        const labelsBulan = {!! json_encode($bulan) !!}.map(getMonthName);
        const dataDokumenLine = {!! json_encode($dataDokumen) !!};
        const dataArtikelLine = {!! json_encode($dataArtikel) !!};

        const lineChart = new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: labelsBulan,
                datasets: [
                    {
                        label: 'Dokumen',
                        data: dataDokumenLine,
                        borderColor: '#2b6cb0',
                        backgroundColor: 'rgba(43, 108, 176, 0.2)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Artikel',
                        data: dataArtikelLine,
                        borderColor: '#e53e3e',
                        backgroundColor: 'rgba(229, 62, 62, 0.2)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        /* ---------- BAR CHART: Per Subbidang ---------- */
        const labelsSub = {!! json_encode($subbidangNames->values()) !!};
        const barArtikelData = {!! json_encode($barArtikel->values()) !!};
        const barDokumenData = {!! json_encode($barDokumen->values()) !!};

        // Grafik Perkembangan Pengetahuan (Artikel)
        new Chart(document.getElementById('barPengetahuan').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labelsSub,
                datasets: [{
                    label: 'Jumlah Artikel',
                    data: barArtikelData,
                    backgroundColor: 'rgba(59,130,246,0.75)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // Grafik Perkembangan Dokumen
        new Chart(document.getElementById('barDokumen').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labelsSub,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: barDokumenData,
                    backgroundColor: 'rgba(239,68,68,0.75)',
                    borderColor: 'rgba(239,68,68,1)',
                    borderWidth: 1,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    </script>
</x-app-layout>