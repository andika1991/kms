@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Kegiatan Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Kegiatan Magang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari kegiatan..."
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

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM UTAMA (DAFTAR KEGIATAN) --}}
            <section class="xl:col-span-8 w-full">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-lg text-[#2171b8]">Daftar Kegiatan Magang</span>
                    <a href="{{ route('magang.kegiatan.create') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Kegiatan</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/80 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($kegiatan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $item->nama_kegiatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($item->kategori_kegiatan) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ Str::limit($item->deskripsi_kegiatan, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <a href="{{ route('magang.kegiatan.edit', $item->id) }}"
                                           class="text-yellow-500 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('magang.kegiatan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kegiatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline ml-2">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data kegiatan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- KOLOM SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div class="bg-gradient-to-br from-green-400 to-blue-500 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <i class="fa-solid fa-list-check text-5xl mb-3"></i>
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">Progress Kegiatan Magang</p>
                        <p class="text-xs">Pantau dan catat aktivitas harian selama magang. Kegiatan bisa berupa tugas, laporan, atau proyek magang.</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Magang Sukses</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                        <li>Update laporan kegiatan secara rutin.</li>
                        <li>Berkolaborasi aktif dengan rekan magang.</li>
                        <li>Konsultasikan kendala ke pembimbing.</li>
                        <li>Jangan lupa dokumentasi kegiatan.</li>
                    </ul>
                </div>
            </aside>
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