@php
use Carbon\Carbon;
$carbon = Carbon::parse($dokumen->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
$ext = strtolower(pathinfo($dokumen->path_dokumen, PATHINFO_EXTENSION));
$namaFile = $dokumen->path_dokumen ? basename($dokumen->path_dokumen) : null;

// Ambil list viewers dari relasi, misal $viewers = $dokumen->views()->with('user')->get();
$viewers = $viewers ?? [];
@endphp

@section('title', 'Lihat Dokumen Admin')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari..."
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN KONTEN --}}
        <div class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Konten Dokumen --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-10 flex flex-col gap-4">
                    {{-- Breadcrumb --}}
                    <nav class="mb-1 text-xs text-gray-400">
                        <a href="{{ route('admin.manajemendokumen.index') }}" class="hover:underline">Beranda &gt;
                            Daftar Dokumen</a>
                    </nav>
                    {{-- Judul --}}
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800 mb-1">{{ $dokumen->nama_dokumen }}</h1>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 mb-2">
                        <div>
                            <span>Kategori:</span>
                            <span class="font-semibold text-blue-600">
                                {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                            </span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <div>
                            <span>Uploader:</span>
                            <span>{{ $dokumen->user->name ?? 'Tidak diketahui' }}</span>
                        </div>
                        <span class="hidden sm:inline">|</span>
                        <div>
                            <span>Diunggah:</span>
                            <span>{{ $tanggal }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                        {{-- Dokumen Preview & Actions --}}
                        <div>
                            @if ($dokumen->path_dokumen &&
                            \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->path_dokumen))
                            @if ($ext === 'pdf')
                            <iframe src="{{ asset('storage/' . $dokumen->path_dokumen) }}" width="100%" height="370"
                                class="rounded-xl border mb-3 shadow bg-white"
                                style="min-height:320px; max-height:420px;"></iframe>
                            @elseif(in_array($ext, ['jpg','jpeg','png','webp','gif','bmp']))
                            <img src="{{ asset('storage/'.$dokumen->path_dokumen) }}" alt="Dokumen Gambar"
                                class="w-full h-72 object-contain rounded-xl border mb-4" />
                            @else
                            <div class="flex justify-center items-center h-64 bg-gray-50 rounded-xl border mb-4">
                                <i class="fa-solid fa-file text-7xl text-gray-400"></i>
                            </div>
                            @endif
                            <div class="flex items-center gap-3 mt-2">
                                {{-- Download --}}
                                <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-sm"
                                    target="_blank" download>
                                    <i class="fa-solid fa-download"></i>
                                    Download Dokumen
                                </a>
                                {{-- Share --}}
                                <button type="button"
                                    onclick="copyLink('{{ asset('storage/'.$dokumen->path_dokumen) }}')"
                                    class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-300 text-gray-700 font-medium transition text-sm">
                                    <i class="fa-solid fa-share-nodes"></i> Bagikan
                                </button>
                            </div>
                            @else
                            <p class="italic text-gray-400">Dokumen tidak ditemukan.</p>
                            @endif
                            {{-- Siapa saja yang melihat --}}
                            <div class="flex items-center gap-2 mt-3">
                                <i class="fa-solid fa-eye text-gray-500"></i>
                                <div class="flex -space-x-2">
                                    @foreach ($viewers as $viewer)
                                    <img src="{{ $viewer->pengguna->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($viewer->pengguna->name) }}"
                                        alt="{{ $viewer->pengguna->name }}"
                                        title="{{ $viewer->pengguna->name }} ({{ $viewer->viewed_at ? \Carbon\Carbon::parse($viewer->viewed_at)->diffForHumans() : '' }})"
                                        class="w-7 h-7 rounded-full border-2 border-white shadow" />
                                    @endforeach

                                </div>
                                <span class="inline-flex items-center gap-1">
                                    <i class="fas fa-eye"></i> {{ number_format($dokumen->views_count) }}
                                </span>
                            </div>
                        </div>
                        {{-- Deskripsi --}}
                        <div>
                            <div class="mb-2 font-semibold text-gray-800">Deskripsi</div>
                            <div class="prose max-w-none prose-p:my-2 text-gray-800 text-base leading-relaxed">
                                {!! nl2br(e($dokumen->deskripsi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- Sidebar --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">
                            {{ Auth::user()->role->nama_role ?? 'Administrator' }}
                        </p>
                        <p class="text-xs">Lihat dokumen kegiatan atau knowledge sharing
                            di sini.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <a href="{{ route('admin.manajemendokumen.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow-sm transition text-base text-center">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </aside>
        </div>

        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>
    <script>
    function copyLink(link) {
        navigator.clipboard.writeText(link);
        alert('Link dokumen berhasil disalin!');
    }
    </script>
</x-app-layout>