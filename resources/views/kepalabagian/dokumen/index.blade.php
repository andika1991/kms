@php
use Carbon\Carbon;
use App\Models\KategoriDokumen;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

/** Kepala Bagian tidak membuat kategori → hanya tampilkan daftar sebagai sidebar */
$kategori = KategoriDokumen::orderBy('nama_kategoridokumen')->get();
@endphp

@section('title', 'Manajemen Dokumen Kepala Bagian')

{{-- ALERT (SweetAlert2) --}}
@if (session('success') || session('deleted'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const msg = @json(session('success') ?? session('deleted'));
    const isDel = {
        {
            session() - > has('deleted') ? 'true' : 'false'
        }
    };
    Swal.fire({
        position: 'top',
        icon: isDel ? 'error' : 'success',
        title: msg,
        showConfirmButton: false,
        timer: isDel ? 2500 : 2200,
        background: isDel ? '#fff0f0' : '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5',
            title: `font-bold text-base md:text-lg ${isDel ? 'text-red-800' : 'text-green-800'}`
        }
    });
});
</script>
@endif

<x-app-layout>
    <div class="min-h-screen bg-[#eaf5ff]">

        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('kepalabagian.manajemendokumen.index') }}"
                        class="w-full md:w-72">
                        <div class="relative">
                            <input name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                                class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </form>

                    {{-- Profile dropdown --}}
                    <div x-data="{open:false}" class="relative">
                        <button type="button" @click="open=!open" class="w-10 h-10 grid place-items-center bg-white rounded-full border border-gray-300
                 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open=false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20 hidden">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                    Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- NOTIFIKASI ERROR SERVER-SIDE --}}
        @if(session('error'))
        <p class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold
                shadow-md border border-red-300">{{ session('error') }}</p>
        @endif
        @if($errors->any())
        <div class="mx-6 md:mx-8 mt-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold
                  shadow-md border border-red-300">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">

            {{-- KOLOM UTAMA --}}
            <section class="xl:col-span-8">
                <a href="{{ route('dokumen.dibagikan.ke.saya') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 mb-3 rounded-xl
            bg-[#2d74bb] hover:bg-[#1f5d97] text-white font-semibold shadow-sm transition">
                    <i class="fa-solid fa-share-from-square"></i>
                    <span>Dokumen Dibagikan ke Saya</span>
                </a>
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
                            $rahasia = ($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen ===
                            'Rahasia');
                            @endphp
                            <tr class="row-dokumen border-b border-gray-100 hover:bg-[#f5f9ff] cursor-pointer transition"
                                data-id="{{ $item->id }}" data-rahasia="{{ $rahasia ? '1' : '0' }}">
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex w-20 h-14 items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage && $filePath)
                                        <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover">
                                        @else
                                        <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Lihat (rahasiakan via modal) --}}
                                        @if ($rahasia)
                                        <button type="button"
                                            class="js-no-row w-9 h-9 grid place-items-center rounded bg-blue-100 hover:bg-blue-200 text-blue-600"
                                            title="Lihat" onclick="openRahasia({{ $item->id }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        @else
                                        <a class="js-no-row w-9 h-9 grid place-items-center rounded bg-blue-100 hover:bg-blue-200 text-blue-600"
                                            href="{{ route('kepalabagian.manajemendokumen.show', $item->id) }}"
                                            title="Lihat">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @endif

                                        {{-- Edit --}}
                                        <a href="{{ route('kepalabagian.manajemendokumen.edit', $item->id) }}"
                                            class="js-no-row w-9 h-9 grid place-items-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600"
                                            title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>

                                        {{-- Hapus --}}
                                        <form action="{{ route('kepalabagian.manajemendokumen.destroy', $item->id) }}"
                                            method="POST" onsubmit="return confirm('Hapus dokumen ini?');"
                                            class="inline-block js-no-row">
                                            @csrf @method('DELETE')
                                            <button
                                                class="w-9 h-9 grid place-items-center rounded bg-red-100 hover:bg-red-200 text-red-600"
                                                title="Hapus"><i class="fa-solid fa-trash"></i></button>
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

                {{-- PAGINATION (jika dipakai) --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>
            </section>

            {{-- MODAL KUNCI RAHASIA --}}
            <section id="modal-kunci" class="fixed inset-0 bg-black/40 hidden z-50 items-center justify-center">
                <form id="form-kunci" class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                    @csrf
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
                    <input type="hidden" name="dokumen_id" id="dokumen_id">
                    <input type="password" name="encrypted_key" required placeholder="Kunci rahasia"
                        class="w-full rounded-lg border-gray-300 mb-4">
                    <div class="flex justify-end gap-2">
                        <button type="button" id="batal-modal"
                            class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Batal</button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Lanjutkan</button>
                    </div>
                </form>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <section
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center grid place-items-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight mb-2">
                        {{ Auth::user()->role->nama_role ?? 'Kepala Bagian' }}</p>
                    <p class="text-xs opacity-90">Unggah, simpan, dan tinjau dokumen kerja di sini.</p>
                </section>

                <a href="{{ route('kepalabagian.manajemendokumen.create') }}" class="w-full h-12 md:h-[52px] rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white
          font-semibold px-5 shadow transition flex items-center justify-center gap-2 text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Dokumen</span>
                </a>

                {{-- Daftar Kategori (read-only; tidak ada tambah/edit/hapus) --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-semibold text-blue-800 mb-3 text-lg border-b pb-2">Kategori Dokumen</h3>
                    @if($kategori->count())
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm text-gray-700">{{ $kat->nama_kategoridokumen }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-sm text-gray-500">Belum ada kategori terdaftar.</p>
                    @endif
                </section>
            </aside>
        </main>

        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 grid place-items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                        class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- Interaksi baris & modal rahasia (persis pola pegawai, rute kepala bagian) --}}
    <script>
    function openRahasia(id) {
        document.getElementById('dokumen_id').value = id;
        document.getElementById('modal-kunci').classList.remove('hidden');
        document.getElementById('modal-kunci').classList.add('flex');
    }
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('tr.row-dokumen');
        const modal = document.getElementById('modal-kunci');
        const form = document.getElementById('form-kunci');
        const batal = document.getElementById('batal-modal');

        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                if (e.target.closest('.js-no-row')) return; // cegah trigger dari tombol aksi
                const id = row.dataset.id;
                const isRahasia = row.dataset.rahasia === '1';
                if (isRahasia) {
                    openRahasia(id);
                } else {
                    window.location.href = `/kepalabagian/manajemendokumen/${id}`;
                }
            });
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const key = form.encrypted_key.value;
            const id = document.getElementById('dokumen_id').value;
            window.location.href =
                `/kepalabagian/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(key)}`;
        });

        batal.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            form.reset();
        });
    });
    </script>
</x-app-layout>