@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Dashboard Admin')

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
                Role Anda:<b> {{ Auth::user()->role->nama_role ?? '-' }}</b>
            </div>
        </div>

        {{-- BODY KONTEN --}}
        <div class="p-6 md:p-8">
            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Card 1: Total Dokumen --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-green-500 to-green-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahDokumen }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Dokumen</div>
                    </div>
                    <i class="fa-solid fa-file-lines text-4xl opacity-50"></i>
                </div>

                {{-- Card 2: Total Artikel --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-blue-500 to-blue-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahArtikel }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Artikel Pengetahuan</div>
                    </div>
                    <i class="fa-solid fa-newspaper text-4xl opacity-50"></i>
                </div>

                {{-- Card 3: Total Forum --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-red-500 to-red-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahForum }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Diskusi Forum</div>
                    </div>
                    <i class="fa-solid fa-comments text-4xl opacity-50"></i>
                </div>

                {{-- Card 4: Total Kegiatan --}}
                <div
                    class="flex items-center p-5 rounded-2xl shadow-lg text-white bg-gradient-to-br from-yellow-500 to-yellow-600 transition-transform hover:scale-105">
                    <div class="flex-1">
                        <div class="text-3xl font-bold">{{ $jumlahKegiatan }}</div>
                        <div class="text-sm mt-1 opacity-90">Total Kegiatan</div>
                    </div>
                    <i class="fa-solid fa-calendar-check text-4xl opacity-50"></i>
                </div>
            </div>
            <!-- Tempat untuk chart -->
            <canvas id="aktivitasChart" height="120"></canvas>

            <!-- Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
            const ctx = document.getElementById('aktivitasChart').getContext('2d');

            const aktivitasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bulan),
                    datasets: [{
                            label: 'Dokumen',
                            data: @json($dataDokumen),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Artikel',
                            data: @json($dataArtikel),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Kegiatan',
                            data: @json($dataKegiatan),
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Forum',
                            data: @json($dataForum),
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Grafik Aktivitas Tahunan ({{ date("Y") }})'
                        }
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

            <div class="mt-10">
                <h2 class="text-xl font-bold text-gray-800 mb-4">📄 Dokumen Terbaru</h2>
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th scope="col" class="px-6 py-3">Judul</th>
                                <th scope="col" class="px-6 py-3">Uploader</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dokumenTerbaru as $dokumen)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $dokumen->nama_dokumen }}</td>
                                <td class="px-6 py-4">{{ $dokumen->user->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $dokumen->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('dokumen.show', $dokumen->id) }}" target="_blank"
                                        class="text-blue-600 hover:underline font-medium">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada dokumen terbaru.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MAIN GRID (Charts, Lists, etc) --}}
            <div class="mt-8 max-w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 mb-4">
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Knowledge Management System</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi
                        informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi. Melalui
                        tampilan yang intuitif, dashboard ini memuat statistik real-time.
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed mt-4">
                        Seperti total dokumen resmi yang tersimpan (1.238 dokumen), artikel pengetahuan yang
                        dipublikasikan (312 artikel), serta permintaan akses terbaru dari pengguna internal. Setiap
                        dokumen dikelompokkan berdasarkan kategori seperti Regulasi, Pedoman.
                    </p>
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