@php
use Carbon\Carbon;
$carbon = Carbon::parse($artikel->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
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
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition>
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
        </div>

        {{-- KONTEN ARTIKEL --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Bagian Kiri --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 flex flex-col gap-5">
                    {{-- Thumbnail Utama --}}
                    @if($artikel->thumbnail)
                        <img src="{{ asset('storage/'.$artikel->thumbnail) }}"
                             alt="{{ $artikel->judul }}"
                             class="w-full h-64 sm:h-80 md:h-96 object-cover rounded-xl border mb-4" />
                    @endif

                    {{-- Judul & Info --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight mb-2">{{ $artikel->judul }}</h1>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 mb-2">
                        <div>
                            <span>Kategori:</span>
                            <span class="font-semibold text-blue-600">
                                {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                            </span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <div>
                            <span>Dibuat:</span>
                            <span>{{ $tanggal }}</span>
                        </div>
                    </div>

                    {{-- Isi Artikel --}}
                    <div class="prose max-w-none prose-img:rounded-xl prose-p:my-2 text-gray-800 text-base leading-relaxed">
                        {!! $artikel->isi !!}
                    </div>

                    {{-- Dokumen Terkait --}}
                    @if($artikel->filedok)
                        <div class="mt-6">
                            <label class="font-semibold text-gray-800 mb-2 block">Dokumen Terkait</label>
                            <a href="{{ asset('storage/' . $artikel->filedok) }}"
                               class="flex items-center gap-3 rounded-lg bg-gray-100 p-3 hover:bg-blue-50 transition group w-max"
                               target="_blank" download>
                                <i class="fa-solid fa-file-pdf text-2xl text-red-500 group-hover:text-red-700"></i>
                                <span class="text-sm font-medium text-blue-700 underline">
                                    {{ \Illuminate\Support\Str::afterLast($artikel->filedok, '/') }}
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Bagian Kanan (Sidebar) --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                {{-- Card Role --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('kepalabagian.artikelpengetahuan.edit', $artikel->id) }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-900 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Artikel</span>
                    </a>
                    <form action="{{ route('kepalabagian.artikelpengetahuan.destroy', $artikel->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus Artikel</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
