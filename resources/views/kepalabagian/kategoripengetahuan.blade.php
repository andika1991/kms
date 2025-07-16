@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
    $bidangList = [
        ['label' => 'Sekretariat', 'icon' => 'fa-solid fa-building-user'],
        ['label' => 'PLIP', 'icon' => 'fa-solid fa-landmark'],
        ['label' => 'PKP', 'icon' => 'fa-solid fa-people-group'],
        ['label' => 'TIK', 'icon' => 'fa-solid fa-laptop-code'],
        ['label' => 'SanStik', 'icon' => 'fa-solid fa-chart-simple'],
    ];
@endphp

<x-app-layout>
    {{-- Wrapper untuk seluruh konten di sebelah kanan sidebar --}}
    <div class="w-full bg-[#eaf5ff]">

        {{-- HEADER KONTEN --}}
        <div class="p-6 md:px-8 md:pt-8 md:pb-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Kategori Pengetahuan</h2>
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
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                             x-transition style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY KONTEN GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            
            {{-- KOLOM KIRI (TABEL) --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/80">
                    <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-b border-gray-200 gap-4">
                        <span class="font-bold text-lg text-blue-700 border-b-4 border-blue-600 pb-2">
                            Pengetahuan
                        </span>
                        <div class="flex items-center gap-3">
                            <button class="flex items-center gap-2 border border-gray-300 rounded-lg px-4 py-1.5 bg-white hover:bg-gray-50 text-gray-700 font-medium shadow-sm transition text-sm">
                                <span>Urut</span>
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        @if ($kategori->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Judul</th>
                                            <th scope="col" class="py-3 px-6">Tanggal</th>
                                            <th scope="col" class="py-3 px-6">Keterangan</th>
                                            <th scope="col" class="py-3 px-6 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori as $item)
                                        <tr class="bg-white border-b hover:bg-gray-50 transition">
                                            <td class="py-4 px-6 font-medium text-gray-900">{{ $item->judul ?? 'Renja Diskominfotik 2025' }}</td>
                                            <td class="py-4 px-6">{{ $item->tanggal ?? '2025/01/01' }}</td>
                                            <td class="py-4 px-6">{{ $item->keterangan ?? 'Dokumen terbaru 2025' }}</td>
                                            <td class="py-4 px-6 text-center">
                                                <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-20 px-6">
                                <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-40 mb-6 opacity-80" alt="Data Kosong" />
                                <h3 class="text-xl font-bold text-gray-700">Data Tidak Ditemukan</h3>
                                <p class="text-gray-500 mt-2">Saat ini belum ada data kategori pengetahuan yang tersedia.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div class="bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-bold text-lg mb-4 border-b border-white/20 pb-2">Bidang</h3>
                    <ul class="space-y-4">
                        @foreach ($bidangList as $bidang)
                        <li class="flex items-center gap-4 text-white/90 hover:text-white transition cursor-pointer">
                            <i class="{{ $bidang['icon'] }} text-xl w-6 text-center"></i>
                            <span class="font-medium">{{ $bidang['label'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg">Kategori</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi. Melalui tampilan yang intuitif, dashboard ini memuat statistik real-time.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>