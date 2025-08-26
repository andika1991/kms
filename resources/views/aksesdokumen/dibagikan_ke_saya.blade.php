@php
use Carbon\Carbon;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

$user = Auth::user();
$roleId = $user->role_id;
$roleGroup = strtolower($user->role->role_group ?? 'magang');

$prefixMap = [
1 => 'admin',
2 => 'sekretaris',
3 => 'kasubbidang',
4 => 'magang',
5 => 'kepalabagian',
6 => 'pegawai',
];

$routePrefix = $prefixMap[$roleId] ?? $roleGroup ?? 'magang';
@endphp

<x-app-layout>
    {{-- Header ala figma --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
            <div>
                <h2 class="font-bold text-2xl sm:text-3xl text-gray-800">Manajemen Dokumen</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
            </div>
            {{-- (opsional) kolom pencarian bisa ditaruh di header layout utama app --}}
        </div>
    </x-slot>

    <div class="px-4 md:px-8 max-w-7xl mx-auto pb-10">
        {{-- breadcrumb kecil seperti figma --}}
        <nav class="text-sm text-gray-400 mb-4">
            <span>Dokumen</span>
            <span class="mx-1">></span>
            <span>Daftar Pengetahuan</span>
            <span class="mx-1">></span>
            <span class="text-gray-600 font-medium">Dokumen Dibagikan</span>
        </nav>

        <div class="bg-white shadow-lg rounded-2xl border border-gray-200">
            @if(session('error'))
            <div class="mx-6 mt-6 mb-0 px-4 py-3 rounded-xl bg-red-100 text-red-700 border border-red-300">
                {{ session('error') }}
            </div>
            @endif

            @if($dokumenDibagikan->count())
            {{-- Table wrapper --}}
            <div class="overflow-x-auto mt-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-[#2B6CB0] text-white">
                            <th class="px-6 py-4 text-left text-sm font-semibold rounded-tl-2xl">Judul Dokumen</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kategori</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold rounded-tr-2xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dokumenDibagikan as $dokumen)
                        <tr class="odd:bg-gray-50 hover:bg-[#f4f9ff] transition">
                            <td class="px-6 py-4 text-gray-900 whitespace-nowrap">
                                {{ $dokumen->nama_dokumen }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="#"
                                    class="lihat-dokumen inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-[#22aee6] hover:bg-[#159bd0] text-white text-sm font-semibold shadow"
                                    data-id="{{ $dokumen->id }}"
                                    data-rahasia="{{ $dokumen->kategoriDokumen->nama_kategoridokumen === 'Rahasia' ? '1' : '0' }}"
                                    data-route-prefix="{{ $routePrefix }}">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-6">
                {{ $dokumenDibagikan->links('pagination::tailwind') }}
            </div>
            @else
            {{-- Empty state versi figma --}}
            <div class="px-6 py-14">
                <div
                    class="rounded-2xl border-2 border-dashed border-slate-300/80 bg-slate-50 p-10 sm:p-14 flex flex-col items-center justify-center text-center shadow-inner">
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-white shadow mb-4">
                        <i class="fa-regular fa-folder-open text-2xl text-slate-500"></i>
                    </div>
                    <p class="text-slate-600 text-base sm:text-lg">
                        Belum ada dokumen yang dibagikan ke Anda.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal input kunci (tanpa ubah logika) --}}
    <div id="modal-kunci" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
            <form id="form-kunci">
                @csrf
                <input type="hidden" name="dokumen_id" id="dokumen_id" data-route-prefix="">
                <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                    class="w-full rounded-xl border border-gray-300 mb-4 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
                <div class="flex justify-end gap-2">
                    <button type="button" id="batal-modal"
                        class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-[#2563eb] hover:bg-[#174f97] text-white font-semibold">Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script asli (tanpa perubahan logika) --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const lihatButtons = document.querySelectorAll('.lihat-dokumen');
        const modal = document.getElementById('modal-kunci');
        const form = document.getElementById('form-kunci');
        const dokumenIdInput = document.getElementById('dokumen_id');
        const batalModal = document.getElementById('batal-modal');

        lihatButtons.forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault();
                const dokumenId = button.dataset.id;
                const rahasia = button.dataset.rahasia === '1';
                const prefix = button.dataset.routePrefix || 'magang';

                if (rahasia) {
                    dokumenIdInput.value = dokumenId;
                    dokumenIdInput.dataset.routePrefix = prefix;
                    modal.classList.remove('hidden');
                } else {
                    window.location.href =
                        `/${prefix}/manajemendokumen/${encodeURIComponent(dokumenId)}`;
                }
            });
        });

        form.addEventListener('submit', e => {
            e.preventDefault();
            const key = form.encrypted_key.value.trim();
            const id = dokumenIdInput.value;
            const prefix = dokumenIdInput.dataset.routePrefix || 'magang';

            if (!key) {
                alert('Kunci tidak boleh kosong!');
                return;
            }

            const url =
                `/${prefix}/manajemendokumen/${encodeURIComponent(id)}?encrypted_key=${encodeURIComponent(key)}`;
            window.location.href = url;
        });

        batalModal.addEventListener('click', () => {
            modal.classList.add('hidden');
            form.reset();
        });
    });
    </script>
</x-app-layout>