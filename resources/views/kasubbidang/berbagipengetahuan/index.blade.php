@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Artikel Pengetahuan Kasubbidang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER KONTEN --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('kasubbidang.berbagipengetahuan.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>
                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY KONTEN GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM KIRI (GRID ARTIKEL) --}}
            <section class="xl:col-span-8 w-full">
                @if($artikels->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($artikels as $artikel)
                    <div
                        class="bg-white rounded-xl shadow-lg border border-gray-200/80 hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col overflow-hidden group">
                        {{-- Gambar Artikel --}}
                        <div class="h-44 w-full flex items-center justify-center bg-gray-100 overflow-hidden">
                            <a href="{{ route('kasubbidang.berbagipengetahuan.show', $artikel->id) }}">
                                @if($artikel->thumbnail)
                                <img src="{{ asset('storage/' . $artikel->thumbnail) }}" alt="{{ $artikel->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                @else
                                <img src="{{ asset('assets/img/artikel-elemen.png') }}" alt="No Image"
                                    class="w-24 h-24 object-contain opacity-40" />
                                @endif
                            </a>
                        </div>
                        {{-- Konten Teks Artikel --}}
                        <div class="flex-1 flex flex-col p-4">
                            <h3 class="font-bold text-base text-gray-800 leading-tight mb-2 line-clamp-2">
                                <a href="{{ route('kasubbidang.berbagipengetahuan.show', $artikel->id) }}"
                                    class="hover:text-blue-700">{{ $artikel->judul }}</a>
                            </h3>
                            <div class="text-xs text-gray-500 mb-2">
                                Kategori: <span
                                    class="font-semibold">{{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400 text-xs mt-auto pt-2">
                                <span><i class="fa fa-eye mr-1"></i> {{ $artikel->views ?? 0 }}</span>
                                <span>·</span>
                                <span>{{ \Carbon\Carbon::parse($artikel->created_at)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div
                    class="flex flex-col items-center justify-center text-center py-20 px-6 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-40 mb-6 opacity-80"
                        alt="Data Kosong" />
                    <h3 class="text-xl font-bold text-gray-700">Belum Ada Artikel</h3>
                    <p class="text-gray-500 mt-2 max-w-sm">Saat ini belum ada data artikel pengetahuan yang tersedia.
                        Silakan tambahkan artikel baru.</p>
                </div>
                @endif
                {{-- Pagination --}}
                <div class="mt-8">
                    {{-- {{ $artikels->links() }} --}}
                </div>
            </section>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                {{-- Kartu Role --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                        </p>
                    </div>
                </div>

                {{-- Kartu Aksi --}}
                <a href="{{ route('kasubbidang.berbagipengetahuan.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Artikel</span>
                </a>
                <a href="{{ route('kasubbidang.kategoripengetahuan.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-folder-plus"></i>
                    <span>Tambah Kategori Pengetahuan</span>
                </a>

                {{-- Kartu Kategori --}}
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Pengetahuan</h3>
                    <ul class="space-y-2">
                        @foreach ($kategoriPengetahuans as $kat)
                        <li class="flex items-center justify-between group">
                            <span class="text-sm text-gray-700">{{ $kat->nama_kategoripengetahuan }}</span>
                            <span class="flex gap-1 opacity-70 group-hover:opacity-100 transition">
                                <a href="{{ route('kasubbidang.kategoripengetahuan.edit', $kat->id) }}"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-blue-100 text-blue-600"
                                    title="Edit Kategori">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('kasubbidang.kategoripengetahuan.destroy', $kat->id) }}"
                                    method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                        title="Hapus Kategori">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </span>
                        </li>
                        @endforeach
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