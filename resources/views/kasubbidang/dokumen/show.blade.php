@php
use Carbon\Carbon;
$carbon = Carbon::parse($dokumen->created_at)->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar (optional, bisa dihapus kalau tidak pakai) --}}
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  @if(Auth::id() === $dokumen->pengguna_id)
        <a href="{{ route('aksesdokumen.bagikan', $dokumen->id) }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-xl shadow-lg transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 12H3m0 0l3.293-3.293a1 1 0 011.414 0L12 12l-4.293 4.293a1 1 0 01-1.414 0L3 12z"/>
            </svg>
            Bagikan Dokumen
        </a>
    @endif
        {{-- KONTEN DOKUMEN --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- Bagian Kiri --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 flex flex-col gap-5">
                    {{-- Thumbnail (jika ada thumbnail di DB) --}}
                    @if($dokumen->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->thumbnail))
                        <img src="{{ asset('storage/'.$dokumen->thumbnail) }}"
                            alt="Thumbnail {{ $dokumen->nama_dokumen }}"
                            class="w-full h-64 sm:h-80 md:h-96 object-cover rounded-xl border mb-4" />
                    @elseif($dokumen->path_dokumen)
                        {{-- Jika tidak ada thumbnail, tampilkan PDF preview, image, atau icon --}}
                        @php
                            $ext = strtolower(\Illuminate\Support\Str::afterLast($dokumen->path_dokumen, '.'));
                        @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','webp','gif','bmp']))
                            <img src="{{ asset('storage/'.$dokumen->path_dokumen) }}"
                                alt="Dokumen Gambar"
                                class="w-full h-64 sm:h-80 md:h-96 object-contain rounded-xl border mb-4" />
                        @elseif($ext == 'pdf')
                            <iframe src="{{ asset('storage/'.$dokumen->path_dokumen) }}" width="100%" height="480" class="rounded-xl border mb-4"></iframe>
                        @else
                            <div class="flex justify-center items-center h-64 bg-gray-50 rounded-xl border mb-4">
                                <i class="fa-solid fa-file text-7xl text-gray-400"></i>
                            </div>
                        @endif
                    @endif

                    {{-- Judul & Info --}}
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight mb-2">
                        {{ $dokumen->nama_dokumen }}
                    </h1>
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

                    {{-- Deskripsi --}}
                    <div class="prose max-w-none prose-p:my-2 text-gray-800 text-base leading-relaxed mb-4">
                        {!! nl2br(e($dokumen->deskripsi)) !!}
                    </div>

                    {{-- File Dokumen Download --}}
                    @if($dokumen->path_dokumen && \Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->path_dokumen))
                        <div class="mt-4">
                            <label class="font-semibold text-gray-800 mb-2 block">File Dokumen</label>
                            <a href="{{ asset('storage/' . $dokumen->path_dokumen) }}"
                                class="flex items-center gap-3 rounded-lg bg-gray-100 p-3 hover:bg-blue-50 transition group w-max"
                                target="_blank" download>
                                <i class="fa-solid fa-download text-2xl text-green-600 group-hover:text-green-700"></i>
                                <span class="text-sm font-medium text-blue-700 underline">
                                    {{ \Illuminate\Support\Str::afterLast($dokumen->path_dokumen, '/') }}
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Sidebar --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">
                            {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                        </p>
                    </div>
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('kasubbidang.manajemendokumen.edit', $dokumen->id) }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-900 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Dokumen</span>
                    </a>
                    <form id="delete-dokumen-form"
                        action="{{ route('kasubbidang.manajemendokumen.destroy', $dokumen->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus Dokumen</span>
                        </button>
                    </form>
                    <a href="{{ route('kasubbidang.manajemendokumen.index') }}"
                        class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow-sm transition text-base text-center">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Daftar</span>
                    </a>
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
</x-app-layout>