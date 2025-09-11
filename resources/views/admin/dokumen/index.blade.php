@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Sekretaris')

{{-- ALERTS --}}
@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () =>
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
    })
);
</script>
@endif

@if (session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () =>
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800',
            icon: 'text-red-600'
        },
        timer: 2500
    })
);
</script>
@endif

<x-app-layout>
    <section class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('admin.manajemendokumen.index') }}"
                        class="relative flex-1 sm:flex-none sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    {{-- Profil --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition>
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
            {{-- TABEL --}}
            <section class="xl:col-span-8">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-[#2171b8] text-white">
                                <th class="px-6 py-4 text-base font-semibold">Preview</th>
                                <th class="px-6 py-4 text-base font-semibold">Judul Dokumen</th>
                                <th class="px-6 py-4 text-base font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                            @php
                            $ext = strtolower(pathinfo($item->path_dokumen ?? '', PATHINFO_EXTENSION));
                            $isImg = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-blue-100 cursor-pointer transition group"
                                onclick="window.location='{{ route('admin.manajemendokumen.show', $item->id) }}'">

                                {{-- Preview --}}
                                <td class="px-6 py-4 group-hover:bg-[#e3f0fd]">
                                    <figure
                                        class="w-20 h-14 rounded-md overflow-hidden bg-gray-100 border grid place-items-center">
                                        @if($isImg)
                                        <img src="{{ asset('storage/'.$item->path_dokumen) }}"
                                            alt="{{ $item->nama_dokumen }}" class="object-cover w-full h-full" />
                                        @elseif($ext === 'pdf')
                                        <img src="{{ asset('assets/img/icon-pdf.svg') }}" alt="PDF"
                                            class="w-10 h-10 object-contain" />
                                        @elseif(in_array($ext, ['doc','docx']))
                                        <img src="{{ asset('assets/img/icon-word.svg') }}" alt="Word"
                                            class="w-10 h-10 object-contain" />
                                        @elseif(in_array($ext, ['xls','xlsx']))
                                        <img src="{{ asset('assets/img/icon-excel.svg') }}" alt="Excel"
                                            class="w-10 h-10 object-contain" />
                                        @else
                                        <img src="{{ asset('assets/img/default-file.svg') }}" alt="File"
                                            class="w-10 h-10 object-contain opacity-60" />
                                        @endif
                                    </figure>
                                </td>

                                {{-- Judul --}}
                                <td class="px-6 py-4 align-top group-hover:bg-[#e3f0fd]">
                                    <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}
                                    </p>
                                </td>

                                {{-- Kategori --}}
                                <td class="px-6 py-4 align-top group-hover:bg-[#e3f0fd]">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 align-top group-hover:bg-[#e3f0fd]">
                                    <div class="flex items-center justify-center gap-2"
                                        onclick="event.stopPropagation();">
                                        <a href="{{ route('admin.manajemendokumen.edit', $item->id) }}"
                                            class="w-9 h-9 grid place-items-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </a>

                                        <button type="button"
                                            class="w-9 h-9 grid place-items-center rounded bg-red-100 hover:bg-red-200 text-red-600 transition"
                                            title="Hapus"
                                            onclick="hapusDokumen('{{ route('admin.manajemendokumen.destroy', $item->id) }}')">
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

                {{-- PAGINATION (optional) --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-4 mt-8 xl:mt-0">
                {{-- Kartu Biru --}}
                <div class="rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 text-center">
                        <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon"
                            class="h-16 w-16 mx-auto mb-4">
                        <p class="font-bold text-lg leading-tight mb-1">
                            Role {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}
                        </p>
                        <p class="text-xs opacity-90">Upload dan simpan dokumen dengan mudah.</p>
                    </div>
                </div>

                {{-- Tombol --}}
                <a href="{{ route('admin.manajemendokumen.create') }}"
                    class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Dokumen
                </a>

                <button type="button" onclick="openKategoriModal()"
                    class="w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold px-5 py-2.5 shadow transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-folder-plus"></i>
                    Tambah Kategori
                </button>

                {{-- Card Kategori --}}
                <section class="bg-white rounded-2xl shadow-lg p-6 mt-2">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-gray-700">
                                {{ $kat->nama_kategoridokumen }} — {{ $kat->Subbidang->nama ?? '-' }}
                            </span>
                            <span class="flex gap-1">
                                <button type="button" onclick="openEditKategoriModal({{ $kat->id }})"
                                    class="w-7 h-7 grid place-items-center rounded hover:bg-yellow-100 text-yellow-600"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button"
                                    class="w-7 h-7 grid place-items-center rounded hover:bg-red-100 text-red-600"
                                    title="Hapus"
                                    onclick="hapusKategori('{{ route('admin.kategori-dokumen.destroy', $kat->id) }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </section>
            </aside>
        </main>

        {{-- MODAL: TAMBAH KATEGORI --}}
        <div id="kategoriModal" class="fixed inset-0 z-50 hidden bg-black/60">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8">
                <div
                    class="mx-auto mb-3 w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-300 grid place-items-center">
                    <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                </div>
                <h2 class="font-bold text-lg text-gray-800 mb-4 text-center">Tambah Kategori Dokumen</h2>

                <form action="{{ route('admin.kategori-dokumen.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="nama_kategori" id="nama_kategori"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3"
                        placeholder="Masukkan nama kategori" required>

                    <select name="bidang_id" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3">
                        <option value="" disabled selected>Pilih Bidang</option>
                        @foreach ($bidangList as $bidang)
                        <option value="{{ $bidang->id }}">{{ $bidang->nama }}</option>
                        @endforeach
                    </select>

                    <select name="subbidang_id" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3">
                        <option value="">(Opsional) Pilih Subbidang</option>
                        @foreach ($subbidangList as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" onclick="closeKategoriModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: EDIT KATEGORI --}}
        <div id="editKategoriModal" class="fixed inset-0 z-50 hidden bg-black/60">
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8">
                <div
                    class="mx-auto mb-3 w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-300 grid place-items-center">
                    <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                </div>
                <h2 class="font-bold text-lg text-gray-800 mb-4 text-center">Edit Kategori Dokumen</h2>

                <form id="editKategoriForm" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="text" name="nama_kategori" id="edit_nama_kategori"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3"
                        placeholder="Masukkan nama kategori" required>

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" onclick="closeEditKategoriModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                    </div>
                </form>
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
    </section>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script>
    // Modal helpers
    function openKategoriModal() {
        document.getElementById('kategoriModal').classList.remove('hidden');
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').classList.add('hidden');
    }

    function openEditKategoriModal(id) {
        fetch(`/sekretaris/kategori-dokumen/${id}/edit`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('edit_nama_kategori').value = data.nama_kategoridokumen;
                const form = document.getElementById('editKategoriForm');
                form.action = `/sekretaris/kategori-dokumen/${id}`;
                document.getElementById('editKategoriModal').classList.remove('hidden');
            })
            .catch(() => alert('Gagal mengambil data kategori.'));
    }

    function closeEditKategoriModal() {
        document.getElementById('editKategoriModal').classList.add('hidden');
    }
    // Klik backdrop untuk menutup
    ['kategoriModal', 'editKategoriModal'].forEach(id => {
        const el = document.getElementById(id);
        el?.addEventListener('click', e => {
            if (e.target === el) el.classList.add('hidden');
        });
    });

    // Util submit delete (CSRF + method spoofing)
    function submitDelete(url) {
        const f = document.createElement('form');
        f.action = url;
        f.method = 'POST';
        f.innerHTML = `
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(f);
        f.submit();
    }

    // === Modal delete Dokumen (sesuai desain) ===
    function hapusDokumen(url) {
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
            }
        }).then((res) => {
            if (res.isConfirmed) submitDelete(url);
        });
    }

    // === (Opsional) Samakan gaya untuk hapus kategori ===
    function hapusKategori(url) {
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
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-2xl px-8 py-8',
                title: 'text-2xl font-extrabold text-gray-900',
                htmlContainer: 'mt-1',
                actions: 'mt-6 flex justify-center gap-6',
                confirmButton: 'px-10 py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-lg font-semibold',
                cancelButton: 'px-10 py-3 rounded-2xl bg-[#2b6cb0] hover:bg-[#235089] text-white text-lg font-semibold'
            }
        }).then((res) => {
            if (res.isConfirmed) submitDelete(url);
        });
    }
    </script>
</x-app-layout>