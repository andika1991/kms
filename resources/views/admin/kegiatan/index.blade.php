@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Kegiatan Admin')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5',
            title: 'font-bold text-base md:text-lg text-green-800',
            icon: 'text-green-500'
        },
        timer: 2200
    });
});
</script>
@endif

@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: '{{ session("deleted") }}',
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800',
            icon: 'text-red-600'
        },
        timer: 2500
    });
});
</script>
@endif

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Kegiatan || Administrator</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form action="{{ route('admin.kegiatan.index') }}" method="GET"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" placeholder="Cari kegiatan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"
                            value="{{ request('search') }}">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>
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

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM UTAMA (LIST KEGIATAN) --}}
            <section class="xl:col-span-8 w-full">
                <div class="font-bold text-lg text-[#2171b8] mb-5">Daftar Kegiatan Admin</div>
                {{-- WRAPPER PUTIH UNTUK SEMUA DAFTAR --}}
                <div class="bg-white rounded-2xl shadow-xl px-7 py-8 mb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-7">
                        @forelse($kegiatan as $item)
                        <a href="{{ route('admin.kegiatan.show', $item->id) }}"
                            class="group rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-200 flex flex-row items-center gap-6 p-4 bg-white">
                            {{-- PREVIEW FOTO KEGIATAN --}}
                            <div class="flex-shrink-0 w-40 h-40 sm:w-44 sm:h-44 rounded-xl overflow-hidden bg-gray-200 flex items-center justify-center border transition-all duration-200">
                                @if ($item->fotokegiatan->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->fotokegiatan->first()->path_foto) }}"
                                        class="object-cover w-full h-full" alt="{{ $item->nama_kegiatan }}">
                                @else
                                    <img src="{{ asset('assets/img/empty-photo.png') }}"
                                        class="object-contain w-14 h-14 opacity-60" alt="No Image">
                                @endif
                            </div>
                            {{-- DETAIL INFO KEGIATAN --}}
                            <div class="flex flex-col flex-1 h-full justify-between py-2">
                                <div>
                                    <h3 class="font-bold text-base md:text-lg text-gray-900 group-hover:text-blue-700 mb-1 line-clamp-2">
                                        {{ $item->nama_kegiatan }}
                                    </h3>
                                    <div class="text-xs sm:text-sm text-gray-600 mb-1">Kategori: <span class="font-semibold">{{ ucfirst($item->kategori_kegiatan) }}</span></div>
                                    <div class="text-xs sm:text-sm text-gray-500 mb-2 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi_kegiatan), 60) }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="flex items-center text-xs text-gray-500">
                                        <i class="fa fa-eye mr-1"></i> {{ $item->views ?? 0 }}
                                    </span>
                                    <span class="text-xs text-gray-400 ml-auto">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full text-center text-gray-500 py-14">
                            Belum ada data kegiatan.
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6">
                {{-- CARD ROLE --}}
                <div
                    class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                    <p class="font-bold text-base leading-tight">Bidang
                        {{ Auth::user()->role->nama_role ?? 'Administrator' }}
                    </p>
                </div>
                <div
                    class="bg-gradient-to-br from-green-400 to-blue-500 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <i class="fa-solid fa-list-check text-5xl mb-3"></i>
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">Tips Kegiatan </p>
                        <p class="text-xs">Catat, dokumentasikan, dan laporkan kegiatan untuk mendukung Knowledge
                            Management di lingkungan kerja.</p>
                    </div>
                </div>
                <a href="{{ route('admin.kegiatan.create') }}"
                    class="flex items-center justify-center gap-2 px-5 py-3 mb-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Kegiatan</span>
                </a>
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Admin</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                        <li>Catat setiap aktivitas harian kerja.</li>
                        <li>Laporkan kegiatan kolaborasi tim.</li>
                        <li>Unggah dokumen/bukti pendukung.</li>
                        <li>Jaga kualitas dokumentasi kegiatan.</li>
                    </ul>
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
