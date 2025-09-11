@php
use Carbon\Carbon;
use App\Models\KategoriDokumen;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

/** Ambil kategori untuk sidebar (tanpa tombol tambah). */
$kategori = KategoriDokumen::orderBy('nama_kategoridokumen')->get();
@endphp

@section('title', 'Manajemen Dokumen Pegawai')

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
        background: '#fff0f0',
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
    <main class="min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h1>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('pegawai.manajemendokumen.index') }}"
                        class="relative grow sm:grow-0 sm:w-64">
                        <input name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i
                                class="fa fa-search"></i></span>
                    </form>

                    {{-- Profile Dropdown --}}
                    <nav x-data="{ open:false }" class="relative">
                        <button @click="open=!open" title="Profile"
                            class="size-10 grid place-content-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <ul x-show="open" @click.away="open=false" style="display:none;"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20 overflow-hidden">
                            <li><a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-50">Log
                                        Out</button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section>
        </header>

        {{-- NOTIFIKASI ERROR --}}
        @if(session('error'))
        <aside role="alert"
            class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
            {{ session('error') }}
        </aside>
        @endif
        @if($errors->any())
        <aside role="alert"
            class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </aside>
        @endif

        {{-- BODY GRID --}}
        <section class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8">
                <section class="overflow-x-auto">
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
                                    <figure
                                        class="w-20 h-14 grid place-content-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage && $filePath)
                                        <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover" />
                                        @else
                                        <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>
                                        @endif
                                    </figure>
                                </td>

                                {{-- Judul + desc --}}
                                <td class="px-6 py-4 align-top">
                                    <h4 class="font-medium text-gray-900">{{ $item->nama_dokumen }}</h4>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}
                                    </p>
                                </td>

                                {{-- Kategori --}}
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-top">
                                    <nav class="flex items-center justify-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('pegawai.manajemendokumen.edit', $item->id) }}"
                                            class="js-no-row w-9 h-9 grid place-content-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>

                                        {{-- Hapus -> buka modal hapus --}}
                                        <button type="button"
                                            class="btn-open-delete js-no-row w-9 h-9 grid place-content-center rounded bg-red-100 hover:bg-red-200 text-red-600 transition"
                                            data-action="{{ route('pegawai.manajemendokumen.destroy', $item->id) }}"
                                            data-title="{{ e($item->nama_dokumen) }}" title="Hapus">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
                                    </nav>
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
                </section>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- MODAL KUNCI RAHASIA --}}
            <dialog id="modal-kunci" class="kms-dialog rounded-xl w-full max-w-md p-0">
                <form id="form-kunci" class="p-6">
                    @csrf
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
                    <input type="hidden" name="dokumen_id" id="dokumen_id">
                    <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                        class="w-full rounded-lg border-gray-300 mb-4" required>
                    <div class="flex justify-end gap-2">
                        <button type="button" id="batal-modal"
                            class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Lanjutkan</button>
                    </div>
                </form>
            </dialog>

            {{-- MODAL HAPUS (sesuai screenshot) --}}
            <dialog id="modal-delete" class="kms-dialog rounded-2xl p-0 w-full max-w-xl">
                <section class="bg-white rounded-2xl p-8 md:p-10">
                    <figure class="mx-auto w-20 h-20 grid place-content-center mb-4">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="#F6C343"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <rect x="11" y="8" width="2" height="6" fill="white" />
                            <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white" />
                        </svg>
                    </figure>
                    <h3 class="text-2xl font-extrabold text-center text-gray-900">Apakah Anda Yakin</h3>
                    <p class="text-center text-gray-600 mt-2">data akan dihapus <span id="delete-nama"
                            class="font-semibold"></span></p>
                    <div class="mt-8 flex justify-center gap-6">
                        <button id="btn-delete-confirm"
                            class="px-10 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-lg font-semibold">Hapus</button>
                        <button id="btn-delete-cancel"
                            class="px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold">Batalkan</button>
                    </div>
                </section>
            </dialog>

            {{-- Backdrop untuk seluruh dialog --}}
            <style>
            .kms-dialog::backdrop {
                background: rgba(0, 0, 0, .4)
            }
            </style>

            {{-- FORM DELETE GLOBAL (diset action via JS) --}}
            <form id="form-delete" method="POST" class="hidden">@csrf @method('DELETE')</form>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <article
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <h2 class="font-bold text-lg mb-2">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}</h2>
                    <p class="text-xs">Upload dan simpan dokumen kamu di sini.</p>
                </article>

                <a href="{{ route('pegawai.manajemendokumen.create') }}"
                    class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition inline-flex items-center justify-center gap-2 text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Dokumen
                </a>

                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>

                    @if($kategori->count())
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                            </span>

                            {{-- Aksi (UI only; pegawai tidak kelola kategori) --}}
                            <span class="flex gap-1">
                                <button type="button"
                                    class="btn-edit-kategori grid place-content-center w-7 h-7 rounded hover:bg-yellow-100 text-yellow-600"
                                    title="Edit" data-id="{{ $kat->id }}"
                                    data-nama="{{ addslashes($kat->nama_kategoridokumen) }}">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </button>
                                <button type="button"
                                    class="btn-del-kategori grid place-content-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
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
                </section>
            </aside>
        </section>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </main>

    {{-- Interaksi baris tabel, modal kunci & modal hapus --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ====== KLIK BARIS -> detail / kunci rahasia
        const rows = document.querySelectorAll('tr.row-dokumen');
        const dialogKey = document.getElementById('modal-kunci');
        const formKey = document.getElementById('form-kunci');
        const idInp = document.getElementById('dokumen_id');
        const btnBtl = document.getElementById('batal-modal');

        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                if (e.target.closest('.js-no-row')) return; // abaikan klik tombol aksi
                const id = row.dataset.id;
                const isRahasia = row.dataset.rahasia === '1';
                if (isRahasia) {
                    idInp.value = id;
                    dialogKey.showModal();
                } else {
                    window.location.href = `/pegawai/manajemendokumen/${id}`;
                }
            });
        });

        formKey.addEventListener('submit', (e) => {
            e.preventDefault();
            const key = formKey.encrypted_key.value;
            const id = idInp.value;
            window.location.href =
                `/pegawai/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(key)}`;
        });

        const closeKey = () => {
            dialogKey.close();
            formKey.reset();
        };
        btnBtl.addEventListener('click', closeKey);
        dialogKey.addEventListener('close', () => formKey.reset());

        // ====== MODAL HAPUS
        const deleteDialog = document.getElementById('modal-delete');
        const deleteNameEl = document.getElementById('delete-nama');
        const btnDeleteConfirm = document.getElementById('btn-delete-confirm');
        const btnDeleteCancel = document.getElementById('btn-delete-cancel');
        const formDelete = document.getElementById('form-delete');
        let pendingDeleteAction = null;

        document.querySelectorAll('.btn-open-delete').forEach(btn => {
            btn.addEventListener('click', () => {
                pendingDeleteAction = btn.dataset.action || null;
                const nm = btn.dataset.title || '';
                deleteNameEl.textContent = nm ? `(${nm})` : '';
                deleteDialog.showModal();
            });
        });

        btnDeleteCancel.addEventListener('click', () => deleteDialog.close());
        deleteDialog.addEventListener('cancel', (e) => {
            e.preventDefault();
            deleteDialog.close();
        }); // Esc

        btnDeleteConfirm.addEventListener('click', () => {
            if (!pendingDeleteAction) return deleteDialog.close();
            formDelete.action = pendingDeleteAction;
            formDelete.submit();
        });
    });
    </script>

    {{-- SweetAlert2 (UI only untuk kategori) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    document.querySelectorAll('.btn-edit-kategori').forEach(b => {
        b.addEventListener('click', () => {
            Swal.fire({
                icon: 'info',
                title: 'Akses Terbatas',
                html: 'Pegawai <b>tidak dapat mengubah</b> kategori dokumen. Silakan hubungi atasan (Sekretaris/KaSubbidang).',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-2xl px-8 pt-5 pb-6',
                    confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                },
                buttonsStyling: false
            });
        });
    });

    document.querySelectorAll('.btn-del-kat egori').forEach(b => {
        b.addEventListener('click', async () => {
            const nama = b.dataset.nama || '';
            const res = await Swal.fire({
                width: 560,
                backdrop: true,
                iconColor: 'transparent',
                iconHtml: `
          <svg width="98" height="98" viewBox="0 0 24 24" fill="#F6C343" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.29 3.86L1.82 18A2 2 0 003.55 21h16.9a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <rect x="11" y="8" width="2" height="6" fill="white"/>
            <rect x="11" y="15.5" width="2" height="2" rx="1" fill="white"/>
          </svg>`,
                title: 'Apakah Anda Yakin',
                html: 'Kategori <b>' + esc(nama) + '</b> akan dihapus.',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batalkan',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl px-8 py-8',
                    title: 'text-2xl font-extrabold text-gray-900',
                    htmlContainer: 'mt-1',
                    actions: 'mt-6 flex justify-center gap-6',
                    confirmButton: 'px-10 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-lg font-semibold',
                    cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
                }
            });
            if (res.isConfirmed) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Diizinkan',
                    text: 'Pegawai tidak memiliki izin menghapus kategori dokumen.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl px-8 pt-5 pb-6',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg'
                    }
                });
            }
        });
    });
    </script>
</x-app-layout>