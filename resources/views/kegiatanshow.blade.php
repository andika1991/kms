@extends('home.app')
@section('title', $kegiatan->nama_kegiatan)

@section('content')
<section class="relative pb-16">
    <div class="absolute top-0 left-0 right-0 h-96 w-full">
        <div class="h-full w-full bg-cover bg-center"
            style="background-image: url('{{ asset('assets/img/background_section_kegiatan.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-black/30"></div>
    </div>

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
                                <a href="{{ route('kegiatan') }}" class="text-blue-600 hover:underline">Daftar
                                    Kegiatan</a>
                            </li>
                            <li class="flex items-center">
                                <span class="mx-2">></span>
                                <span class="font-semibold text-gray-700 truncate max-w-xs"
                                    title="{{ $kegiatan->nama_kegiatan }}">
                                    {{ \Illuminate\Support\Str::limit($kegiatan->nama_kegiatan, 40) }}
                                </span>
                            </li>
                        </ol>
                    </nav>

                    {{-- Foto Utama Kegiatan --}}
                    @if ($kegiatan->fotokegiatan && $kegiatan->fotokegiatan->count() > 0)
                    <img src="{{ asset('storage/' . $kegiatan->fotokegiatan->first()->path_foto) }}"
                        class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7"
                        alt="{{ $kegiatan->nama_kegiatan }}">
                    @else
                    <img src="https://placehold.co/600x300/E9F2FF/3B82F6?text=Tidak+Ada+Foto"
                        class="w-full rounded-xl h-64 md:h-80 object-cover border mb-7" alt="Tidak ada foto">
                    @endif

                    {{-- Judul --}}
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">
                        {{ $kegiatan->nama_kegiatan }}</h1>

                    {{-- PERBAIKAN 1: Info Subbidang, Kategori, dan Tanggal --}}
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-600 mb-6">
                        @if($kegiatan->subbidang)
                        <div>
                            <span class="font-semibold">Subbidang:</span>
                            <span class="text-blue-700">{{ $kegiatan->subbidang->nama }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="font-semibold">Kategori:</span>
                            <span class="text-blue-700 capitalize">{{ $kegiatan->kategori_kegiatan }}</span>
                        </div>
                        <div class="ml-auto">
                            <span class="font-semibold">Tanggal Upload:</span>
                            <span>{{ \Carbon\Carbon::parse($kegiatan->created_at)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    {{-- PERBAIKAN 2: Deskripsi dengan garis pemisah --}}
                    <div class="border-t border-b py-6 my-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-2">Deskripsi</h3>
                        <div class="prose max-w-none prose-p:leading-relaxed text-gray-800">
                            {!! $kegiatan->deskripsi_kegiatan !!}
                        </div>
                    </div>

                    {{-- Lampiran jika ada --}}
                    @if($kegiatan->file)
                    <div class="mt-8">
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
                        <input name="q" type="text" placeholder="Cari Kegiatan..."
                            class="w-full rounded-full bg-white py-2.5 pl-10 pr-4 border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm transition" />
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
                            <img src="{{ asset('storage/' . ($lain->fotokegiatan->first()->path_foto ?? 'default.jpg')) }}"
                                class="h-20 w-20 object-cover rounded-lg border flex-shrink-0 group-hover:opacity-90 transition-opacity"
                                alt="{{ $lain->nama_kegiatan }}">
                            <div class="flex-1">
                                <h4
                                    class="font-bold text-sm text-gray-800 leading-snug group-hover:text-blue-800 transition-colors line-clamp-2 mb-1">
                                    {{ $lain->nama_kegiatan }}</h4>
                                {{-- PERBAIKAN 3: Menambahkan "Kategori :" --}}
                                <p class="text-xs font-semibold text-blue-700 mb-1 capitalize">Kategori :
                                    {{ $lain->kategori_kegiatan }}</p>
                                {{-- PERBAIKAN 4: Menambahkan deskripsi potongan dan memindahkan tanggal --}}
                                <div class="flex justify-between items-end gap-2">
                                    <p class="text-xs text-gray-600 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($lain->deskripsi_kegiatan), 30) }}
                                    </p>
                                    <p class="text-xs text-gray-500 flex-shrink-0">
                                        {{ \Carbon\Carbon::parse($lain->created_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
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
@endsection