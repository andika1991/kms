@extends('home.app')
@section('title', $kegiatan->nama_kegiatan)

@section('content')
<section class="relative pb-16">
    <!-- Latar Belakang Biru -->
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>
    <main class="relative max-w-[1200px] mx-auto px-4 sm:px-6 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Konten Utama Kegiatan -->
            <article class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    {{-- Breadcrumbs --}}
                    <nav class="text-sm text-gray-500 mb-6">
                        <ol class="list-none p-0 inline-flex items-center flex-wrap">
                            <li class="flex items-center">
                                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Beranda</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <a href="{{ route('kegiatan') }}" class="text-blue-600 hover:underline">Daftar Kegiatan</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <span class="font-semibold text-gray-700 truncate max-w-xs" title="{{ $kegiatan->nama_kegiatan }}">
                                    {{ \Illuminate\Support\Str::limit($kegiatan->nama_kegiatan, 40) }}
                                </span>
                            </li>
                        </ol>
                    </nav>

                    {{-- Slider/Foto Kegiatan --}}
                    @if($kegiatan->fotokegiatan && $kegiatan->fotokegiatan->count())
                        <div x-data="{ idx: 0 }" class="relative w-full rounded-xl overflow-hidden mb-7">
                            <template x-if="$wire.entangle('idx') >= 0">
                                <img 
                                    :src="'{{ asset('storage') }}/' + '{{ $kegiatan->fotokegiatan[0]->path_foto }}'"
                                    class="w-full h-64 md:h-80 object-cover rounded-xl border"
                                    alt="Foto Kegiatan"
                                    x-bind:src="'{{ asset('storage') }}/' + '{{ $kegiatan->fotokegiatan[idx]->path_foto }}'"
                                >
                            </template>
                            @if($kegiatan->fotokegiatan->count() > 1)
                            <button @click="idx = (idx - 1 + {{ $kegiatan->fotokegiatan->count() }}) % {{ $kegiatan->fotokegiatan->count() }}" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-blue-600 hover:text-white text-gray-700 rounded-full shadow w-8 h-8 flex items-center justify-center z-10">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="idx = (idx + 1) % {{ $kegiatan->fotokegiatan->count() }}" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-blue-600 hover:text-white text-gray-700 rounded-full shadow w-8 h-8 flex items-center justify-center z-10">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            @endif
                        </div>
                    @else
                        <img src="https://placehold.co/600x300/E9F2FF/3B82F6?text=No+Image" class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7" alt="No Foto">
                    @endif

                    {{-- Kategori, Tanggal, Share, Views --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $kegiatan->bidang->nama ?? '-' }}
                        </span>
                        @if($kegiatan->subbidang)
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $kegiatan->subbidang->nama }}
                        </span>
                        @endif
                        <span>{{ \Carbon\Carbon::parse($kegiatan->waktu)->translatedFormat('d F Y') }}</span>
                        <div class="flex items-center gap-x-4 ml-auto">
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link berhasil disalin!')" class="flex items-center gap-1.5 hover:text-blue-600 transition" title="Bagikan">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <span class="flex items-center gap-1.5" title="Dilihat">
                                <i class="fas fa-eye"></i> {{ $kegiatan->views ?? 0 }}
                            </span>
                        </div>
                    </div>

                    {{-- Judul Kegiatan --}}
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 leading-tight">{{ $kegiatan->nama_kegiatan }}</h1>

                    {{-- Deskripsi Kegiatan --}}
                    <div class="prose max-w-none prose-p:leading-relaxed text-gray-800 mb-8">
                        {!! $kegiatan->deskripsi !!}
                    </div>

                    {{-- Lampiran File/Download jika ada --}}
                    @if($kegiatan->file)
                    <div class="mt-8 pt-4 border-t">
                        <a href="{{ asset('storage/' . $kegiatan->file) }}" target="_blank" download
                           class="inline-flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-semibold transition">
                            <i class="fas fa-file-download"></i>
                            Download Lampiran
                        </a>
                    </div>
                    @endif
                </div>
            </article>

            <!-- Sidebar Kanan -->
            <aside class="lg:col-span-1 flex flex-col gap-8">
                {{-- Fitur Cari Kegiatan --}}
                <form action="{{ route('kegiatan') }}" method="GET">
                    <div class="relative">
                        <input name="q" type="text" placeholder="Cari Kegiatan..." class="w-full rounded-full bg-white py-2.5 pl-10 pr-4 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </form>

                {{-- Kegiatan Lainnya --}}
                <div class="bg-white rounded-2xl shadow-xl p-5">
                    <h3 class="font-bold text-lg mb-5 text-gray-800 border-b pb-3">Kegiatan Lainnya</h3>
                    <div class="flex flex-col gap-5">
                        @forelse ($kegiatan_lainnya as $lain)
                        <a href="{{ route('kegiatan.show', $lain->id) }}" class="group flex gap-4">
                            @if($lain->fotokegiatan->first())
                            <img src="{{ asset('storage/'.$lain->fotokegiatan->first()->path_foto) }}" class="h-20 w-20 object-cover rounded-lg border flex-shrink-0 group-hover:opacity-90 transition-opacity" alt="{{ $lain->nama_kegiatan }}">
                            @else
                            <img src="https://placehold.co/100x100/E9F2FF/3B82F6?text=No+Img" class="h-20 w-20 object-cover rounded-lg border flex-shrink-0 group-hover:opacity-90 transition-opacity" alt="No Image">
                            @endif
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-blue-700 mb-1">{{ $lain->bidang->nama ?? '-' }}</p>
                                <h4 class="font-bold text-sm text-gray-800 leading-snug group-hover:text-blue-800 transition-colors line-clamp-2">{{ $lain->nama_kegiatan }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($lain->waktu)->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-4">Tidak ada Kegiatan Lainnya</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>
</section>

{{-- Alpine.js for Slider --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection