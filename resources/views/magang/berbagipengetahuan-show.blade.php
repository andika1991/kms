@php
/** @var \App\Models\ArtikelPengetahuan $artikelpengetahuan */
$artikel = $artikelpengetahuan;
use Carbon\Carbon;
$carbon = Carbon::parse($artikel->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'View Pengetahuan Magang')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search dummy (agar konsisten tampilan) --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari artikel pengetahuan..."
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

        {{-- KONTEN --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- UTAMA --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 flex flex-col gap-6">
                    {{-- Thumbnail --}}
                    @php
                    $thumb = $artikel->thumbnail ? asset('storage/'.$artikel->thumbnail) :
                    asset('assets/img/artikel-elemen.png');
                    @endphp
                    <img src="{{ $thumb }}" alt="{{ $artikel->judul }}"
                        class="w-full h-64 sm:h-80 md:h-96 object-cover rounded-xl border mb-2" />

                    {{-- Judul --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight">
                        {{ $artikel->judul }}
                    </h1>

                    {{-- Meta --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                        <div>
                            <span>Kategori:</span>
                            <span class="font-semibold text-blue-600">
                                {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                            </span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <div>
                            <span>Dibuat:</span>
                            <span>{{ $tanggal }}</span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-eye"></i> {{ number_format($artikel->views_total) }}
                        </span>
                    </div>

                    {{-- Isi --}}
                    <div
                        class="prose max-w-none prose-img:rounded-xl prose-p:my-2 text-gray-800 text-base leading-relaxed">
                        {!! $artikel->isi !!}
                    </div>

                    {{-- Dokumen Terkait --}}
                    @if ($artikel->filedok)
                    <div class="mt-6">
                        <label class="font-semibold text-gray-800 mb-2 block">Dokumen Terkait</label>
                        <a href="{{ asset('storage/' . $artikel->filedok) }}" target="_blank" download
                            class="flex items-center gap-3 rounded-lg bg-gray-100 p-3 hover:bg-blue-50 transition group w-max">
                            <i class="fa-solid fa-file-lines text-2xl text-blue-600 group-hover:text-blue-800"></i>
                            <span class="text-sm font-medium text-blue-700 underline">
                                {{ \Illuminate\Support\Str::afterLast($artikel->filedok, '/') }}
                            </span>
                        </a>
                    </div>
                    @endif
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'Magang' }}</p>
                </div>

                {{-- Aksi (EDIT/HAPUS) --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('magang.berbagipengetahuan.edit', ['berbagipengetahuan' => $artikel->id]) }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-700
                hover:bg-blue-900 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Artikel</span>
                    </a>

                    <button id="btn-hapus-artikel"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus Artikel</span>
                    </button>

                    <form id="delete-artikel-form"
                        action="{{ route('magang.berbagipengetahuan.destroy', ['berbagipengetahuan' => $artikel->id]) }}"
                        method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
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
    </div>

    {{-- SweetAlert2 (v11.22.3) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.getElementById('btn-hapus-artikel').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda Yakin?',
            html: '<span class="text-gray-600 text-base">Artikel akan dihapus</span>',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batalkan',
            reverseButtons: false,
            focusCancel: true,
            customClass: {
                popup: 'rounded-2xl px-8 pt-5 pb-6',
                icon: 'mt-3 mb-2',
                title: 'mb-1',
                htmlContainer: 'mb-3',
                cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-2 rounded-lg text-base',
                confirmButton: 'bg-[#D32F2F] hover:bg-[#B71C1C] text-white font-semibold px-10 py-2 rounded-lg text-base mr-2',
                actions: 'flex justify-center gap-4',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-artikel-form').submit();
            }
        });
    });
    </script>
</x-app-layout>