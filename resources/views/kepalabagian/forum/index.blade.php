@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    <div class="bg-[#f0f4f8] min-h-screen w-full">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Forum Diskusi</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:w-64">
                        <input type="text" placeholder="Cari Forum..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 bg-white rounded-full border border-gray-300 flex items-center justify-center hover:shadow hover:border-blue-500 transition"
                            title="Profile">
                            <i class="fa-solid fa-user text-gray-600"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- LEFT COLUMN --}}
            <section class="xl:col-span-8 space-y-6">
                @forelse($grupchats as $grupchat)
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $grupchat->nama_grup }}</h3>
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $grupchat->deskripsi }}</p>
                        </div>
                        @if(Auth::id() === $grupchat->pengguna_id)
                        <div class="flex gap-2 ml-4 mt-1">
                            {{-- Edit --}}
                            <a href="{{ route('kepalabagian.forum.edit', $grupchat->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('kepalabagian.forum.destroy', $grupchat->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus forum ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                       
                        <div class="text-xs text-gray-500">
                            Dibuat oleh: <span class="font-semibold">{{ $grupchat->pengguna->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('kepalabagian.forum.show', $grupchat->id) }}"
                       class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                        <i class="fa-solid fa-circle-arrow-right mr-1"></i> Lihat Forum
                    </a>
                </div>
                @empty
                <div class="bg-white text-center p-12 rounded-2xl shadow-md">
                    <img src="{{ asset('assets/img/logo_diskominfotik_lampung.png') }}" class="w-32 mx-auto mb-6 opacity-60">
                    <h3 class="text-lg font-bold text-gray-700">Belum ada forum</h3>
                    <p class="text-gray-500 mt-2">Mulai buat diskusi anda.</p>
                </div>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-8">
                    {{-- {{ $grupchats->links() }} --}}
                </div>
            </section>

            {{-- RIGHT SIDEBAR --}}
            <aside class="xl:col-span-4 space-y-6">
                {{-- Tambah Forum --}}
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <a href="{{ route('kepalabagian.forum.create') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold text-base transition">
                        <i class="fa-solid fa-plus"></i> Tambah Forum
                    </a>
                </div>

                {{-- Info Card --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-md text-center">
                    <div class="w-20 h-20 bg-white/20 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fa-solid fa-comments text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">Selamat datang di Forum Diskusi</h3>
                    <p class="text-sm mt-2 text-white/80">Diskusi dan berbagi pengetahuan bersama tim.</p>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Forum Diskusi adalah fitur untuk memfasilitasi komunikasi antar pegawai di lingkungan Dinas Komunikasi, Informatika, dan Statistik Provinsi Lampung dalam wadah kolaboratif yang efisien dan terstruktur.
                    </p>
                </div>
            </aside>
        </div>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-12">
            <div class="max-w-7xl mx-auto px-4 flex justify-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Logo" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>
