@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    {{-- Wrapper untuk seluruh konten di sebelah kanan sidebar --}}
    <div class="bg-[#eaf5ff] min-h-screen w-full">
        {{-- HEADER KONTEN --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
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
        </div>

        {{-- BODY KONTEN GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 flex-grow">

            {{-- KOLOM KIRI (DAFTAR FORUM) --}}
            <section class="xl:col-span-8 w-full">
                @forelse($grupchats as $grupchat)
                {{-- Tampilan jika ada forum --}}
                <a href="{{ route('kepalabagian.forum.show', $grupchat->id) }}"
                    class="block bg-white rounded-2xl shadow-lg border border-gray-200/80 p-6 mb-6 hover:shadow-xl hover:border-blue-300 transition-all duration-300">
                    <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $grupchat->nama_grup }}</h3>
                    <p class="text-gray-600 text-sm line-clamp-2">{{ $grupchat->deskripsi }}</p>
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center -space-x-2">
                            <img class="w-8 h-8 rounded-full border-2 border-white"
                                src="{{ asset('assets/img/avatar-placeholder.png') }}" alt="User 1">
                            <img class="w-8 h-8 rounded-full border-2 border-white"
                                src="{{ asset('assets/img/avatar-placeholder.png') }}" alt="User 2">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600 border-2 border-white">
                                +5</div>
                        </div>
                        <div class="text-xs text-gray-500">
                            Dibuat oleh: <span class="font-semibold">{{ $grupchat->user->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </a>
                @empty
                {{-- Tampilan jika tidak ada forum sama sekali --}}
                <div
                    class="flex flex-col items-center justify-center text-center h-full py-20 px-6 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" class="w-48 mb-6 opacity-50"
                        alt="Logo Diskominfotik" />
                    <h3 class="text-xl font-bold text-gray-700">Belum ada forum</h3>
                    <p class="text-gray-500 mt-2">Mulai buat diskusi anda.</p>
                </div>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-8">
                    {{-- {{ $grupchats->links() }} --}}
                </div>
            </section>

            {{-- KOLOM KANAN (SIDEBAR) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                {{-- Kartu Aksi --}}
                <div class="bg-white rounded-2xl shadow-lg p-7 space-y-3">
                    <a href="{{ route('kepalabagian.forum.create') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Forum</span>
                    </a>
                </div>

                {{-- Kartu Placeholder --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-comments text-5xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Tidak ada forum</h3>
                </div>

                {{-- Kartu Deskripsi --}}
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Forum Diskusi merupakan fitur untuk mempermudah pegawai Dinas Komunikasi, Informatika dan
                        Statistik Provinsi Lampung dapat saling berbagi pengetahuan dan memepermudah komunikasi dengan
                        manajemen yang terstruktur dan mudah diakses.
                    </p>
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