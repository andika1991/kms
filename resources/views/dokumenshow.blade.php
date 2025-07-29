@extends('home.app')
@section('title', $dokumen->nama_dokumen)

@section('content')
<section class="relative pb-16">
    <!-- Latar Belakang Biru -->
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>
    <main class="relative max-w-[1200px] mx-auto px-4 sm:px-6 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Konten Utama Dokumen -->
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
                                <a href="{{ route('dokumen') }}" class="text-blue-600 hover:underline">Daftar
                                    Dokumen</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <span class="font-semibold text-gray-700 truncate max-w-xs"
                                    title="{{ $dokumen->nama_dokumen }}">
                                    {{ \Illuminate\Support\Str::limit($dokumen->nama_dokumen, 40) }}
                                </span>
                            </li>
                        </ol>
                    </nav>

                    {{-- Preview Dokumen --}}
                    @if($dokumen->thumbnail)
                    <img src="{{ asset('storage/'.$dokumen->thumbnail) }}"
                        class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7"
                        alt="{{ $dokumen->nama_dokumen }}">
                    @elseif(Str::endsWith($dokumen->path_dokumen, ['.pdf','.PDF']))
                    <div
                        class="w-full rounded-xl h-64 md:h-80 bg-gray-100 border flex items-center justify-center mb-7">
                        <embed src="{{ asset('storage/'.$dokumen->path_dokumen) }}" type="application/pdf" width="100%"
                            height="100%" class="rounded-xl" />
                    </div>
                    @else
                    <img src="https://placehold.co/600x300/E9F2FF/3B82F6?text=No+Preview"
                        class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7" alt="No Preview">
                    @endif

                    {{-- Judul --}}
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 leading-tight">
                        {{ $dokumen->nama_dokumen }}</h1>

                    {{-- Kategori, Tanggal, Share, Views --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 mb-4">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            Kategori : {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                        </span>
                        <span>{{ \Carbon\Carbon::parse($dokumen->created_at)->translatedFormat('d F Y') }}</span>
                        <div class="flex items-center gap-x-4 ml-auto">
                            <button
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Link berhasil disalin!')"
                                class="flex items-center gap-1.5 hover:text-blue-600 transition" title="Bagikan">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <span class="flex items-center gap-1.5" title="Dilihat">
                                <i class="fas fa-eye"></i> {{ $dokumen->views ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-bold text-lg text-gray-800 mt-6 mb-2">Deskripsi</h3>
                    <div class="prose max-w-none prose-p:leading-relaxed text-gray-800 mb-8">
                        {!! $dokumen->deskripsi !!}
                    </div>

                    {{-- Download Dokumen --}}
                    <div class="mt-8 pt-4 border-t">
                        <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}" target="_blank" download
                            class="inline-flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-semibold transition">
                            <i class="fas fa-file-download"></i>
                            Download Dokumen
                        </a>
                    </div>
                </div>
            </article>

            <!-- Sidebar Kanan -->
            <aside class="lg:col-span-1 flex flex-col gap-8">
                {{-- Fitur Cari Dokumen --}}
                <form action="{{ route('dokumen.search') }}" method="GET">
                    <div class="relative">
                        <input name="q" type="text" placeholder="Cari Dokumen..."
                            class="w-full rounded-full bg-white py-2.5 pl-10 pr-4 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </form>

                {{-- Dokumen Lainnya --}}
                <div class="bg-white rounded-2xl shadow-xl p-5">
                    <h3 class="font-bold text-lg mb-5 text-gray-800 border-b pb-3">Dokumen Lainnya</h3>
                    <div class="flex flex-col gap-5">
                        @forelse ($dokumen_lainnya as $lain)
                        <a href="{{ route('dokumen.show', $lain->id) }}" class="group flex gap-4">
                            <img src="{{ asset('storage/' . ($lain->thumbnail ?? 'default.jpg')) }}"
                                class="h-20 w-20 object-cover rounded-lg border flex-shrink-0 group-hover:opacity-90 transition-opacity"
                                alt="{{ $lain->nama_dokumen }}">
                            <div class="flex-1">
                                {{-- PERBAIKAN 2: Menambahkan "Kategori :" --}}
                                <h4
                                    class="font-bold text-sm text-gray-800 leading-snug group-hover:text-blue-800 transition-colors line-clamp-2 mb-1">
                                    {{ $lain->nama_dokumen }}</h4>

                                <p class="text-xs font-semibold text-blue-700 mb-1">Kategori :
                                    {{ $lain->kategoriDokumen->nama_kategoridokumen ?? '-' }}</p>

                                {{-- PERBAIKAN 3: Menambahkan deskripsi potongan dan memindahkan tanggal --}}
                                <div class="flex justify-between items-end gap-2">
                                    <p class="text-xs text-gray-600 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($lain->deskripsi), 30) }}
                                    </p>
                                    <p class="text-xs text-gray-500 flex-shrink-0">
                                        {{ \Carbon\Carbon::parse($lain->created_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-4">Tidak ada Dokumen Lainnya</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </main>
</section>
@endsection