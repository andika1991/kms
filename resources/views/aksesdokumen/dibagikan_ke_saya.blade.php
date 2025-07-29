@php
use Carbon\Carbon;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

// Ambil info user
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

// Prioritaskan role_id dulu, fallback ke role_group
$routePrefix = $prefixMap[$roleId] ?? $roleGroup ?? 'magang';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            📁 Dokumen Dibagikan ke Saya
        </h2>
        <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-md bg-red-100 text-red-700 border border-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if($dokumenDibagikan->count())
                <div class="overflow-x-auto rounded-md">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700 dark:text-gray-200">📄 Nama Dokumen</th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-700 dark:text-gray-200">📚 Kategori</th>
                                <th class="px-6 py-3 text-center text-sm font-bold text-gray-700 dark:text-gray-200">⚙️ Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($dokumenDibagikan as $dokumen)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                        {{ $dokumen->nama_dokumen }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                        {{ $dokumen->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="#"
                                           class="lihat-dokumen inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                           data-id="{{ $dokumen->id }}"
                                           data-rahasia="{{ $dokumen->kategoriDokumen->nama_kategoridokumen === 'Rahasia' ? '1' : '0' }}"
                                           data-route-prefix="{{ $routePrefix }}">
                                            🔍 Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $dokumenDibagikan->links('pagination::tailwind') }}
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-gray-500 dark:text-gray-400 text-lg">🚫 Belum ada dokumen yang dibagikan ke Anda.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal input kunci dokumen rahasia --}}
    <div id="modal-kunci" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen Rahasia</h3>
            <form id="form-kunci">
                @csrf
                <input type="hidden" name="dokumen_id" id="dokumen_id" data-route-prefix="">
                <input type="password" name="encrypted_key" placeholder="Kunci rahasia"
                       class="w-full rounded-lg border border-gray-300 mb-4 px-3 py-2" required>
                <div class="flex justify-end gap-2">
                    <button type="button" id="batal-modal" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>

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
                    window.location.href = `/${prefix}/manajemendokumen/${encodeURIComponent(dokumenId)}`;
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

            const url = `/${prefix}/manajemendokumen/${encodeURIComponent(id)}?encrypted_key=${encodeURIComponent(key)}`;
            window.location.href = url;
        });

        batalModal.addEventListener('click', () => {
            modal.classList.add('hidden');
            form.reset();
        });
    });
    </script>
</x-app-layout>
