@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Kegiatan Admin')

{{-- ALERTS --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () =>
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5',
            title: 'font-bold text-base md:text-lg text-green-800'
        },
        timer: 2200
    })
);
</script>
@endif
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () =>
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800'
        },
        timer: 2500
    })
);
</script>
@endif

<x-app-layout>
    <section class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Kegiatan</h1>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <form action="{{ route('admin.kegiatan.index') }}" method="GET"
                        class="relative flex-1 sm:flex-none sm:w-64">
                        <input name="search" value="{{ request('search') }}" placeholder="Cari kegiatan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </form>

                    <nav x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile"><i class="fa-solid fa-user"></i></button>
                        <div x-show="open" @click.away="open=false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- DAFTAR KEGIATAN (Single white wrapper) --}}
            <section class="xl:col-span-8">
                <div class="bg-white rounded-2xl shadow-xl border p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($kegiatan as $item)
                        <a href="{{ route('admin.kegiatan.show', $item->id) }}"
                            class="group flex gap-4 rounded-xl p-3 hover:bg-gray-50 transition">
                            {{-- Thumb --}}
                            <figure
                                class="w-40 h-40 md:w-44 md:h-44 rounded-xl overflow-hidden bg-gray-200 border grid place-items-center flex-shrink-0">
                                @if ($item->fotokegiatan->isNotEmpty())
                                <img src="{{ asset('storage/' . $item->fotokegiatan->first()->path_foto) }}"
                                    alt="{{ $item->nama_kegiatan }}" class="w-full h-full object-cover" />
                                @else
                                <img src="{{ asset('assets/img/empty-photo.png') }}" alt="Tidak ada foto"
                                    class="w-14 h-14 object-contain opacity-60" />
                                @endif
                            </figure>

                            {{-- Info --}}
                            <article class="flex-1">
                                <h3
                                    class="font-bold text-base md:text-lg text-gray-900 group-hover:text-blue-700 mb-1 line-clamp-2">
                                    {{ $item->nama_kegiatan }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">
                                    Kategori: <span class="font-semibold">{{ ucfirst($item->kategori_kegiatan) }}</span>
                                </p>
                                <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi_kegiatan), 80) }}
                                </p>

                                <footer class="mt-2 flex items-center gap-4">
                                    <span class="inline-flex items-center text-xs text-gray-500">
                                        <i class="fa fa-eye mr-1"></i>
                                        {{ number_format($item->views_count ?? (method_exists($item,'views') ? $item->views()->count() : 0)) }}
                                    </span>
                                    <time class="text-xs text-gray-400 ml-auto"
                                        datetime="{{ optional($item->created_at)->toDateString() }}">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    </time>
                                </footer>
                            </article>
                        </a>
                        @empty
                        <p class="col-span-full text-center text-gray-500 py-10">Belum ada data kegiatan.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-6">
                <section
                    class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-14 w-14 mx-auto mb-3">
                    <p class="font-bold leading-tight">Role {{ Auth::user()->role->nama_role ?? 'Administrator' }}</p>
                </section>

                <a href="{{ route('admin.kegiatan.create') }}"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus"></i> Tambah Kegiatan
                </a>

                <section class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Admin</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                        <li>Catat setiap aktivitas harian kerja.</li>
                        <li>Laporkan kegiatan kolaborasi tim.</li>
                        <li>Unggah dokumen/bukti pendukung.</li>
                        <li>Jaga kualitas dokumentasi kegiatan.</li>
                    </ul>
                </section>
            </aside>
        </main>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </section>
</x-app-layout>