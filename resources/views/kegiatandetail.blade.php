@extends('home.app')
@section('title', $kegiatan->nama_kegiatan)

@section('content')
<section class="relative pb-16">
    <!-- Latar Header -->
    <div class="absolute top-0 left-0 right-0 h-72 bg-[#2b6cb0]"></div>

    <main class="relative max-w-[1200px] mx-auto px-4 sm:px-6 pt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Konten Kegiatan -->
            <article class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <!-- Judul Kegiatan -->
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>

                    <!-- Tanggal -->
                    <div class="text-sm text-gray-500 mb-6">
                        Tanggal Kegiatan: {{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d F Y') }}
                    </div>

                    <!-- Galeri Foto -->
                    @if ($kegiatan->fotokegiatan && $kegiatan->fotokegiatan->count())
                        <div class="swiper swiper-container rounded-xl overflow-hidden mb-6">
                            <div class="swiper-wrapper">
                                @foreach ($kegiatan->fotokegiatan as $foto)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $foto->path_foto) }}"
                                             class="w-full h-64 object-cover" alt="Foto Kegiatan">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Navigasi Swiper -->
                            <div class="swiper-button-next text-blue-700"></div>
                            <div class="swiper-button-prev text-blue-700"></div>
                            <div class="swiper-pagination mt-2"></div>
                        </div>
                    @else
                        <p class="text-gray-500 mb-6">Tidak ada foto kegiatan.</p>
                    @endif



                    <!-- Deskripsi Kegiatan Lengkap -->
                    @if ($kegiatan->deskripsi_kegiatan)
                        <h3 class="font-bold text-lg text-gray-800 mt-6 mb-2">Deskripsi Kegiatan</h3>
                        <div class="prose max-w-none text-gray-800 prose-p:leading-relaxed mb-8">
                            {!! $kegiatan->deskripsi_kegiatan !!}
                        </div>
                    @endif

                   <p><strong>Pengupload:</strong> {{ $kegiatan->pengguna->name ?? 'Tidak diketahui' }}</p>
                    <div class="mt-6 pt-4 border-t text-sm text-gray-600">
                        <p><strong>Subbidang:</strong> {{ $kegiatan->subbidang->nama ?? '-' }}</p>
                    </div>
                </div>
            </article>

     <!-- Sidebar -->
<aside class="lg:col-span-1 flex flex-col gap-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-2">Kegiatan Lainnya</h3>
    @forelse ($kegiatan_lain as $lainnya)
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <a href="{{ route('kegiatan.detail', $lainnya->id) }}">
                @if ($lainnya->fotokegiatan->count())
                    <img src="{{ asset('storage/' . $lainnya->fotokegiatan->first()->path_foto) }}"
                        alt="Foto Kegiatan" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                        Tidak ada foto
                    </div>
                @endif
                <div class="p-4">
                    <h4 class="text-base font-bold text-gray-800 mb-1 line-clamp-2">
                        {{ $lainnya->nama_kegiatan }}
                    </h4>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($lainnya->tanggal_kegiatan)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </a>
        </div>
    @empty
        <p class="text-gray-500">Belum ada kegiatan lain.</p>
    @endforelse
</aside>

            <aside class="lg:col-span-1 flex flex-col gap-6">
                {{-- Tempatkan kegiatan lain, berita, atau iklan --}}
            </aside>
        </div>
    </main>
</section>

<!-- Swiper JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    const swiper = new Swiper('.swiper-container', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        spaceBetween: 16,
    });
</script>
@endsection
