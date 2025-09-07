@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Kasubbidang')

{{-- Toast sukses / hapus (jalan setelah semua resource termuat) --}}
@if (session('success'))
<script>
window.addEventListener('load', () => {
    Swal?.fire({
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

@if (session('deleted'))
<script>
window.addEventListener('load', () => {
    Swal?.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fef2f2',
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
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('kasubbidang.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20"
                            style="display:none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
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
            <section class="xl:col-span-8 w-full">
                <a href="{{ route('dokumen.dibagikan.ke.saya') }}"
                    class="mt-4 mb-6 flex items-center justify-center gap-2 w-full px-0 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base text-center">
                    <i class="fa-solid fa-share-from-square text-lg"></i>
                    <span class="text-base font-semibold whitespace-nowrap">Dokumen Dibagikan ke Saya</span>
                </a>

                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-[#2171b8] text-white">
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
                            $iconPath = match(true) {
                            $isImage => $filePath,
                            $extension === 'pdf' => asset('assets/img/icon-pdf.svg'),
                            in_array($extension, ['doc','docx']) => asset('assets/img/icon-word.svg'),
                            in_array($extension, ['xls','xlsx']) => asset('assets/img/icon-excel.svg'),
                            default => asset('assets/img/default-file.svg'),
                            };
                            @endphp

                            <tr class="@if($loop->even) bg-[#eaf3fa] @endif border-b border-gray-100 group hover:bg-[#d6eaff] transition cursor-pointer"
                                onclick="window.location='{{ route('kasubbidang.manajemendokumen.show', $item->id) }}'">
                                <td class="px-6 py-4 flex items-center gap-4 min-w-[240px]">
                                    {{-- Preview --}}
                                    <figure
                                        class="flex-shrink-0 w-[90px] h-[62px] bg-gray-100 rounded-lg border grid place-items-center overflow-hidden">
                                        @if($item->thumbnail && file_exists(public_path('storage/'.$item->thumbnail)))
                                        <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="preview"
                                            class="object-cover w-full h-full" />
                                        @elseif($isImage)
                                        <img src="{{ $filePath }}" alt="preview" class="object-cover w-full h-full" />
                                        @else
                                        <img src="{{ $iconPath }}" alt="icon" class="object-contain w-12 h-12" />
                                        @endif
                                    </figure>

                                    {{-- Judul & deskripsi singkat --}}
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate">{{ $item->nama_dokumen }}</div>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <span
                                        class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm whitespace-nowrap">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 flex items-center gap-2 justify-center align-middle"
                                    onclick="event.stopPropagation();">
                                    <a href="{{ route('kasubbidang.manajemendokumen.edit', $item->id) }}"
                                        class="w-10 h-10 grid place-items-center rounded-full bg-yellow-100 hover:bg-yellow-200 transition"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-yellow-500 text-lg"></i>
                                    </a>

                                    <form id="form-hapus-{{ $item->id }}"
                                        action="{{ route('kasubbidang.manajemendokumen.destroy', $item->id) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="showHapusModal({{ $item->id }}); event.stopPropagation();"
                                            class="w-10 h-10 grid place-items-center rounded-full bg-red-100 hover:bg-red-200 transition"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash text-red-600 text-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION (aktifkan jika memakai paginator) --}}
                {{-- <div class="mt-4">{{ $dokumen->links() }}
    </div> --}}
    </section>

    {{-- SIDEBAR --}}
    <aside class="xl:col-span-4 w-full flex flex-col gap-8">
        <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 text-center">
            <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" class="h-16 w-16 mx-auto mb-4" alt="Role Icon">
            <p class="font-bold text-lg leading-tight mb-2">Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
            </p>
            <p class="text-xs opacity-90">Upload dan simpan dokumen kegiatan maupun knowledge sharing di sini.
            </p>
        </section>

        <a href="{{ route('kasubbidang.manajemendokumen.create') }}"
            class="block w-full text-center rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold text-[16px] py-2.5 shadow transition">
            <i class="fa-solid fa-plus"></i> <span>Tambah Dokumen</span>
        </a>

        <button type="button" onclick="openKategoriModal()"
            class="block w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold text-[16px] py-2.5 shadow transition -mt-1">
            <i class="fa-solid fa-folder-plus"></i> <span>Tambah Kategori</span>
        </button>

        {{-- Card Kategori --}}
        <section class="bg-white rounded-[16px] shadow p-5">
            <h3 class="text-[#2563a9] font-semibold text-[15px] mb-2">Kategori Dokumen</h3>
            <ul class="flex flex-col gap-2">
                @foreach($kategori as $kat)
                <li class="flex items-center justify-between group px-1 py-1 rounded transition">
                    <span class="text-[15px] text-[#232323] leading-5 group-hover:font-semibold">
                        {{ $kat->nama_kategoridokumen }}
                    </span>
                    <span class="flex gap-1 opacity-80 group-hover:opacity-100">
                        <button type="button"
                            onclick="openEditKategoriModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategoridokumen) }}')"
                            class="px-2 py-1 rounded hover:bg-yellow-100" title="Edit">
                            <i class="fa-solid fa-pen text-yellow-500 text-xs"></i>
                        </button>

                        <form action="{{ route('kasubbidang.kategori-dokumen.destroy', $kat->id) }}" method="POST"
                            class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                class="px-2 py-1 rounded hover:bg-red-100" title="Hapus">
                                <i class="fa-solid fa-trash text-red-600 text-xs"></i>
                            </button>
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </section>
    </aside>

    {{-- MODAL: Edit Kategori --}}
    <div id="editKategoriModal" class="fixed inset-0 z-50 hidden bg-black/40 place-items-center">
        <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
            <h2 class="font-bold text-lg text-gray-800 mb-4 text-center">Edit Kategori Dokumen</h2>
            <form id="editKategoriForm" method="POST" class="w-full">
                @csrf @method('PUT')
                <input type="text" name="nama_kategori" id="edit_nama_kategori"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center mb-4"
                    placeholder="Masukkan nama kategori" required>
                <div class="flex w-full gap-2 mt-2 justify-end">
                    <button type="button" onclick="closeEditKategoriModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Tambah Kategori --}}
    <div id="kategoriModal" class="fixed inset-0 z-50 hidden bg-black/60 place-items-center">
        <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
            <div class="flex flex-col items-center mb-3">
                <div
                    class="rounded-full bg-gradient-to-br from-blue-500 to-blue-300 w-16 h-16 grid place-items-center mb-2">
                    <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                </div>
                <h2 class="font-bold text-lg text-gray-800 mb-2 text-center">Tambah Kategori Dokumen</h2>
            </div>
            <form action="{{ route('kasubbidang.kategori-dokumen.store') }}" method="POST"
                class="w-full flex flex-col items-center gap-4">
                @csrf
                <input type="text" name="nama_kategori" id="nama_kategori"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                    placeholder="Masukkan nama kategori" required>
                <div class="flex w-full gap-2 mt-2 justify-end">
                    <button type="button" onclick="closeKategoriModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Password Dokumen --}}
    <div id="passwordModal" class="fixed inset-0 z-50 hidden bg-black/50 place-items-center">
        <div class="bg-white rounded-xl shadow-lg p-6 w-80 max-w-full">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen</h3>
            <input id="modalPasswordInput" type="password" placeholder="Kunci Dokumen"
                class="w-full border border-gray-300 rounded-md px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <input type="hidden" id="dokumenId" />
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold">Batal</button>
                <button type="button" onclick="submitPassword()"
                    class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold">Submit</button>
            </div>
        </div>
    </div>
    </main>
    </div>

    {{-- SweetAlert2 (selalu tersedia) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>

    <script>
    // Tambah Kategori
    function openKategoriModal() {
        const m = document.getElementById('kategoriModal');
        m.classList.remove('hidden');
        m.classList.add('grid');
    }

    function closeKategoriModal() {
        const m = document.getElementById('kategoriModal');
        m.classList.add('hidden');
        m.classList.remove('grid');
    }

    // Edit Kategori
    function openEditKategoriModal(id, nama) {
        document.getElementById('edit_nama_kategori').value = nama;
        document.getElementById('editKategoriForm').action = '/kasubbidang/kategori-dokumen/' + id;
        const m = document.getElementById('editKategoriModal');
        m.classList.remove('hidden');
        m.classList.add('grid');
    }

    function closeEditKategoriModal() {
        const m = document.getElementById('editKategoriModal');
        m.classList.add('hidden');
        m.classList.remove('grid');
    }

    // Password Dokumen
    function showPasswordModal(dokumenId) {
        document.getElementById('dokumenId').value = dokumenId;
        document.getElementById('modalPasswordInput').value = '';
        const m = document.getElementById('passwordModal');
        m.classList.remove('hidden');
        m.classList.add('grid');
    }

    function closeModal() {
        const m = document.getElementById('passwordModal');
        m.classList.add('hidden');
        m.classList.remove('grid');
    }

    function submitPassword() {
        const dokumenId = document.getElementById('dokumenId').value;
        const password = document.getElementById('modalPasswordInput').value.trim();
        if (!password) {
            alert('Kunci tidak boleh kosong.');
            return;
        }
        const url = `/kasubbidang/manajemendokumen/${dokumenId}?encrypted_key=${encodeURIComponent(password)}`;
        window.location.href = url;
    }

    // Hapus Dokumen
    function showHapusModal(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus dokumen ini?',
            text: 'Data yang sudah dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((res) => {
            if (res.isConfirmed) document.getElementById('form-hapus-' + id).submit();
        });
    }
    </script>
</x-app-layout>