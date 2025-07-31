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

@section('title', 'Dashboard Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Selamat Datang di KMS Magang Diskominfotik
                        Lampung</h2>
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

                {{-- Notifikasi Bell --}}
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
            <div class="text-gray-700 text-sm font-medium mt-4">
                Halo, selamat datang <b>{{ Auth::user()->name }}</b>!
                Role Anda: <b>{{ Auth::user()->role->nama_role ?? '-' }}</b>
            </div>

        </div>

        {{-- BODY KONTEN --}}
        <div class="p-6 md:p-8">
            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahKegiatan }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Kegiatan Magang</div>
                    </div>
                    <i class="fa-solid fa-clipboard-list text-4xl opacity-50"></i>
                </div>
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahArtikel }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Artikel Saya</div>
                    </div>
                    <i class="fa-solid fa-share-nodes text-4xl opacity-50"></i>
                </div>
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahDokumen }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Dokumen diunggah</div>
                    </div>
                    <i class="fa-solid fa-file-upload text-4xl opacity-50"></i>
                </div>
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-indigo-500 to-indigo-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahForum }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Forum Diskusi</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl opacity-50"></i>
                </div>
            </div>
            <h3> <b>Statistik performa saya</b></h3>
            <canvas id="chartDokumenArtikel" class="w-full h-64 bg-white rounded-xl p-4 shadow-md mb-8"></canvas>

            <!-- Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
            const ctx = document.getElementById('chartDokumenArtikel').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bulan),
                    datasets: [{
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
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
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
                                stepSize: 1
                            }
                        }
                    }
                }
            });
            </script>

            {{-- Dokumen/Laporan Teratas --}}
            {{-- Dokumen/Laporan Teratas --}}
            <div class="lg:w-5/12 w-full flex flex-col justify-center">
                <h3 class="font-bold text-base sm:text-lg text-gray-800 mb-4 text-center lg:text-left">Dokumen/Laporan
                    Magang Teratas</h3>
                <ul class="space-y-3">
                    @forelse($dokumenTerbaru as $index => $dokumen)
                    <li
                        class="flex justify-between items-center text-sm text-gray-700 {{ $index % 2 == 0 ? 'bg-[#f3f7fb] rounded-md' : '' }} px-3 py-2">
                        <span>{{ $dokumen->nama_dokumen }}</span>
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