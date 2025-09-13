@php
use Carbon\Carbon;
use Illuminate\Support\Str;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Kelola Kegiatan Kasubbidang')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: @json(session('success')),
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
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
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
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Kegiatan Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari kegiatan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;">
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
        </header>

        {{-- GRID CONTENT --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM UTAMA (LIST KEGIATAN DALAM SATU BUNGKUS PUTIH, GRID 2 KOLOM) --}}
            <section class="xl:col-span-8 w-full">
                @if($kegiatan->count())
                <div class="rounded-2xl bg-white shadow-lg border border-gray-200/70 p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($kegiatan as $item)
                        @php
                        $views = $item->views ?? 0;
                        $tanggalBuat = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y');
                        $excerpt = Str::limit(strip_tags((string) $item->deskripsi_kegiatan), 110);
                        @endphp

                        <a href="{{ route('kasubbidang.kegiatan.show', $item->id) }}"
                            class="group rounded-xl border border-gray-200 bg-white/60 hover:border-blue-300 hover:shadow-md transition-all grid grid-cols-1 md:grid-cols-[11.5rem_1fr] overflow-hidden">
                            {{-- Foto kiri --}}
                            <div class="md:w-[11.5rem] w-full bg-gray-50">
                                <div class="aspect-[16/10] md:aspect-[4/3] w-full h-full overflow-hidden">
                                    @if ($item->fotokegiatan->isNotEmpty())
                                    <img src="{{ asset('storage/'.$item->fotokegiatan->first()->path_foto) }}"
                                        alt="{{ $item->nama_kegiatan }}" loading="lazy" decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300">
                                    @else
                                    <img src="{{ asset('assets/img/empty-photo.png') }}" alt="Tidak ada foto"
                                        class="w-full h-full object-contain opacity-70">
                                    @endif
                                </div>
                            </div>

                            {{-- Konten kanan --}}
                            <div class="p-4 sm:p-5 flex flex-col">
                                <h3
                                    class="text-base sm:text-lg font-semibold text-gray-900 leading-snug line-clamp-2 group-hover:text-blue-700">
                                    {{ $item->nama_kegiatan }}
                                </h3>

                                <p class="mt-1 text-xs sm:text-[13px] text-gray-500">
                                    Kategori:
                                    <span class="font-semibold">{{ ucfirst($item->kategori_kegiatan) }}</span>
                                </p>

                                <p class="mt-2 text-[13px] sm:text-sm text-gray-600 line-clamp-2">{{ $excerpt }}</p>

                                <div
                                    class="mt-3 flex items-center justify-between text-[12px] sm:text-xs text-gray-500">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="fa-regular fa-eye"></i>  {{ number_format($item->views_count ?? (method_exists($item,'views') ? $item->views()->count() : 0)) }}
                                    </span>
                                    <time datetime="{{ $item->created_at?->toISOString() }}"
                                        class="whitespace-nowrap">{{ $tanggalBuat }}</time>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <div
                    class="flex flex-col items-center justify-center text-center py-20 px-6 bg-white rounded-2xl shadow-lg border">
                    <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-40 mb-6 opacity-80"
                        alt="Data Kosong" />
                    <h3 class="text-xl font-bold text-gray-700">Belum Ada Kegiatan</h3>
                    <p class="text-gray-500 mt-2 max-w-sm">Saat ini belum ada data kegiatan yang tersedia. Silakan
                        tambahkan kegiatan baru.</p>
                </div>
                @endif
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-6">
                <div
                    class="bg-gradient-to-br from-blue-700 to-blue-500 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                    <p class="font-bold text-base leading-tight">
                        Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                    </p>
                </div>

                <a href="{{ route('kasubbidang.kegiatan.create') }}"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Kegiatan</span>
                </a>

                <div class="bg-white rounded-2xl shadow-lg p-7">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Tips Produktif Kasubbidang</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 leading-relaxed space-y-1">
                        <li>Fokus pada tugas dengan dampak terbesar terlebih dahulu.</li>
                        <li>Beri staf Anda tanggung jawab dan instruksi jelas.</li>
                        <li>Gunakan tools digital untuk komunikasi dan manajemen tugas.</li>
                        <li>Alokasikan waktu untuk perencanaan singkat setiap pagi.</li>
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