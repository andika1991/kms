@php
use Carbon\Carbon;
use Illuminate\Support\Str;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Artikel Pengetahuan Kasubbidang')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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

{{-- ALERT Hapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    {{-- HEADER --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
            </div>

            <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                {{-- Search Bar --}}
                <form method="GET" action="{{ route('kasubbidang.berbagipengetahuan.index') }}"
                    class="relative flex-grow sm:flex-grow-0 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari artikel pengetahuan..."
                        class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa fa-search"></i>
                    </span>
                </form>

                {{-- Dropdown Profile (tetap) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                        title="Profile">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
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
    </div>

    {{-- BODY --}}
    <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 bg-[#eaf5ff] min-h-[calc(100vh-120px)]">
        {{-- KOLOM KIRI (LIST ARTIKEL di DALAM SATU BUNGKUS PUTIH, GRID 2 kolom) --}}
        <section class="xl:col-span-8 w-full">
            @if($artikels->count())
            <div class="rounded-2xl bg-white shadow-lg border border-gray-200/70 p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    @foreach($artikels as $artikel)
                    @php
                    $views = $artikel->views_count ?? $artikel->views ?? \App\Models\ArticleView::where('artikel_id',
                    $artikel->id)->count();
                    $tanggalBuat = \Carbon\Carbon::parse($artikel->created_at)->format('d/m/Y');
                    $excerpt = Str::limit(strip_tags((string) $artikel->isi), 110);
                    @endphp

                    {{-- Kartu artikel: seluruh kartu bisa diklik --}}
                    <a href="{{ route('kasubbidang.berbagipengetahuan.show', $artikel->id) }}"
                        class="group h-full rounded-xl border border-gray-200 bg-white/60 hover:border-blue-300 hover:shadow-md transition-all grid grid-cols-1 md:grid-cols-[12rem_1fr] overflow-hidden">
                        {{-- Gambar kiri: FULL (fill) responsif --}}
                        <div class="md:w-[12rem] w-full bg-gray-100">
                            <div class="aspect-[16/10] sm:aspect-[4/3] w-full h-full overflow-hidden">
                                @if($artikel->thumbnail)
                                <img src="{{ asset('storage/'.$artikel->thumbnail) }}" alt="{{ $artikel->judul }}"
                                    loading="lazy" decoding="async"
                                    class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-[1.03]">
                                @else
                                <img src="{{ asset('assets/img/artikel-elemen.png') }}" alt="Thumbnail" loading="lazy"
                                    decoding="async" class="w-full h-full object-cover object-center opacity-40">
                                @endif
                            </div>
                        </div>

                        {{-- Konten kanan --}}
                        <div class="p-4 sm:p-5 flex flex-col">
                            <h3
                                class="text-base sm:text-lg font-semibold text-gray-800 leading-snug line-clamp-2 group-hover:text-blue-700">
                                {{ $artikel->judul }}
                            </h3>

                            <p class="mt-1 text-xs sm:text-[13px] text-gray-500">
                                Kategori:
                                <span class="font-semibold">
                                    {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                                </span>
                            </p>

                            <p class="mt-2 text-[13px] sm:text-sm text-gray-600 line-clamp-2">
                                {{ $excerpt }}
                            </p>

                            <div class="mt-3 flex items-center justify-between text-[12px] sm:text-xs text-gray-500">
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                                </span>
                                <time datetime="{{ $artikel->created_at?->toISOString() }}" class="whitespace-nowrap">
                                    {{ $tanggalBuat }}
                                </time>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <div
                class="flex flex-col items-center justify-center text-center py-20 px-6 bg-white rounded-2xl shadow-lg border">
                <img src="{{ asset('assets/img/empty-state.svg') }}" class="w-40 mb-6 opacity-80" alt="Data Kosong" />
                <h3 class="text-xl font-bold text-gray-700">Belum Ada Artikel</h3>
                <p class="text-gray-500 mt-2 max-w-sm">
                    Saat ini belum ada data artikel pengetahuan yang tersedia. Silakan tambahkan artikel baru.
                </p>
            </div>
            @endif

            {{-- Pagination (opsional) --}}
            <div class="mt-8">
                {{-- {{ $artikels->links() }} --}}
            </div>
        </section>

        {{-- KOLOM KANAN (SIDEBAR) --}}
        <aside class="xl:col-span-4 w-full flex flex-col gap-6">
            {{-- Kartu Role --}}
            <div
                class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-6 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-14 w-14 mb-3">
                <p class="font-bold text-lg leading-tight">
                    {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                </p>
            </div>

            {{-- Aksi --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('kasubbidang.berbagipengetahuan.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Artikel</span>
                </a>
                <a href="{{ route('kasubbidang.kategoripengetahuan.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-folder-plus"></i>
                    <span>Tambah Kategori Pengetahuan</span>
                </a>
            </div>

            {{-- Kategori --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Pengetahuan</h3>
                <ul class="space-y-2">
                    @foreach ($kategoriPengetahuans as $kat)
                    <li class="flex items-center justify-between group">
                        <span class="text-sm text-gray-700">{{ $kat->nama_kategoripengetahuan }}</span>
                        <span class="flex gap-1 opacity-70 group-hover:opacity-100 transition">
                            <a href="{{ route('kasubbidang.kategoripengetahuan.edit', $kat->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-blue-100 text-blue-600"
                                title="Edit Kategori">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button type="button"
                                class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                title="Hapus Kategori"
                                onclick="hapusKategori('{{ route('kasubbidang.kategoripengetahuan.destroy', $kat->id) }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>
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

    {{-- SweetAlert helper --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    function hapusKategori(url) {
        Swal.fire({
            title: 'Apakah Anda Yakin',
            text: 'data akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            customClass: {
                popup: 'rounded-xl p-7',
                confirmButton: 'text-base font-semibold px-10 py-2 rounded-lg mr-2 bg-[#A44D3A] text-white hover:bg-[#8c3e2e] focus:ring-2 focus:ring-[#A44D3A] focus:ring-offset-2',
                cancelButton: 'text-base font-semibold px-10 py-2 rounded-lg bg-[#3971A6] text-white hover:bg-[#295480] focus:ring-2 focus:ring-[#3971A6] focus:ring-offset-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
          `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>
</x-app-layout>