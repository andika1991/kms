@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
    $bidangList = [
        ['label' => 'Sekretariat', 'icon' => 'fa-solid fa-briefcase'],
        ['label' => 'PLIP', 'icon' => 'fa-solid fa-building-columns'],
        ['label' => 'PKP', 'icon' => 'fa-solid fa-lightbulb'],
        ['label' => 'TIK', 'icon' => 'fa-solid fa-network-wired'],
        ['label' => 'SanStik', 'icon' => 'fa-solid fa-database'],
    ];
@endphp

<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full">
        {{-- Kontainer max width --}}
        <div class="mx-auto max-w-[1520px] px-4 lg:px-10 pt-8 pb-10 flex flex-col xl:flex-row gap-8">
            {{-- SIDEBAR tetap fixed, grid utama tidak perlu ml-60! --}}

            {{-- Konten utama --}}
            <div class="flex-1">
                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-[28px] font-bold text-gray-900 mb-1">Kategori Pengetahuan</h2>
                        <p class="text-[#828282] text-base font-normal">{{ $tanggal }}</p>
                    </div>
                    <div class="flex items-center gap-4 mt-4 md:mt-0 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <input type="text" placeholder="Cari..."
                                class="w-full rounded-full border border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 shadow-none transition" />
                            <span class="absolute left-3 top-2.5 text-gray-400">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-black text-lg hover:shadow transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </a>
                    </div>
                </div>

                {{-- Card utama --}}
                <div class="bg-white rounded-2xl shadow px-0 pt-4 pb-2">
                    <div class="flex flex-col md:flex-row items-center justify-between px-8 pt-2 mb-2 gap-2">
                        <span class="font-bold text-lg text-[#2176ae] border-b-4 border-[#2176ae] pb-2">
                            Pengetahuan
                        </span>
                        <div class="flex items-center gap-2">
                            <button class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-1.5 bg-white hover:bg-blue-50 text-gray-700 font-medium shadow-none transition text-sm">
                                Urut <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <a href="{{ route('kepalabagian.kategoripengetahuan.create') }}"
                                class="px-5 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-none transition ml-2 text-sm">
                                + Tambah Kategori
                            </a>
                        </div>
                    </div>
                    <div class="px-2 pb-4">
                        @if ($kategori->count())
                            <div>
                                <table class="w-full text-sm text-left text-gray-600">
                                    <thead class="text-xs text-gray-700 uppercase bg-[#f3f7fb]">
                                        <tr>
                                            <th class="py-3 px-6 font-bold text-left">Judul</th>
                                            <th class="py-3 px-6 font-bold text-left">Tanggal</th>
                                            <th class="py-3 px-6 font-bold text-left">Keterangan</th>
                                            <th class="py-3 px-6 font-bold text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori as $item)
                                        <tr class="{{ $loop->odd ? 'bg-[#f3f7fb]' : 'bg-white' }} hover:bg-blue-50 transition">
                                            <td class="py-3 px-6">{{ $item->judul ?? '-' }}</td>
                                            <td class="py-3 px-6">{{ $item->tanggal ?? '-' }}</td>
                                            <td class="py-3 px-6">{{ $item->keterangan ?? '-' }}</td>
                                            <td class="py-3 px-6 flex gap-2">
                                                <a href="{{ route('kepalabagian.kategoripengetahuan.edit', $item->id) }}"
                                                    class="px-3 py-1 rounded bg-yellow-400 hover:bg-yellow-500 text-xs text-black font-bold">Edit</a>
                                                <form class="inline"
                                                    action="{{ route('kepalabagian.kategoripengetahuan.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-xs text-white font-bold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-20 px-6">
                                <img src="{{ asset('img/empty.svg') }}" class="w-32 mb-4 opacity-80" alt="Data Kosong" />
                                <h3 class="text-xl font-bold text-gray-700">Data Tidak Ditemukan</h3>
                                <p class="text-gray-500 mt-2">Saat ini belum ada data kategori pengetahuan yang tersedia.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SIDEBAR kanan --}}
            <div class="w-full xl:w-[370px] flex flex-col gap-8">
                <div class="bg-[#3773a6] text-white rounded-2xl shadow p-8 flex flex-col">
                    <div class="font-bold text-lg mb-2 pb-2 border-b border-white/20">Bidang</div>
                    <ul class="space-y-6 mt-2">
                        @foreach ($bidangList as $bidang)
                        <li class="flex items-center gap-4 text-white/90 hover:text-white transition cursor-pointer">
                            <span class="w-8 flex justify-center items-center">
                                <i class="{{ $bidang['icon'] }} text-2xl"></i>
                            </span>
                            <span class="text-base font-medium">{{ $bidang['label'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white rounded-2xl shadow p-7">
                    <div class="font-semibold text-blue-700 mb-2 text-lg">Kategori</div>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Dashboard Manajemen Pengetahuan Diskominfotik ini dirancang untuk menjadi pusat integrasi informasi dan dokumentasi strategis bagi seluruh pegawai di lingkungan instansi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
