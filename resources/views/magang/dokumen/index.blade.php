@php
use Carbon\Carbon;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

/**
* Kategori untuk sidebar DIAMBIL dari daftar dokumen yang tampil,
* bukan dari tabel kategori (agar Magang tidak "mengelola" kategori).
*/
$kategoriSidebar = collect($dokumen)
->pluck('kategoriDokumen')
->filter() // buang null
->unique('id')
->sortBy('nama_kategoridokumen');
@endphp

@section('title', 'Manajemen Dokumen Magang')

{{-- Toasts sukses/eror (opsional) --}}
@if (session('success') || session('deleted') || session('error'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
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
    @endif

    @if(session('deleted'))
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fff0f0',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800'
        },
        timer: 2500
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'warning',
        title: 'Perhatian',
        text: @json(session('error')),
        confirmButtonText: 'Mengerti',
        customClass: {
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
        },
        buttonsStyling: false
    });
    @endif
});
</script>
@endif

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER (match pegawai) --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('magang.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </form>
                    {{-- Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition style="display:none"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20">
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

        {{-- GRID BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8">
                {{-- Pita "Dokumen Dibagikan ke Saya" --}}
                <a href="{{ route('dokumen.dibagikan.ke.saya') }}"
                    class="inline-flex w-full items-center justify-center gap-2 px-6 py-3 mb-3 rounded-xl bg-[#2d74bb] hover:bg-[#1f5d97] text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-share-from-square"></i><span>Dokumen Dibagikan ke Saya</span>
                </a>

                {{-- TABEL --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border">
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
                            $rahasia = ($item->kategoriDokumen &&
                            strtolower($item->kategoriDokumen->nama_kategoridokumen) === 'rahasia');
                            @endphp
                            <tr class="row-dokumen border-b border-gray-100 hover:bg-[#f5f9ff] transition cursor-pointer"
                                data-id="{{ $item->id }}" data-rahasia="{{ $rahasia ? '1' : '0' }}">
                                {{-- Preview --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="w-20 h-14 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage && $filePath)
                                        <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover">
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

                                {{-- Aksi (tombol diabaikan dari klik baris) --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('magang.manajemendokumen.edit', $item->id) }}"
                                            class="js-no-row w-9 h-9 flex items-center justify-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>
                                        <form action="{{ route('magang.manajemendokumen.destroy', $item->id) }}"
                                            method="POST" class="inline-block js-no-row form-hapus">
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
                                <td colspan="4" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION (jika diperlukan) --}}
                <div class="mt-4">{{-- {{ $dokumen->links() }} --}}</div>
            </section>

            {{-- MODAL kunci rahasia --}}
            <div id="modal-kunci" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
                    <form id="form-kunci">@csrf
                        <input type="hidden" name="dokumen_id" id="dokumen_id">
                        <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                            class="w-full rounded-lg border-gray-300 mb-4 focus:ring-2 focus:ring-blue-500" required>
                        <div class="flex justify-end gap-2">
                            <button type="button" id="batal-modal"
                                class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <p class="font-bold text-lg leading-tight mb-1">{{ Auth::user()->role->nama_role ?? 'Magang' }}</p>
                    <p class="text-xs opacity-95">Upload dan simpan dokumen kamu di sini.</p>
                </div>

                <a href="{{ route('magang.manajemendokumen.create') }}"
                    class="rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2 text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Dokumen
                </a>

                {{-- Kategori dari dokumen yang ada --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>
                    @if($kategoriSidebar->count())
                    <ul class="space-y-2">
                        @foreach($kategoriSidebar as $kat)
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                            </span>
                            <span class="flex gap-1">
                                {{-- Edit (Akses Terbatas) --}}
                                <button type="button"
                                    class="btn-edit-kat inline-flex items-center justify-center w-7 h-7 rounded hover:bg-yellow-100 text-yellow-600"
                                    data-nama="{{ addslashes($kat->nama_kategoridokumen) }}">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>
                                {{-- Delete (Tidak Diizinkan) --}}
                                <button type="button"
                                    class="btn-del-kat inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                    data-nama="{{ addslashes($kat->nama_kategoridokumen) }}">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-500">Belum ada kategori dari dokumenmu.</p>
                    @endif
                </div>
            </aside>
        </main>

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

    {{-- SweetAlert2 (latest) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>

    <script>
    // ------- Klik baris untuk buka detail/rahasia -------
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('tr.row-dokumen');
        const modal = document.getElementById('modal-kunci');
        const form = document.getElementById('form-kunci');
        const idInp = document.getElementById('dokumen_id');

        rows.forEach(row => {
            row.addEventListener('click', e => {
                if (e.target.closest('.js-no-row')) return; // abaikan klik di tombol
                const id = row.dataset.id;
                const rahasia = row.dataset.rahasia === '1';
                if (rahasia) {
                    idInp.value = id;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    window.location.href = `/magang/manajemendokumen/${id}`;
                }
            });
        });

        document.getElementById('batal-modal').addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            form.reset();
        });

        form.addEventListener('submit', e => {
            e.preventDefault();
            const key = form.encrypted_key.value.trim();
            const id = idInp.value;
            const url = `/magang/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(key)}`;
            window.location.href = url;
        });

        // ------- Hapus Dokumen (SweetAlert2) -------
        document.querySelectorAll('form.form-hapus').forEach(f => {
            f.addEventListener('submit', function(ev) {
                ev.preventDefault();
                Swal.fire({
                    width: 560,
                    backdrop: true,
                    iconColor: 'transparent',
                    iconHtml: `
        <svg width="98" height="98" viewBox="0 0 24 24" fill="#F6C343" xmlns="http://www.w3.org/2000/svg">
          <path d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <rect x="11" y="8" width="2" height="6" fill="white"/>
          <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white"/>
        </svg>
      `,
                    title: 'Apakah Anda Yakin',
                    html: '<div class="text-gray-600 text-lg">data akan dihapus</div>',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batalkan',
                    reverseButtons: false,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl px-8 py-8',
                        title: 'text-2xl font-extrabold text-gray-900',
                        htmlContainer: 'mt-1',
                        actions: 'mt-6 flex justify-center gap-6',
                        confirmButton: 'px-10 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-lg font-semibold',
                        cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                    },
                    buttonsStyling: false,
                }).then(res => {
                    if (res.isConfirmed) this.submit();
                });
            });
        });

        // ------- Aksi kategori (UI only, akses terbatas) -------
        function esc(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        document.querySelectorAll('.btn-edit-kat').forEach(btn => {
            btn.addEventListener('click', () => {
                Swal.fire({
                    icon: 'info',
                    title: 'Akses Terbatas',
                    html: 'Magang <b>tidak dapat mengubah</b> kategori dokumen. Silakan hubungi Pegawai/Atasan.',
                    confirmButtonText: 'Mengerti',
                    customClass: {
                        popup: 'rounded-2xl px-8 pt-5 pb-6',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                    },
                    buttonsStyling: false
                });
            });
        });

        document.querySelectorAll('.btn-del-kat').forEach(btn => {
            btn.addEventListener('click', async () => {
                const nama = esc(btn.dataset.nama || '');
                const res = await Swal.fire({
                    width: 560,
                    backdrop: true,
                    iconColor: 'transparent',
                    iconHtml: `
        <svg width="98" height="98" viewBox="0 0 24 24" fill="#F6C343" xmlns="http://www.w3.org/2000/svg">
          <path d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <rect x="11" y="8" width="2" height="6" fill="white"/>
          <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white"/>
        </svg>
      `,
                    title: 'Apakah Anda Yakin',
                    html: '<div class="text-gray-600 text-lg">data akan dihapus</div>',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batalkan',
                    reverseButtons: false,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl px-8 py-8',
                        title: 'text-2xl font-extrabold text-gray-900',
                        htmlContainer: 'mt-1',
                        actions: 'mt-6 flex justify-center gap-6',
                        confirmButton: 'px-10 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-lg font-semibold',
                        cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                    },
                    buttonsStyling: false
                });
                if (res.isConfirmed) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Diizinkan',
                        text: 'Magang tidak memiliki izin menghapus kategori dokumen.',
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
    });
    </script>
</x-app-layout>