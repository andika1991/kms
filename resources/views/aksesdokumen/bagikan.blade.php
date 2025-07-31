@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Bagikan Dokumen')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col pb-10">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="hidden sm:flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="px-4 md:px-8 grid grid-cols-1 xl:grid-cols-12 gap-8 mt-8">
            {{-- FORM BAGIKAN --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 flex flex-col gap-7">
                    {{-- Judul --}}
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fa fa-users text-2xl text-[#2B6CB0]"></i>
                        <span class="text-xl sm:text-2xl font-bold text-gray-900">Pilih Pengguna yang Ingin Diberi Akses</span>
                    </div>
                    {{-- Form pencarian --}}
                    <form method="GET" action="{{ route('aksesdokumen.bagikan', $dokumen->id) }}" class="mb-7">
                        <div class="flex flex-col sm:flex-row gap-3 w-full">
                            <div class="flex-1 relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nama atau email pengguna..."
                                    class="w-full px-5 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 shadow-sm transition text-base pl-12" />
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                            <button type="submit"
                                class="flex items-center gap-2 bg-[#2563eb] hover:bg-[#174f97] text-white font-semibold rounded-xl px-7 py-3 shadow transition duration-200">
                                <i class="fa fa-search"></i>
                                Cari
                            </button>
                        </div>
                    </form>
                    {{-- Form user checkbox --}}
                    <form action="{{ route('aksesdokumen.bagikan.proses', $dokumen->id) }}" method="POST" class="flex flex-col gap-8">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            @foreach ($users as $user)
                            <label class="flex items-center gap-3 py-2 px-2 rounded-lg hover:bg-[#f4f9ff] transition">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    {{ in_array($user->id, $aksesUserIds) ? 'checked' : '' }}>
                                <span class="text-base text-gray-800">{{ $user->name }} <span class="text-gray-400">({{ $user->email }})</span></span>
                            </label>
                            @endforeach
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-[#2563eb] hover:bg-[#174f97] text-white font-semibold px-8 py-3 rounded-xl shadow transition duration-200">
                                <i class="fas fa-share-alt"></i>
                                <span>Bagikan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-8 xl:mt-0">
                <div class="bg-[#2563eb] rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center text-white">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <p class="font-bold text-lg leading-tight mb-2">
                        Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}
                    </p>
                    <p class="text-xs">Edit atau perbarui dokumen kegiatan maupun knowledge sharing di sini.</p>
                </div>
            </aside>
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
</x-app-layout>
