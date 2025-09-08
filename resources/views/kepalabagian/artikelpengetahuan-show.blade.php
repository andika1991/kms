@php
use Carbon\Carbon;
$carbon = Carbon::parse($artikel->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'View Pengetahuan Kepala Bagian')

<x-app-layout>
    <main class="min-h-screen w-full bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <header class="bg-[#eaf5ff] border-b border-gray-200 p-6 md:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Artikel Pengetahuan</h2>
                    <p class="mt-1 text-sm font-normal text-gray-500">{{ $tanggal }}</p>
                </div>

                <nav class="flex w-full items-center gap-4 sm:w-auto">
                    {{-- Search tampilan konsisten --}}
                    <label class="relative block flex-grow sm:w-64">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                        <input
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm shadow-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                            type="text" placeholder="Cari artikel pengetahuan...">
                    </label>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open = !open"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-300 bg-white text-lg text-gray-600 transition hover:border-blue-500 hover:text-blue-600 hover:shadow-md"
                                title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <menu x-show="open" @click.away="open = false" x-transition
                              class="absolute right-0 z-20 mt-2 w-48 rounded-xl border bg-white shadow-lg">
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </menu>
                    </div>
                </nav>
            </div>
        </header>

        {{-- KONTEN --}}
        <section class="grid grid-cols-1 gap-8 p-6 md:p-8 xl:grid-cols-12">
            {{-- KONTEN UTAMA --}}
            <article class="xl:col-span-8">
                <section class="flex flex-col gap-6 rounded-2xl bg-white p-6 md:p-10 shadow-lg">
                    @if ($artikel->thumbnail)
                        <img
                            src="{{ asset('storage/'.$artikel->thumbnail) }}"
                            alt="{{ $artikel->judul }}"
                            class="mb-4 h-64 w-full rounded-xl border object-cover sm:h-80 md:h-96" />
                    @endif

                    <h1 class="text-2xl md:text-3xl font-bold leading-tight text-gray-800">
                        {{ $artikel->judul }}
                    </h1>

                    <p class="mb-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                        <span>
                            Kategori:
                            <strong class="text-blue-600">
                                {{ $artikel->kategoriPengetahuan->nama_kategoripengetahuan ?? '-' }}
                            </strong>
                        </span>
                        <span class="hidden sm:inline">|</span>
                        <span>Dibuat: {{ $tanggal }}</span>
                    </p>

                    {{-- Isi --}}
                    <div class="prose max-w-none text-base leading-relaxed text-gray-800 prose-img:rounded-xl prose-p:my-2">
                        {!! $artikel->isi !!}
                    </div>

                    {{-- Dokumen terkait --}}
                    @if ($artikel->filedok)
                        <section>
                            <h3 class="mb-2 font-semibold text-gray-800">Dokumen Terkait</h3>
                            <a href="{{ asset('storage/'.$artikel->filedok) }}" target="_blank" download
                               class="group flex w-max items-center gap-3 rounded-lg bg-gray-100 p-3 transition hover:bg-blue-50">
                                <i class="fa-solid fa-file-pdf text-2xl text-red-500 group-hover:text-red-700"></i>
                                <span class="text-sm font-medium text-blue-700 underline">
                                    {{ \Illuminate\Support\Str::afterLast($artikel->filedok, '/') }}
                                </span>
                            </a>
                        </section>
                    @endif
                </section>
            </article>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 mt-8 flex w-full flex-col gap-8 xl:mt-0">
                <section class="flex flex-col items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-8 text-center text-white shadow-lg">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="mb-4 h-16 w-16">
                    <p class="text-lg font-bold leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                </section>

                {{-- Tombol aksi (pakai rute kepala bagian) --}}
                <nav class="flex flex-col gap-4">
                    <a href="{{ route('kepalabagian.artikelpengetahuan.edit', $artikel->id) }}"
                       class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-900">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Artikel</span>
                    </a>

                    <button id="btn-hapus-artikel"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-5 py-2.5 text-base font-semibold text-white shadow-sm transition hover:bg-red-800">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus Artikel</span>
                    </button>

                    <form id="delete-artikel-form"
                          action="{{ route('kepalabagian.artikelpengetahuan.destroy', $artikel->id) }}"
                          method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </nav>
            </aside>
        </section>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="mt-8 bg-[#2b6cb0] py-4">
                <div class="mx-auto flex max-w-7xl items-center justify-center px-4">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </main>

    {{-- SweetAlert2 (versi terbaru di jsDelivr) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.5/dist/sweetalert2.all.min.js"></script>
    <script>
        document.getElementById('btn-hapus-artikel')?.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Apakah Anda Yakin',
                html: '<span class="text-gray-600 text-base">Artikel akan dihapus</span>',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan',
                reverseButtons: true,
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
