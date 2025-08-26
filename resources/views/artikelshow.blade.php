@extends('home.app')
@section('title', $artikel->judul)

@section('content')
<section class="relative pb-16">
    <!-- Latar Belakang Biru -->
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>
    <main class="relative max-w-[1200px] mx-auto px-4 sm:px-6 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Konten Utama Artikel -->
            <article class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <nav class="text-sm text-gray-500 mb-6">
                        <ol class="list-none p-0 inline-flex items-center flex-wrap">
                            <li class="flex items-center">
                                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Beranda</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <a href="{{ route('pengetahuan') }}" class="text-blue-600 hover:underline">Daftar
                                    Pengetahuan</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <span class="font-semibold text-gray-700 truncate max-w-xs"
                                    title="{{ $artikel->judul }}">{{ \Illuminate\Support\Str::limit($artikel->judul, 40) }}</span>
                            </li>
                        </ol>
                    </nav>

                    @if ($artikel->thumbnail)
                    <img src="{{ asset('storage/' . $artikel->thumbnail) }}"
                        class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7" alt="{{ $artikel->judul }}">
                    @endif

                    <!-- Kategori, Tanggal, Share, Views -->
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 mb-4">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Kategori
                            : {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? 'Umum' }}</span>
                        <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d F Y') }}</span>
                        <div class="flex items-center gap-x-4 ml-auto">
                            <button
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Link berhasil disalin!')"
                                class="flex items-center gap-1.5 hover:text-blue-600 transition" title="Bagikan">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                            </span>
                        </div>
                    </div>

                    <!-- Judul Artikel -->
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">
                        {{ $artikel->judul }}</h1>

                    <!-- Isi Artikel -->
                    <div class="prose max-w-none prose-img:rounded-xl prose-p:leading-relaxed text-gray-800">
                        {!! $artikel->isi !!}
                    </div>

                    <!-- Dokumen Terkait -->
                    @if ($artikel->filedok)
                    <div class="mt-10 pt-6 border-t">
                        <h3 class="font-bold text-lg text-gray-800 mb-4">Dokumen Terkait</h3>
                        <a href="{{ asset('storage/' . $artikel->filedok) }}" target="_blank"
                            class="inline-flex items-center gap-4 rounded-lg bg-gray-100 hover:bg-blue-50 transition-all duration-300 p-3 border group">
                            <img src="https://placehold.co/64x64/E9F2FF/3B82F6?text=PDF"
                                class="h-12 w-12 object-contain rounded-md" alt="PDF Icon">
                            <div>
                                <p class="font-semibold text-blue-700 group-hover:underline">
                                    {{ \Illuminate\Support\Str::afterLast($artikel->filedok, '/') }}</p>
                                <p class="text-xs text-gray-500">Klik untuk melihat atau mengunduh</p>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </article>

            <!-- Sidebar Kanan -->
            <aside class="lg:col-span-1 flex flex-col gap-8">
                <!-- Fitur Cari Pengetahuan -->
                <form action="{{ route('artikel.search') }}" method="GET">
                    <div class="relative">
                        <input name="q" type="text" placeholder="Cari Pengetahuan..."
                            class="w-full rounded-full bg-white py-2.5 pl-10 pr-4 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </form>

                <!-- Pengetahuan Lainnya -->
                <div class="bg-white rounded-2xl shadow-xl p-5">
                    <h3 class="font-bold text-lg mb-5 text-gray-800 border-b pb-3">Pengetahuan Lainnya</h3>
                    <div class="flex flex-col gap-5">
                        @forelse ($pengetahuan_lainnya as $lain)
                        <a href="{{ route('artikel.show', $lain->slug) }}" class="group flex gap-4">
                            <img src="{{ asset('storage/' . ($lain->thumbnail ?? 'default.jpg')) }}"
                                class="h-20 w-20 object-cover rounded-lg border flex-shrink-0 group-hover:opacity-90 transition-opacity"
                                alt="{{ $lain->judul }}">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-blue-700 mb-1">
                                    {{ $lain->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}</p>
                                <h4
                                    class="font-bold text-sm text-gray-800 leading-snug group-hover:text-blue-800 transition-colors line-clamp-2">
                                    {{ $lain->judul }}</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($lain->created_at)->translatedFormat('d M Y') }}</p>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-4">Tidak ada Pengetahuan Lainnya</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>
</section>
@endsection