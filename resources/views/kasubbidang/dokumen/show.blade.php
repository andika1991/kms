@php
use Carbon\Carbon;
$carbon = Carbon::parse($dokumen->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
$viewCount = $dokumen->views_count ?? 0;
@endphp

@section('title', 'View Dokumen Kasubbidang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari…"
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- MAIN CARD --}}
            <section class="xl:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    {{-- Title --}}
                    <h1 class="text-2xl md:text-[26px] font-bold text-gray-900">{{ $dokumen->nama_dokumen }}</h1>

                    {{-- Meta row --}}
                    <div class="mt-2 flex items-start justify-between gap-4">
                        <div class="text-gray-600 text-sm space-y-1">
                            <p>
                                <span class="font-semibold">Kategori:</span>
                                <span class="text-gray-800">
                                    {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                </span>
                            </p>
                            <p>{{ $tanggal }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1 text-gray-600">
                                <i class="fa-solid fa-eye"></i><span class="text-sm">{{ $viewCount }}</span>
                            </span>
                            @if(Auth::id() === $dokumen->pengguna_id)
                            <a href="{{ route('aksesdokumen.bagikan', $dokumen->id) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#3b82f6] hover:bg-[#2563eb] text-white text-xs md:text-sm font-semibold transition">
                                <i class="fa-solid fa-share-nodes"></i> Bagikan Dokumen
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Pemisah: dipertebal --}}
                    <div class="my-4 border-t-2 border-gray-300"></div>

                    {{-- Content split: preview (left) + description (right) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Preview --}}
                        <figure
                            class="rounded-xl border-2 border-gray-200 bg-gray-50 p-3 grid place-items-center overflow-hidden">
                            @php
                            $ext = strtolower(\Illuminate\Support\Str::afterLast($dokumen->path_dokumen ?? '', '.'));
                            @endphp

                            @if($dokumen->thumbnail &&
                            \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->thumbnail))
                            <img src="{{ asset('storage/'.$dokumen->thumbnail) }}"
                                alt="Thumbnail {{ $dokumen->nama_dokumen }}"
                                class="w-full h-auto max-h-[520px] object-contain rounded-lg">
                            @elseif($ext && in_array($ext, ['jpg','jpeg','png','webp','gif','bmp']))
                            <img src="{{ asset('storage/'.$dokumen->path_dokumen) }}" alt="Pratinjau dokumen"
                                class="w-full h-auto max-h-[520px] object-contain rounded-lg">
                            @elseif($ext === 'pdf')
                            <iframe src="{{ asset('storage/'.$dokumen->path_dokumen) }}"
                                class="w-full h-[520px] rounded-lg border-2 border-gray-200"
                                title="Dokumen PDF"></iframe>
                            @else
                            <div class="text-center text-gray-400">
                                <i class="fa-solid fa-file text-6xl"></i>
                                <p class="mt-2 text-sm">Pratinjau tidak tersedia</p>
                            </div>
                            @endif
                        </figure>

                        {{-- Description --}}
                        <section>
                            <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <div class="text-gray-800 leading-relaxed text-sm md:text-base">
                                {!! nl2br(e($dokumen->deskripsi)) !!}
                            </div>

                            {{-- HAPUS link/URL unduhan karena sudah ada preview --}}
                            {{-- (Jika suatu saat ingin mengembalikan tombol unduh, letakkan di sini) --}}
                        </section>
                    </div>
                </article>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 space-y-6">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-3">
                    <p class="font-bold">Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                </section>

                <a href="{{ route('kasubbidang.manajemendokumen.edit', $dokumen->id) }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-[#356ea7] hover:bg-[#295480] text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Dokumen
                </a>

                {{-- Tambahan: Kembali ke daftar --}}
                <a href="{{ route('kasubbidang.manajemendokumen.index') }}"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </aside>
        </main>
    </div>

    {{-- FOOTER --}}
    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>
</x-app-layout>