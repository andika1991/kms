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

                        {{-- Profile dropdown (tetap) --}}
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

                        {{-- Notifikasi (jangan diubah) --}}
                        <a href="{{ route('notifikasi.index') }}"
                            class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-blue-600 text-lg hover:shadow-md hover:border-blue-500 transition"
                            aria-label="Notifikasi">
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
                    Role Anda: <b>Kepala {{ Auth::user()->role->nama_role ?? '-' }}</b>
                </p>
            </div>
        </header>

        {{-- BODY --}}
        <main class="max-w-7xl mx-auto px-4 md:px-8 py-6 space-y-8">

            {{-- KARTU RINGKAS --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahDokumen }}</div>
                        <div class="text-sm mt-0.5 opacity-90">Total Dokumen Masuk</div>
                    </div>
                    <i class="fa-solid fa-file-arrow-down text-4xl opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahArtikel }}</div>
                        <div class="text-sm mt-0.5 opacity-90">Total Artikel Dibagikan</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-red-500 to-red-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahForum }}</div>
                        <div class="text-sm mt-0.5 opacity-90">Total Forum Diikuti</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl opacity-70"></i>
                </div>

                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahKegiatan }}</div>
                        <div class="text-sm mt-0.5 opacity-90">Total Kegiatan</div>
                    </div>
                    <i class="fa-solid fa-calendar-check text-4xl opacity-70"></i>
                </div>
            </section>

            {{-- GRAFIK GARIS --}}
            <section class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Grafik Aktivitas Bulanan</h3>
                <div class="relative h-72 sm:h-80 md:h-96">
                    <canvas id="lineChartDokumenArtikel"></canvas>
                </div>
            </section>

            {{-- GRID BAWAH --}}
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <article class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">Knowledge Management System</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi
                        informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi. Melalui
                        tampilan yang intuitif, dashboard ini memuat statistik real-time.
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed mt-4">
                        Seperti total dokumen resmi yang tersimpan (1.238 dokumen), artikel pengetahuan yang
                        dipublikasikan (312 artikel), serta permintaan akses terbaru dari pengguna internal.
                        Setiap dokumen dikelompokkan berdasarkan kategori seperti Regulasi, Pedoman.
                    </p>
                </article>

                <aside class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Dokumen Teratas</h3>

                    @if($dokumenTeratas->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada data.</p>
                    @else
                    <ul class="space-y-3">
                        @foreach($dokumenTeratas as $d)
                        @php
                        // pastikan angka tampil meski alias/kolom berbeda
                        $views = $d->total_views ?? ($d->views_count ?? 0);
                        @endphp
                        <li class="grid grid-cols-[auto,1fr,auto] items-center gap-3 text-sm text-gray-700
                           {{ $loop->odd ? 'bg-[#f3f7fb] rounded-md' : '' }} px-3 py-2">
                            {{-- Urutan ranking 1..5 --}}
                            <span class="w-6 h-6 grid place-items-center rounded-full bg-blue-50 text-blue-700
                                 font-semibold text-xs">
                                {{ $loop->iteration }}
                            </span>

                            <a href="{{ route('kasubbidang.manajemendokumen.show', $d->id) }}"
                                class="truncate hover:underline">
                                {{ $d->nama_dokumen }}
                            </a>

                            {{-- Jumlah views + icon --}}
                            <span class="font-semibold flex items-center gap-1.5 text-gray-600">
                                <span>{{ $views }}</span>
                                <i class="fa-solid fa-eye text-xs"></i>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </aside>
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

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('lineChartDokumenArtikel').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bulan),
            datasets: [{
                    label: 'Dokumen',
                    data: @json($dataDokumen),
                    borderColor: 'rgba(59,130,246,1)',
                    backgroundColor: 'rgba(59,130,246,.12)',
                    fill: true,
                    tension: .35,
                    pointRadius: 2
                },
                {
                    label: 'Artikel',
                    data: @json($dataArtikel),
                    borderColor: 'rgba(34,197,94,1)',
                    backgroundColor: 'rgba(34,197,94,.12)',
                    fill: true,
                    tension: .35,
                    pointRadius: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    </script>
</x-app-layout>