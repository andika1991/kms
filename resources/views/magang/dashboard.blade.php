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

/* ====== DATA PER-BIDANG UNTUK CHART TAMBAHAN (tanpa ubah controller) ====== */
use App\Models\Bidang;
use App\Models\Dokumen;
use App\Models\ArtikelPengetahuan;

$userId = Auth::id();
$bidangList = Bidang::orderBy('nama')->get();
$bidangLabels = $bidangList->pluck('nama')->map(fn($n) => $n ?? 'Bidang')->values();

$docsByBidang = [];
$artsByBidang = [];
foreach ($bidangList as $b) {
    // Dokumen milik user berdasarkan kategori dokumen -> bidang_id
    $docsByBidang[] = Dokumen::where('pengguna_id', $userId)
        ->whereHas('kategoriDokumen', function ($q) use ($b) {
            $q->where('bidang_id', $b->id);
        })->count();

    // Artikel pengetahuan milik user berdasarkan kategori pengetahuan -> bidang_id
    $artsByBidang[] = ArtikelPengetahuan::where('pengguna_id', $userId)
        ->whereHas('kategoriPengetahuan', function ($q) use ($b) {
            $q->where('bidang_id', $b->id);
        })->count();
}
@endphp

@section('title', 'Dashboard Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER (jangan diubah) --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">
                        Selamat Datang di KMS Magang Diskominfotik Lampung
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
                                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Notifikasi Bell (jangan diubah) --}}
                    <a href="{{ route('notifikasi.index') }}"
                       class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-blue-600 text-lg hover:shadow-md hover:border-blue-500 transition">
                        <i class="fa-solid fa-bell"></i>
                        @if($jumlahNotifikasi > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 leading-none">
                                {{ $jumlahNotifikasi }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="text-gray-700 text-sm font-medium mt-4">
                Halo, selamat datang <b>{{ Auth::user()->name }}</b>!
                Role Anda: <b>{{ Auth::user()->role->nama_role ?? '-' }}</b>
            </div>
        </div>

        {{-- BODY KONTEN --}}
        <div class="p-6 md:p-8">
            {{-- STATS CARDS (tetap) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahKegiatan }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Kegiatan Magang</div>
                    </div>
                    <i class="fa-solid fa-clipboard-list text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahArtikel }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Artikel Saya</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahDokumen }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Dokumen diunggah</div>
                    </div>
                    <i class="fa-solid fa-file-upload text-4xl opacity-50"></i>
                </div>
                <div class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-indigo-500 to-indigo-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahForum }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Forum Diskusi</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl opacity-50"></i>
                </div>
            </div>

            {{-- ====== JANGAN DIUBAH: Statistik performa saya ====== --}}
            <h3><b>Statistik performa saya</b></h3>
            <canvas id="chartDokumenArtikel" class="w-full h-64 bg-white rounded-xl p-4 shadow-md mb-8"></canvas>

            {{-- Chart.js CDN (sekali saja) --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // LINE bulanan (tetap)
                const ctx = document.getElementById('chartDokumenArtikel').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($bulan),
                        datasets: [
                            {
                                label: 'Berbagi Dokumen',
                                data: @json($dataDokumen),
                                fill: false,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                tension: 0.4,
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                            },
                            {
                                label: 'Berbagi Artikel Pengetahuan',
                                data: @json($dataArtikel),
                                fill: false,
                                borderColor: 'rgba(255, 159, 64, 1)',
                                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                tension: 0.4,
                                pointBackgroundColor: 'rgba(255, 159, 64, 1)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        interaction: { mode: 'nearest', axis: 'x', intersect: false },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            </script>

            {{-- ====== START: Bagian baru yang kamu minta ====== --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-10">
                {{-- Dokumen/Laporan Magang Teratas --}}
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl shadow-xl p-6 h-full">
                        <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4">
                            Dokumen/Laporan Magang Teratas
                        </h3>
                        <ul class="space-y-3">
                            @forelse($dokumenTerbaru as $index => $dokumen)
                                <li class="flex justify-between items-center text-sm text-gray-700 {{ $index % 2 == 0 ? 'bg-[#f3f7fb] rounded-md' : '' }} px-3 py-2">
                                    <span class="line-clamp-1">{{ $dokumen->nama_dokumen }}</span>
                                    <span class="font-semibold flex items-center gap-1.5 text-gray-500">
                                        {{ $dokumen->view_count ?? 0 }}
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </span>
                                </li>
                            @empty
                                <li class="text-gray-500 text-sm text-center py-2">Belum ada dokumen yang diunggah.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- Line 3D Perbandingan Dokumen (Dokumen vs Artikel per Bidang) --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl shadow-xl p-6 h-full">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-base sm:text-lg text-gray-800">Perbandingan Dokumen</h3>
                        </div>
                        <canvas id="linePerbandingan" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>

            {{-- Row 2: KMS text + 2 bar charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
                {{-- KMS text --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-xl p-6 h-full">
                        <h3 class="font-bold text-base sm:text-lg text-blue-900 mb-3">Knowledge Management System</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            Dashboard KMS Magang dirancang membantu kamu mengelola dan memantau dokumen serta artikel
                            pengetahuan yang dipublikasikan per Bidang/Subbidang. Pantau pertumbuhan pengetahuan dan
                            kontribusi artikelmu lewat grafik interaktif di samping.
                        </p>
                    </div>
                </div>

                {{-- Bar 3D Perkembangan Pengetahuan (per Bidang) --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-xl p-6 h-full">
                        <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-3">Perkembangan Pengetahuan</h3>
                        <canvas id="barPengetahuan" class="w-full h-64"></canvas>
                    </div>
                </div>

                {{-- Bar 3D Perkembangan Artikel (per Bidang) --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-xl p-6 h-full">
                        <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-3">Perkembangan Artikel</h3>
                        <canvas id="barArtikel" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>
            {{-- ====== END: Bagian baru ====== --}}

            {{-- Footer --}}
            <x-slot name="footer">
                <footer class="bg-[#2b6cb0] py-4 mt-8">
                    <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                        <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                             class="h-10 object-contain">
                    </div>
                </footer>
            </x-slot>
        </div>
    </div>

    {{-- ====== Script untuk 3 chart tambahan (efek 3D via shadow + gradient) ====== --}}
    <script>
        // Plugin sederhana untuk shadow (kesan 3D)
        const shadowPlugin = {
            id: 'shadowPlugin',
            beforeDatasetsDraw(chart, args, opts) {
                const {ctx} = chart;
                ctx.save();
                ctx.shadowColor = 'rgba(0,0,0,0.15)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetY = 6;
            },
            afterDatasetsDraw(chart, args, opts) {
                chart.ctx.restore();
            }
        };
        Chart.register(shadowPlugin);

        // Utilities gradient
        function makeGradient(ctx, color) {
            const g = ctx.createLinearGradient(0, 0, 0, 220);
            // warna disesuaikan figma: biru & merah
            if (color === 'blue') {
                g.addColorStop(0, 'rgba(59,130,246,0.35)'); // biru
                g.addColorStop(1, 'rgba(59,130,246,0.05)');
            } else if (color === 'red') {
                g.addColorStop(0, 'rgba(239,68,68,0.45)');  // merah
                g.addColorStop(1, 'rgba(239,68,68,0.08)');
            } else {
                g.addColorStop(0, 'rgba(99,102,241,0.4)');  // indigo
                g.addColorStop(1, 'rgba(99,102,241,0.06)');
            }
            return g;
        }

        // DATA dari Blade (per-Bidang)
        const BIDANG_LABELS  = @json($bidangLabels);
        const DOCS_BIDANG    = @json($docsByBidang);
        const ARTS_BIDANG    = @json($artsByBidang);

        // Line “3D” Perbandingan Dokumen (Dokumen vs Artikel per Bidang)
        const ctxLine = document.getElementById('linePerbandingan').getContext('2d');
        const gradBlue = makeGradient(ctxLine, 'blue');
        const gradRed  = makeGradient(ctxLine, 'red');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: BIDANG_LABELS,
                datasets: [
                    {
                        label: 'Dokumen',
                        data: DOCS_BIDANG,
                        borderColor: 'rgba(59,130,246,1)',
                        backgroundColor: gradBlue,
                        pointBackgroundColor: 'rgba(59,130,246,1)',
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Artikel',
                        data: ARTS_BIDANG,
                        borderColor: 'rgba(239,68,68,1)',
                        backgroundColor: gradRed,
                        pointBackgroundColor: 'rgba(239,68,68,1)',
                        tension: 0.35,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { intersect: false, mode: 'index' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // Bar “3D” Perkembangan Pengetahuan (merah – sesuai figma)
        const ctxBar1 = document.getElementById('barPengetahuan').getContext('2d');
        const redGrad = makeGradient(ctxBar1, 'red');

        new Chart(ctxBar1, {
            type: 'bar',
            data: {
                labels: BIDANG_LABELS,
                datasets: [{
                    label: 'Artikel Pengetahuan',
                    data: ARTS_BIDANG,
                    backgroundColor: redGrad,
                    borderColor: 'rgba(239,68,68,1)',
                    borderWidth: 1.2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });

        // Bar “3D” Perkembangan Artikel (juga merah—mengikuti figma)
        const ctxBar2 = document.getElementById('barArtikel').getContext('2d');
        const redGrad2 = makeGradient(ctxBar2, 'red');

        new Chart(ctxBar2, {
            type: 'bar',
            data: {
                labels: BIDANG_LABELS,
                datasets: [{
                    label: 'Artikel',
                    data: ARTS_BIDANG, // jika ingin khusus “artikel”, dataset ini sudah benar
                    backgroundColor: redGrad2,
                    borderColor: 'rgba(239,68,68,1)',
                    borderWidth: 1.2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</x-app-layout>
