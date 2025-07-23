@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Sekretaris')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('sekretaris.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
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

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA (TABEL DOKUMEN) --}}
            <section class="xl:col-span-8 w-full">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-4">
                    <span class="font-bold text-lg text-[#2171b8]">Daftar Dokumen</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-gray-100">
                                <th class="px-6 py-4 text-base font-semibold">Preview</th>
                                <th class="px-6 py-4 text-base font-semibold">Judul Dokumen</th>
                                <th class="px-6 py-4 text-base font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                            @php
                            $filePath = $item->path_dokumen ? asset('storage/'.$item->path_dokumen) : null;
                            $extension = $item->path_dokumen ? strtolower(pathinfo($item->path_dokumen,
                            PATHINFO_EXTENSION)) : '';
                            $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-[#f2f8ff] transition">
                                {{-- Preview/File --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="w-20 h-14 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage)
                                        <img src="{{ asset('storage/'.$item->path_dokumen) }}"
                                            alt="{{ $item->nama_dokumen }}" class="object-cover w-full h-full" />
                                        @elseif($extension == 'pdf')
                                        <img src="{{ asset('assets/img/icon-pdf.svg') }}" alt="PDF"
                                            class="w-10 h-10 object-contain" />
                                        @elseif(in_array($extension, ['doc','docx']))
                                        <img src="{{ asset('assets/img/icon-word.svg') }}" alt="Word"
                                            class="w-10 h-10 object-contain" />
                                        @elseif(in_array($extension, ['xls','xlsx']))
                                        <img src="{{ asset('assets/img/icon-excel.svg') }}" alt="Excel"
                                            class="w-10 h-10 object-contain" />
                                        @else
                                        <img src="{{ asset('assets/img/default-file.svg') }}" alt="File"
                                            class="w-10 h-10 object-contain opacity-60" />
                                        @endif
                                    </div>
                                </td>
                                {{-- Judul --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                    <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}
                                    </div>
                                </td>
                                {{-- Kategori --}}
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>
                                {{-- Aksi --}}
                                <td class="px-6 py-4 flex items-center gap-2 justify-center align-top">
                                    <a href="{{ route('sekretaris.manajemendokumen.show', $item->id) }}"
                                        class="px-4 py-1.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm">Lihat</a>
                                    <a href="{{ route('sekretaris.manajemendokumen.edit', $item->id) }}"
                                        class="px-4 py-1.5 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition text-sm">Edit</a>
                                    <form action="{{ route('sekretaris.manajemendokumen.destroy', $item->id) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Hapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-1.5 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- KOLOM SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">
                            {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}
                        </p>
                        <p class="text-xs">Upload, simpan, dan kelola dokumen dengan mudah.</p>
                    </div>
                </div>

                {{-- Tombol Aksi (vertikal, penuh) --}}
                <div class="flex flex-col gap-3 w-full mt-2">
                    <a href="{{ route('sekretaris.manajemendokumen.create') }}"
                        class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-plus"></i> Tambah Dokumen
                    </a>
                    <button onclick="document.getElementById('kategoriModal').classList.remove('hidden')"
                        class="w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-folder-plus"></i> Tambah Kategori
                    </button>
                </div>

                {{-- Card Kategori --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between group">
                            <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                            <span class="flex gap-1 opacity-70 group-hover:opacity-100 transition">
                                <button onclick="openEditKategoriModal({{ $kat->id }})"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-yellow-100 text-yellow-600"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('sekretaris.kategori-dokumen.destroy', $kat->id) }}"
                                    method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                        title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>

        {{-- MODAL TAMBAH KATEGORI --}}
        <div id="kategoriModal"
            class="fixed z-50 inset-0 hidden bg-black bg-opacity-60 flex items-center justify-center transition">
            <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
                <div class="flex flex-col items-center mb-3">
                    <div
                        class="rounded-full bg-gradient-to-br from-blue-500 to-blue-300 w-16 h-16 flex items-center justify-center mb-2">
                        <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                    </div>
                    <h2 class="font-bold text-lg text-gray-800 mb-2 text-center">Tambah Kategori Dokumen</h2>
                </div>
                <form action="{{ route('sekretaris.kategori-dokumen.store') }}" method="POST"
                    class="w-full flex flex-col items-center gap-4">
                    @csrf
                    <input type="text" name="nama_kategori" id="nama_kategori"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                        placeholder="Masukkan nama kategori" required>
                    <div class="flex w-full gap-2 mt-2 justify-end">
                        <button type="button" onclick="closeKategoriModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT KATEGORI --}}
        <div id="editKategoriModal"
            class="fixed z-50 inset-0 hidden bg-black bg-opacity-60 flex items-center justify-center transition">
            <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
                <div class="flex flex-col items-center mb-3">
                    <div
                        class="rounded-full bg-gradient-to-br from-blue-500 to-blue-300 w-16 h-16 flex items-center justify-center mb-2">
                        <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                    </div>
                    <h2 class="font-bold text-lg text-gray-800 mb-2 text-center">Edit Kategori Dokumen</h2>
                </div>
                <form id="editKategoriForm" method="POST" class="w-full flex flex-col items-center gap-4">
                    @csrf
                    @method('PUT')
                    <input type="text" name="nama_kategori" id="edit_nama_kategori"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                        placeholder="Masukkan nama kategori" required>
                    <div class="flex w-full gap-2 mt-2 justify-end">
                        <button type="button" onclick="closeEditKategoriModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <script>
        function openEditKategoriModal(id) {
        function showKeyModal(button) {
            const id = button.dataset.id;
            const nama = button.dataset.nama;

            document.getElementById('modalTitle').innerText = 'Masukkan Kunci Dokumen: ' + nama;
            let form = document.getElementById('keyForm');
            form.action = "/sekretaris/manajemendokumen/" + id;
            document.getElementById('keyModal').classList.remove('hidden');
        }

        function closeKeyModal() {
            document.getElementById('keyModal').classList.add('hidden');
        }

        function closeEditKategoriModal() {
            document.getElementById('editKategoriModal').classList.add('hidden');
        }

        function closeKategoriModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }
        </script>

        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
</x-app-layout>