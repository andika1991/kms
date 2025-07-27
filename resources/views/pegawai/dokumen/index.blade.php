@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Pegawai')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen Pegawai</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('pegawai.manajemendokumen.index') }}" class="relative flex-grow sm:flex-grow-0 sm:w-64">
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
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@if (session('error'))
    <div class="mb-4 px-4 py-3 rounded-md bg-red-100 text-red-700 text-sm border border-red-300">
        {{ session('error') }}
    </div>
@endif

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            {{-- KOLOM UTAMA (DAFTAR DOKUMEN) --}}
            <section class="xl:col-span-8 w-full">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-lg text-[#2171b8]">Daftar Dokumen Pegawai</span>
                    <a href="{{ route('pegawai.manajemendokumen.create') }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Dokumen</span>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-gray-100">
                                <th class="px-6 py-4 text-base font-semibold">Nama Dokumen</th>
                                <th class="px-6 py-4 text-base font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($dokumen as $item)
                            <tr class="@if($loop->even) bg-[#eaf3fa] @endif border-b border-gray-100">
                                <td class="flex items-center gap-4 px-6 py-4">
                                    {{-- Thumbnail --}}
                                    <div class="w-20 h-14 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($item->thumbnail)
                                            <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->nama_dokumen }}" class="object-cover w-full h-full" />
                                        @else
                                            <img src="{{ asset('assets/img/default-file.svg') }}" alt="No Image" class="object-contain w-10 h-10 opacity-60" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex items-center gap-2 justify-center">
                                   <a href="#" 
   class="lihat-dokumen px-4 py-1.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold transition text-sm"
   data-id="{{ $item->id }}"
   data-rahasia="{{ $item->kategoriDokumen->nama_kategoridokumen === 'Rahasia' ? '1' : '0' }}">
   Lihat
</a>

                                    <a href="{{ route('pegawai.manajemendokumen.edit', $item->id) }}"
                                       class="px-4 py-1.5 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition text-sm">Edit</a>
                                    <form action="{{ route('pegawai.manajemendokumen.destroy', $item->id) }}"
                                          method="POST" onsubmit="return confirm('Hapus dokumen ini?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-1.5 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.</td>
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

            <div id="modal-kunci" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
        <form id="form-kunci">
            @csrf
            <input type="hidden" name="dokumen_id" id="dokumen_id">
            <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                class="w-full rounded-lg border-gray-300 mb-4" required>
            <div class="flex justify-end gap-2">
                <button type="button" id="batal-modal" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Lanjutkan</button>
            </div>
        </form>
    </div>
</div>

            {{-- KOLOM SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}</p>
                        <p class="text-xs">Upload, simpan, dan kelola dokumen pengetahuan, pelatihan, atau dokumentasi kerja pegawai di sini.</p>
                    </div>
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

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const lihatButtons = document.querySelectorAll('.lihat-dokumen');
    const modal = document.getElementById('modal-kunci');
    const form = document.getElementById('form-kunci');
    const dokumenIdInput = document.getElementById('dokumen_id');
    const batalModal = document.getElementById('batal-modal');

    let dokumenId = null;

    lihatButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            dokumenId = this.dataset.id;

            if (this.dataset.rahasia === '1') {
                // Tampilkan modal input kunci
                dokumenIdInput.value = dokumenId;
                modal.classList.remove('hidden');
            } else {
                // Jika bukan rahasia, langsung redirect
                window.location.href = `/pegawai/manajemendokumen/${dokumenId}`;
            }
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const key = form.encrypted_key.value;
        const id = dokumenIdInput.value;

        const url = `/pegawai/manajemendokumen/${id}?encrypted_key=${encodeURIComponent(key)}`;
        window.location.href = url;
    });

    batalModal.addEventListener('click', function () {
        modal.classList.add('hidden');
        form.reset();
    });
});
</script>

</x-app-layout>