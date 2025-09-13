@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Sekretaris')

{{-- ALERT Sukses --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
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
            title: 'font-bold text-base md:text-lg text-red-800',
            icon: 'text-red-600'
        },
        timer: 2500
    });
});
</script>
@endif

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('sekretaris.manajemendokumen.index') }}"
                        class="relative flex-1 sm:flex-none sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.outside="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY GRID --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8">
                <h3 class="mb-3 xl:-mt-2 2xl:-mt-4 font-bold text-lg text-[#2171b8]">Daftar Dokumen</h3>

                @if(session('error'))
                <p
                    class="mb-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
                    {{ session('error') }}
                </p>
                @endif

                @if($errors->any())
                <div
                    class="mb-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="overflow-x-auto">
                    {{-- Pita: Dokumen Dibagikan --}}
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
                                <th class="px-6 py-4 text-sm font-semibold">Judul Dokumen</th>
                                <th class="px-6 py-4 text-sm font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-sm font-semibold text-center rounded-tr-2xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                            @php
                            $filePath = $item->path_dokumen ? asset('storage/'.$item->path_dokumen) : null;
                            $extension = $item->path_dokumen ? strtolower(pathinfo($item->path_dokumen,
                            PATHINFO_EXTENSION)) : '';
                            $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                            $isSecret = ($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen ===
                            'Rahasia');
                            @endphp
                            <tr class="border-b border-gray-100 transition group hover:bg-[#f5f9ff] cursor-pointer"
                                data-id="{{ $item->id }}" data-secret="{{ $isSecret ? '1' : '0' }}"
                                data-url="{{ route('sekretaris.manajemendokumen.show', $item->id) }}"
                                onclick="openDokumenRow(this)">
                                {{-- Preview --}}
                                <td class="px-6 py-4">
                                    <figure
                                        class="w-20 h-14 grid place-items-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage && $filePath)
                                        <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover" />
                                        @else
                                        <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>
                                        @endif
                                    </figure>
                                </td>

                                {{-- Judul --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
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
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($isSecret)
                                        <button onclick="event.stopPropagation(); showPasswordModal('{{ $item->id }}', 'show')"
                                            class="w-9 h-9 grid place-items-center rounded bg-blue-100 hover:bg-blue-200 text-blue-600 transition"
                                            title="Lihat (Rahasia)">
                                            <i class="fa-solid fa-key text-lg"></i>
                                        </button>
                                        @endif

                                        {{-- PERBAIKAN DI SINI: Ganti <a> tag dengan <button> --}}
                                        <button
                                            onclick="event.stopPropagation(); handleEditClick('{{ $item->id }}', '{{ $isSecret ? '1' : '0' }}')"
                                            class="w-9 h-9 grid place-items-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </button>

                                        <button type="button"
                                            onclick="event.stopPropagation(); hapusDokumen('{{ route('sekretaris.manajemendokumen.destroy', $item->id) }}')"
                                            class="w-9 h-9 grid place-items-center rounded bg-red-100 hover:bg-red-200 text-red-600 transition"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash text-lg"></i>
                                        </button>
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

                {{-- PAGINATION (opsional) --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                        class="h-16 w-16 mx-auto mb-4">
                    <p class="font-bold text-lg leading-tight mb-1">{{ Auth::user()->role->nama_role ?? 'Sekretaris' }}
                    </p>
                    <p class="text-xs opacity-90">Upload, simpan, dan kelola dokumen dengan mudah.</p>
                </section>

                <nav class="flex flex-col gap-3 mt-2">
                    <a href="{{ route('sekretaris.manajemendokumen.create') }}"
                        class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition inline-flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-plus"></i> Tambah Dokumen
                    </a>
                    <button onclick="document.getElementById('kategoriModal').classList.remove('hidden')"
                        class="w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold px-5 py-2.5 shadow transition inline-flex items-center justify-center gap-2 text-base">
                        <i class="fa-solid fa-folder-plus"></i> Tambah Kategori
                    </button>
                </nav>

                {{-- Card Kategori --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                            <span class="flex gap-1">
                                <button
                                    onclick="openEditKategoriModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategoridokumen) }}')"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-yellow-100 text-yellow-600"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button"
                                    onclick="hapusKategori('{{ route('sekretaris.kategori-dokumen.destroy', $kat->id) }}')"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-red-100 text-red-600"
                                    title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </section>
            </aside>
        </main>

        {{-- MODALS --}}
        {{-- TAMBAH KATEGORI --}}
        <div id="kategoriModal" class="fixed inset-0 z-50 hidden">
            <div class="min-h-full bg-black/60 p-4 grid place-items-center">
                <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8">
                    <header class="text-center mb-3">
                        <div
                            class="mx-auto mb-2 w-16 h-16 grid place-items-center rounded-full bg-gradient-to-br from-blue-500 to-blue-300">
                            <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                        </div>
                        <h2 class="font-bold text-lg text-gray-800">Tambah Kategori Dokumen</h2>
                    </header>

                    <form action="{{ route('sekretaris.kategori-dokumen.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="text" name="nama_kategori" id="nama_kategori"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                            placeholder="Masukkan nama kategori" required>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" onclick="closeKategoriModal()"
                                class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT KATEGORI --}}
        <div id="editKategoriModal" class="fixed inset-0 z-50 hidden">
            <div class="min-h-full bg-black/60 p-4 grid place-items-center">
                <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8">
                    <header class="text-center mb-3">
                        <div
                            class="mx-auto mb-2 w-16 h-16 grid place-items-center rounded-full bg-gradient-to-br from-yellow-500 to-yellow-300">
                            <i class="fa-solid fa-pen-to-square text-white text-3xl"></i>
                        </div>
                        <h2 class="font-bold text-lg text-gray-800">Edit Kategori Dokumen</h2>
                    </header>

                    <form id="editKategoriForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="text" name="nama_kategori" id="edit_nama_kategori"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-yellow-500 px-4 py-3 text-base text-center"
                            placeholder="Masukkan nama kategori baru" required>
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" onclick="closeEditKategoriModal()"
                                class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- PASSWORD DOKUMEN RAHASIA --}}
        <div id="passwordModal" class="fixed inset-0 z-50 hidden">
            <div class="min-h-full bg-black/60 p-4 grid place-items-center">
                <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8">
                    <header class="text-center mb-4">
                        <div
                            class="mx-auto mb-2 w-16 h-16 grid place-items-center rounded-full bg-gradient-to-br from-red-500 to-yellow-400">
                            <i class="fa-solid fa-lock text-white text-3xl"></i>
                        </div>
                        <h2 class="font-bold text-lg text-gray-800">Masukkan Kunci Dokumen</h2>
                        <p class="text-sm text-gray-500 mt-1" id="modalPurposeText">Dokumen ini bersifat rahasia.</p>
                    </header>

                    <input type="hidden" id="dokumenId">
                    <input type="hidden" id="modalActionPurpose">
                    <input type="password" id="modalPasswordInput"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                        placeholder="••••••••••" required>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                        <button type="button" onclick="submitPassword()"
                            class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">Lanjut</button>
                    </div>
                </div>
            </div>
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

    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
        // Klik baris dokumen
        function openDokumenRow(row) {
            const isSecret = row.dataset.secret === '1';
            const id = row.dataset.id;
            const url = row.dataset.url;
            if (isSecret) {
                showPasswordModal(id, 'show');
            } else {
                window.location.href = url;
            }
        }

        // PERBAIKAN DI SINI
        function handleEditClick(id, isSecret) {
            if (isSecret === '1') {
                showPasswordModal(id, 'edit');
            } else {
                window.location.href = `/sekretaris/manajemendokumen/${id}/edit`;
            }
        }

        // Modal kategori
        function closeKategoriModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }

        function openEditKategoriModal(id, nama) {
            const m = document.getElementById('editKategoriModal');
            m.querySelector('#edit_nama_kategori').value = nama;
            m.querySelector('#editKategoriForm').action = `/sekretaris/kategori-dokumen/${id}`;
            m.classList.remove('hidden');
        }

        function closeEditKategoriModal() {
            document.getElementById('editKategoriModal').classList.add('hidden');
        }

        // Modal rahasia
        function showPasswordModal(dokumenId, action) {
            const m = document.getElementById('passwordModal');
            const purposeText = document.getElementById('modalPurposeText');
            m.classList.remove('hidden');
            m.querySelector('#dokumenId').value = dokumenId;
            m.querySelector('#modalActionPurpose').value = action;
            const inp = m.querySelector('#modalPasswordInput');
            
            // Mengubah teks modal berdasarkan aksi (show/edit)
            if (action === 'edit') {
                purposeText.textContent = 'Masukkan kunci untuk mengedit dokumen rahasia.';
            } else {
                purposeText.textContent = 'Dokumen ini bersifat rahasia.';
            }

            inp.value = '';
            inp.focus();
        }

        function closeModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        // Mengirimkan password berdasarkan aksi (show/edit)
        function submitPassword() {
            const id = document.getElementById('dokumenId').value;
            const password = document.getElementById('modalPasswordInput').value.trim();
            const action = document.getElementById('modalActionPurpose').value;

            if (!password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Kunci tidak boleh kosong!'
                });
                return;
            }
            
            if (action === 'edit') {
                // Redirect ke halaman edit dengan kunci
                window.location.href = `/sekretaris/manajemendokumen/${id}/edit?encrypted_key=${encodeURIComponent(password)}`;
            } else {
                // Redirect ke halaman show dengan kunci
                window.location.href = `/sekretaris/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(password)}`;
            }
        }

        // Hapus
        function hapusDokumen(url) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Dokumen akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                    popup: 'rounded-xl p-7',
                    confirmButton: 'text-base font-semibold px-8 py-2 rounded-lg mr-2 bg-red-600 text-white hover:bg-red-700',
                    cancelButton: 'text-base font-semibold px-8 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) {
                    const f = document.createElement('form');
                    f.action = url;
                    f.method = 'POST';
                    f.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(f);
                    f.submit();
                }
            });
        }

        function hapusKategori(url) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Menghapus kategori akan membuat dokumen terkait tidak memiliki kategori.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batalkan',
                customClass: {
                    popup: 'rounded-xl p-7',
                    confirmButton: 'text-base font-semibold px-8 py-2 rounded-lg mr-2 bg-red-600 text-white hover:bg-red-700',
                    cancelButton: 'text-base font-semibold px-8 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600'
                },
                buttonsStyling: false
            }).then(r => {
                if (r.isConfirmed) {
                    const f = document.createElement('form');
                    f.action = url;
                    f.method = 'POST';
                    f.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(f);
                    f.submit();
                }
            });
        }
    </script>
</x-app-layout>