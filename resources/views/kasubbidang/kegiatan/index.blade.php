@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Kegiatan Kasubbidang')

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
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-8">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Kegiatan Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari kegiatan..."
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRID CONTENT --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM UTAMA (LIST KEGIATAN) --}}
            <section class="xl:col-span-8 w-full">
                <div class="font-bold text-lg text-[#2171b8] mb-5">Daftar Kegiatan Kasubbidang</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    @forelse($kegiatan as $item)
                    <a href="{{ route('kasubbidang.kegiatan.show', $item->id) }}"
                        class="group bg-white rounded-2xl shadow-md hover:shadow-lg border border-gray-200/80 overflow-hidden flex flex-col transition-all duration-200">
                        {{-- PREVIEW FOTO --}}
                        @if ($item->fotokegiatan->isNotEmpty())
                        <img src="{{ asset('storage/' . $item->fotokegiatan->first()->path_foto) }}"
                            class="w-full h-48 object-cover object-center" alt="{{ $item->nama_kegiatan }}">
                        @else
                        <img src="{{ asset('assets/img/empty-photo.png') }}"
                            class="w-full h-48 object-cover object-center opacity-70" alt="No Image">
                        @endif

                        <div class="flex-1 flex flex-col p-5">
                            <h3
                                class="font-bold text-base md:text-lg text-gray-900 mb-1 group-hover:text-blue-700 line-clamp-2">
                                {{ $item->nama_kegiatan }}
                            </h3>
                            <div class="text-sm text-gray-500 mb-1">Kategori: <span
                                    class="font-semibold">{{ ucfirst($item->kategori_kegiatan) }}</span></div>
                            <div class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->deskripsi_kegiatan }}</div>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="flex items-center text-xs text-gray-500">
                                    <i class="fa fa-eye mr-1"></i> {{ $item->views ?? 0 }}
                                </span>
                                <span
                                    class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full text-center text-gray-500 py-14">
                        Belum ada data kegiatan.
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6">
                {{-- CARD ROLE --}}
                <div
                    class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                    <p class="font-bold text-base leading-tight">Bidang
                        {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                    </p>
                </div>
                {{-- Progress Box --}}
                <div
                    class="bg-gradient-to-br from-green-400 to-blue-500 text-white rounded-2xl shadow-lg p-7 mb-2 flex flex-col items-center justify-center text-center">
                    <i class="fa-solid fa-list-check text-4xl mb-2"></i>
                    <p class="font-bold text-base mb-2">Progress Kegiatan Kasubbidang</p>
                    <p class="text-xs">Pantau dan catat aktivitas harian selama kegiatan. Kegiatan bisa berupa tugas,
                        laporan, atau proyek.</p>
                </div>
                {{-- TOMBOL TAMBAH --}}
                <a href="{{ route('kasubbidang.kegiatan.create') }}"
                    class="flex items-center justify-center gap-2 px-5 py-3 mb-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Kegiatan</span>
                </a>
                {{-- Tips Box --}}
                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Kasubbidang</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                        <li>Fokus pada tugas dengan dampak terbesar terlebih dahulu.</li>
                        <li>Beri staf Anda tanggung jawab dan instruksi jelas.</li>
                        <li>Gunakan tools digital untuk komunikasi dan manajemen tugas.</li>
                        <li>Alokasikan waktu untuk perencanaan singkat setiap pagi .</li>
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