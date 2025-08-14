@php
use Carbon\Carbon;
use App\Models\KategoriDokumen;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

/**
* Ambil semua kategori untuk sidebar (tanpa tombol tambah).
* Catatan: dilakukan di view agar tidak mengubah controller.
*/
$kategori = KategoriDokumen::orderBy('nama_kategoridokumen')->get();
@endphp

@section('title', 'Manajemen Dokumen Pegawai')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fff0f0',
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
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('pegawai.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Profile Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
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

        {{-- NOTIFIKASI ERROR (jika ada) --}}
        @if(session('error'))
        <div
            class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div
            class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8 w-full">
                <div class="mb-3 xl:-mt-2 2xl:-mt-4">
                    <span class="font-bold text-lg text-[#2171b8]">Daftar Dokumen</span>
                </div>

                <div class="overflow-x-auto">
                    {{-- Pita: Dokumen Dibagikan ke Saya --}}
                    <a href="{{ route('dokumen.dibagikan.ke.saya') }}"
                        class="min-w-full inline-flex items-center justify-center gap-2 px-6 py-3 mb-3 rounded-xl bg-[#2d74bb] hover:bg-[#1f5d97] text-white font-semibold shadow-sm transition whitespace-nowrap">
                        <i class="fa-solid fa-share-from-square"></i>
                        <span>Dokumen Dibagikan ke Saya</span>
                    </a>

                    {{-- TABEL --}}
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-[#2171b8] text-white">
                                <th class="px-6 py-4 text-sm font-semibold rounded-tl-2xl">Preview</th>
                                <th class="px-6 py-4 text-sm font-semibold">Judul</th>
                                <th class="px-6 py-4 text-sm font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-sm font-semibold text-center rounded-tr-2xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                            @php
                            $filePath = $item->path_dokumen ? asset('storage/'.$item->path_dokumen) : null;
                            $ext = $item->path_dokumen ? strtolower(pathinfo($item->path_dokumen, PATHINFO_EXTENSION)) :
                            '';
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                            $rahasia = ($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen ===
                            'Rahasia');
                            @endphp
                            <tr class="row-dokumen border-b border-gray-100 transition hover:bg-[#f5f9ff] cursor-pointer"
                                data-id="{{ $item->id }}" data-rahasia="{{ $rahasia ? '1' : '0' }}">
                                {{-- Preview --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="w-20 h-14 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage && $filePath)
                                        <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover" />
                                        @else
                                        <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Judul + deskripsi singkat --}}
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

                                {{-- Aksi (tanpa ikon lihat) --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('pegawai.manajemendokumen.edit', $item->id) }}"
                                            class="js-no-row w-9 h-9 flex items-center justify-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>

                                        {{-- Hapus (soft delete) --}}
                                        <form action="{{ route('pegawai.manajemendokumen.destroy', $item->id) }}"
                                            method="POST" onsubmit="return confirm('Hapus dokumen ini?');"
                                            class="inline-block js-no-row">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded bg-red-100 hover:bg-red-200 text-red-600 transition"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-gray-500 text-center py-12">
                                    Belum ada dokumen yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION (opsional) --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- MODAL KUNCI RAHASIA --}}
            <div id="modal-kunci" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
                    <form id="form-kunci">
                        @csrf
                        <input type="hidden" name="dokumen_id" id="dokumen_id">
                        <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                            class="w-full rounded-lg border-gray-300 mb-4" required>
                        <div class="flex justify-end gap-2">
                            <button type="button" id="batal-modal"
                                class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">
                                Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}
                        </p>
                        <p class="text-xs">Upload, simpan dan lihat dokumen pengetahuan, teknis, atau dokumentasi kerja pegawai
                            di sini.</p>
                    </div>
                </div>

                {{-- Tombol Tambah Dokumen --}}
                <a href="{{ route('pegawai.manajemendokumen.create') }}"
                    class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2 text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Dokumen
                </a>

                {{-- Kategori Dokumen (list dari DB) --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>

                    @if($kategori->count())
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                            </span>

                            {{-- Aksi (UI saja; pegawai tidak punya akses kelola kategori) --}}
                            <span class="flex gap-1">
                                <button type="button"
                                    class="btn-edit-kategori inline-flex items-center justify-center w-7 h-7 rounded hover:bg-yellow-100 text-yellow-600"
                                    title="Edit" data-id="{{ $kat->id }}"
                                    data-nama="{{ addslashes($kat->nama_kategoridokumen) }}">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>

                                <button type="button"
                                    class="btn-del-kategori inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                    title="Hapus" data-id="{{ $kat->id }}"
                                    data-nama="{{ addslashes($kat->nama_kategoridokumen) }}">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-500">Belum ada kategori terdaftar.</p>
                    @endif
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

    {{-- Script: klik baris untuk buka detail (atau modal rahasia). Kolom Aksi tidak ikut ter-trigger. --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tr.row-dokumen');
        const modal = document.getElementById('modal-kunci');
        const form = document.getElementById('form-kunci');
        const dokumenIdInput = document.getElementById('dokumen_id');
        const batalModal = document.getElementById('batal-modal');

        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Abaikan klik pada tombol edit/delete
                if (e.target.closest('.js-no-row')) return;

                const id = this.dataset.id;
                const isRahasia = this.dataset.rahasia === '1';

                if (isRahasia) {
                    dokumenIdInput.value = id;
                    modal.classList.remove('hidden');
                } else {
                    window.location.href = `/pegawai/manajemendokumen/${id}`;
                }
            });
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const key = form.encrypted_key.value;
            const id = dokumenIdInput.value;
            const url = `/pegawai/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(key)}`;
            window.location.href = url;
        });

        batalModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            form.reset();
        });
    });
    </script>

    {{-- SweetAlert2 (latest) untuk tombol kategori (UI only) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    function escHTML(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Edit kategori -> info akses terbatas
    document.querySelectorAll('.btn-edit-kategori').forEach(btn => {
        btn.addEventListener('click', () => {
            Swal.fire({
                icon: 'info',
                title: 'Akses Terbatas',
                html: 'Pegawai <b>tidak dapat mengubah</b> kategori dokumen. Silakan hubungi atasan (Sekretaris/KaSubbidang) untuk perubahan.',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-2xl px-8 pt-5 pb-6',
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false
            });
        });
    });

    // Delete kategori -> konfirmasi, lalu beri pesan tidak diizinkan
    document.querySelectorAll('.btn-del-kategori').forEach(btn => {
        btn.addEventListener('click', async () => {
            const nama = btn.dataset.nama || '';
            const res = await Swal.fire({
                icon: 'warning',
                title: 'Hapus Kategori?',
                html: 'Kategori <b>' + escHTML(nama) + '</b> akan dihapus.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl px-8 pt-5 pb-6',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg mr-2',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false
            });
            if (res.isConfirmed) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Diizinkan',
                    text: 'Pegawai tidak memiliki izin menghapus kategori dokumen.',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'rounded-2xl px-8 pt-5 pb-6',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                    },
                    buttonsStyling: false
                });
            }
        });
    });
    </script>

</x-app-layout>