@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Berbagi Pengetahuan Magang')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
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
            title: 'font-bold text-base md:text-lg text-green-800'
        },
        timer: 2200
    });
});
</script>
@endif

{{-- ALERT Hapus --}}
@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fef2f2',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800'
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                    {{-- Search Bar (GET) --}}
                    <form method="GET" action="{{ route('magang.berbagipengetahuan.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari artikel pengetahuan..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95" style="display:none;">
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

            {{-- LIST ARTIKEL → 1 pembungkus putih untuk SEMUA artikel, grid 2 kolom --}}
            <section class="xl:col-span-8 w-full">
                @if($artikels->count())
                <div class="bg-white rounded-2xl shadow-lg border p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($artikels as $artikel)
                        <a href="{{ route('magang.berbagipengetahuan.show', $artikel->id) }}"
                            class="block rounded-xl border border-gray-100 hover:border-blue-300 hover:shadow-md transition p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5">
                                @php
                                $thumb = $artikel->thumbnail ? asset('storage/'.$artikel->thumbnail)
                                : asset('assets/img/artikel-elemen.png');
                                @endphp
                                <img src="{{ $thumb }}" alt="{{ $artikel->judul }}"
                                    class="w-full sm:w-44 lg:w-48 h-40 sm:h-28 md:h-32 lg:h-36 object-cover rounded-lg flex-shrink-0" />

                                <div class="flex flex-col flex-1">
                                    <h3 class="font-bold text-sm sm:text-base text-gray-800 mb-1.5 line-clamp-2"
                                        title="{{ $artikel->judul }}">
                                        {{ $artikel->judul }}
                                    </h3>

                                    <p class="text-[11px] sm:text-xs font-semibold text-blue-600 mb-1">
                                        Kategori: {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                                    </p>

                                    <p class="text-xs sm:text-sm text-gray-600 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 140) }}
                                    </p>

                                    <div
                                        class="flex justify-between items-center text-[11px] sm:text-xs text-gray-500 mt-auto pt-3">
                                        <span class="flex items-center gap-1.5" title="Dilihat">
                                            <i class="fas fa-eye"></i> {{ $artikel->views ?? 0 }}
                                        </span>
                                        <span>{{ \Carbon\Carbon::parse($artikel->created_at)->translatedFormat('d M Y') }}</span>
                                    </div>
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
                    <h3 class="text-xl font-bold text-gray-700">Belum Ada Artikel</h3>
                    <p class="text-gray-500 mt-2 max-w-sm">Saat ini belum ada artikel pengetahuan yang tersedia.</p>
                </div>
                @endif

                <div class="mt-8">
                    <!-- pagination opsional -->
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 xl:pl-2">
                {{-- Card Role --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Magang' }}</p>
                </div>

                {{-- Tambah Artikel --}}
                <a href="{{ route('magang.berbagipengetahuan.create') }}"
                    class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i><span>Tambah Artikel</span>
                </a>

                {{-- Kategori Pengetahuan + Edit/Delete --}}
                <div class="bg-white rounded-2xl shadow-lg p-7 mt-2">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Pengetahuan</h3>

                    <ul class="space-y-2 max-h-80 overflow-auto pr-1">
                        @forelse ($kategori as $kat)
                        <li class="flex items-center justify-between gap-3">
                            <span class="text-sm text-gray-700 truncate">{{ $kat->nama_kategoripengetahuan }}</span>

                            <div class="flex items-center gap-1.5">
                                <button type="button" title="Edit"
                                    class="btn-edit-kat p-1.5 rounded-lg hover:bg-blue-50 text-blue-600"
                                    data-id="{{ $kat->id }}" data-name="{{ e($kat->nama_kategoripengetahuan) }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" title="Hapus"
                                    class="btn-del-kat p-1.5 rounded-lg hover:bg-red-50 text-red-600"
                                    data-id="{{ $kat->id }}" data-name="{{ e($kat->nama_kategoripengetahuan) }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            {{-- FORM EDIT (hidden) --}}
                            <form id="form-edit-kat-{{ $kat->id }}" method="POST"
                                action="{{ route('magang.kategoripengetahuan.update', $kat->id) }}" class="hidden">
                                @csrf @method('PUT')
                                <input type="hidden" name="nama_kategoripengetahuan" value="">
                            </form>

                            {{-- FORM DELETE (hidden) --}}
                            <form id="form-del-kat-{{ $kat->id }}" method="POST"
                                action="{{ route('magang.kategoripengetahuan.destroy', $kat->id) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Belum ada kategori magang.</li>
                        @endforelse
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

    {{-- SweetAlert2 untuk tombol Edit/Delete --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <style>
    .swal2-actions {
        gap: 14px !important
    }
    </style>
    <script>
    // EDIT kategori
    document.querySelectorAll('.btn-edit-kat').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || '';
            const form = document.getElementById(`form-edit-kat-${id}`);

            const res = await Swal.fire({
                title: 'Edit Kategori',
                input: 'text',
                inputLabel: 'Nama kategori',
                inputValue: name,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false,
                inputValidator: v => (!v || !v.trim()) ?
                    'Nama kategori tidak boleh kosong' : undefined
            });

            if (res.isConfirmed) {
                form.querySelector('input[name=nama_kategoripengetahuan]').value = res.value.trim();
                form.submit();
            }
        });
    });

    // DELETE kategori
    document.querySelectorAll('.btn-del-kat').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || '';
            const form = document.getElementById(`form-del-kat-${id}`);

            const res = await Swal.fire({
                title: 'Hapus Kategori?',
                html: 'Kategori <b>' + name.replace(/</g, '&lt;').replace(/>/g, '&gt;') +
                    '</b> akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                customClass: {
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false
            });

            if (res.isConfirmed) form.submit();
        });
    });
    </script>
</x-app-layout>