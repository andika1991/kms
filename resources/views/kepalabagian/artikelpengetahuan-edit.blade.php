@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
<div class="w-full min-h-screen bg-[#eaf5ff] pb-10">
    {{-- HEADER --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Artikel Pengetahuan</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-6">
        {{-- FORM KIRI --}}
        <form method="POST" action="{{ route('kepalabagian.artikelpengetahuan.update', $artikelpengetahuan->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-8 flex flex-col gap-7 xl:col-span-8">
            @csrf
            @method('PUT')
            
            {{-- Thumbnail --}}
            <div>
                <label class="block font-semibold text-gray-800 mb-2">Thumbnail</label>
                <div class="relative w-full h-48 md:h-56 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">
                    @if($artikelpengetahuan->thumbnail)
                        <img src="{{ asset('storage/' . $artikelpengetahuan->thumbnail) }}" alt="Thumbnail" class="w-full h-full object-cover" />
                        {{-- Hapus thumbnail --}}
                        <button type="button" onclick="showDeleteModal()" class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs shadow transition">
                            Hapus
                        </button>
                        {{-- Modal hapus thumbnail --}}
                        <div id="deleteModal" class="hidden fixed z-50 inset-0 bg-black/30 flex items-center justify-center">
                            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center max-w-xs">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-yellow-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <h2 class="text-lg font-bold mb-1">Apakah Anda Yakin</h2>
                                    <p class="text-sm text-gray-500 mb-4">Data akan dihapus</p>
                                </div>
                                <form action="{{ route('kepalabagian.artikelpengetahuan.deleteThumbnail', $artikelpengetahuan->id) }}" method="POST" class="flex gap-2 w-full justify-center">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-semibold">Hapus</button>
                                    <button type="button" onclick="hideDeleteModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold">Batalkan</button>
                                </form>
                            </div>
                        </div>
                        <script>
                            function showDeleteModal() {
                                document.getElementById('deleteModal').classList.remove('hidden');
                            }
                            function hideDeleteModal() {
                                document.getElementById('deleteModal').classList.add('hidden');
                            }
                        </script>
                    @else
                        <span class="text-gray-400">Belum ada gambar</span>
                    @endif
                    <label for="thumbnail" class="absolute bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm cursor-pointer transition">
                        Ganti Gambar
                        <input type="file" name="thumbnail" id="thumbnail" class="hidden">
                    </label>
                </div>
            </div>

            {{-- Judul --}}
            <div>
                <label class="block font-semibold text-gray-800 mb-1">Judul Artikel</label>
                <input type="text" name="judul" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500" required value="{{ old('judul', $artikelpengetahuan->judul) }}">
            </div>

            {{-- Kategori & Slug --}}
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full">
                    <label class="block font-semibold text-gray-800 mb-1">Kategori</label>
                    <select name="kategori_pengetahuan_id" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pengetahuan_id', $artikelpengetahuan->kategori_pengetahuan_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoripengetahuan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block font-semibold text-gray-800 mb-1">Slug</label>
                    <input type="text" name="slug" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500" required value="{{ old('slug', $artikelpengetahuan->slug) }}">
                </div>
            </div>

            {{-- Isi Artikel --}}
            <div>
                <label class="block font-semibold text-gray-800 mb-2">Isi Artikel</label>
                <textarea id="isi" name="isi" rows="10" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">{{ old('isi', $artikelpengetahuan->isi) }}</textarea>
            </div>

            {{-- Dokumen --}}
            <div>
                <label class="block font-semibold text-gray-800 mb-2">Dokumen</label>
                @if($artikelpengetahuan->filedok)
                <div class="flex items-center mb-2">
                    <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2H6ZM13 9V3.5L18.5 9H13ZM6 20V4H13V9H18V20H6Z" />
                    </svg>
                    <a href="{{ asset('storage/' . $artikelpengetahuan->filedok) }}" target="_blank" class="text-gray-800 hover:underline">
                        {{ basename($artikelpengetahuan->filedok) }}
                    </a>
                </div>
                @endif
                <input type="file" name="filedok" class="w-full text-gray-700">
            </div>

            {{-- Tombol aksi (mobile) --}}
            <div class="flex gap-4 xl:hidden">
                <button type="submit" class="flex-1 px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">Simpan</button>
                <a href="{{ url()->previous() }}" class="flex-1 px-6 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base text-center">Batalkan</a>
            </div>
        </form>

        {{-- SIDEBAR --}}
        <aside class="xl:col-span-4 w-full flex flex-col gap-8">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                <div>
                    <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                </div>
            </div>
            {{-- Tombol aksi (desktop) --}}
            <div class="gap-4 hidden xl:flex">
                <button form="form-edit" type="submit" class="flex-1 px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">Simpan</button>
                <a href="{{ url()->previous() }}" class="flex-1 px-6 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base text-center">Batalkan</a>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/5tsdsuoydzm2f0tjnkrffxszmoas3as1xlmcg5ujs82or4wz/tinymce/6/tinymce.min.js"
    referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#isi',
        height: 350,
        plugins: 'lists link image preview code fullscreen',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image | preview code fullscreen',
        menubar: false,
    });
</script>
@endpush
</x-app-layout>
