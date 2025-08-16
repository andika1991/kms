@php
    use Carbon\Carbon;
    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Tambah Manajemen Agenda Sekretaris')


<x-app-layout>
    <div class="bg-[#eaf5ff] min-h-screen w-full flex flex-col">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Agenda</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari Agenda..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
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

        <!-- BODY GRID -->
        <div class="flex flex-col lg:flex-row gap-8 px-4 md:px-12 pt-8 pb-10 flex-1 w-full max-w-7xl mx-auto">
            <!-- FORM AGENDA -->
            <form action="{{ route('sekretaris.agenda.store') }}" method="POST"
                  class="flex-1 max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-6 md:p-10 flex flex-col gap-8"
                  autocomplete="off" id="agendaForm">
                @csrf

                <!-- Nama Agenda -->
                <div>
                    <label class="block font-bold text-lg mb-2" for="nama_agenda">Nama Agenda</label>
                    <input id="nama_agenda" name="nama_agenda" type="text" required
                        class="w-full rounded-xl border border-gray-300 px-5 py-4 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base font-semibold" />
                </div>

                <!-- Tanggal & Waktu Mulai & Selesai -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block font-bold text-lg mb-2" for="date_agenda">Tanggal</label>
                        <input id="date_agenda" name="date_agenda" type="date" required
                            class="w-full rounded-xl border border-gray-300 px-5 py-4 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base" />
                    </div>
                    <div class="flex-1">
                        <label class="block font-bold text-lg mb-2" for="waktu_agenda">Waktu Mulai</label>
                        <input id="waktu_agenda" name="waktu_agenda" type="time" required
                            class="w-full rounded-xl border border-gray-300 px-5 py-4 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base" />
                    </div>
                    <div class="flex-1">
                        <label class="block font-bold text-lg mb-2" for="waktu_selesai">Waktu Selesai</label>
                        <input id="waktu_selesai" name="waktu_selesai" type="time" required
                            class="w-full rounded-xl border border-gray-300 px-5 py-4 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base" />
                    </div>
                </div>

                <!-- Pimpinan -->
                <div>
                    <label class="block font-bold text-lg mb-2" for="id_pengguna">Pimpinan (Kadis/Kepala Bagian)</label>
                    <select id="id_pengguna" name="id_pengguna" required
                        class="w-full rounded-xl border border-gray-300 px-5 py-4 bg-white shadow focus:outline-none focus:ring-2 focus:ring-blue-500 text-base">
                        <option value="">-- Pilih Pengguna --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->nama_role }})</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- SIDEBAR KANAN -->
            <aside class="w-full lg:w-80 flex flex-col gap-6 mt-8 lg:mt-0">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">Bidang {{ Auth::user()->role->nama_role ?? 'Sekretaris' }}</p>
                    </div>
                </div>

                <div class="flex gap-3 w-full">
                    <button type="submit" form="agendaForm"
                        class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        Tambah
                    </button>
                    <a href="{{ route('sekretaris.agenda.index') }}"
                       class="btn-cancel flex-1 px-6 py-3 rounded-xl bg-[#ad3a2c] hover:bg-[#992b1e] text-white font-semibold shadow transition text-base text-center">
                        Batalkan
                    </a>
                </div>
            </aside>
        </div>
    </div>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // KONFIRMASI SIMPAN (Hijau / Merah)
        const form = document.getElementById('agendaForm');
        if (form) {
            let allowSubmit = false;
            form.addEventListener('submit', function (e) {
                if (allowSubmit) return;
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda Yakin',
                    html: '<span class="font-semibold">perubahan akan disimpan</span>',
                    icon: 'success',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        icon: 'mt-0 mb-3',
                        title: 'mb-1',
                        htmlContainer: 'mb-3',
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2 rounded-lg text-base w-full sm:w-auto',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold px-10 py-2 rounded-lg text-base w-full sm:w-auto',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full'
                    }
                }).then((res) => {
                    if (res.isConfirmed) {
                        allowSubmit = true;
                        if (form.requestSubmit) form.requestSubmit();
                        else form.submit();
                    }
                });
            });
        }

        // KONFIRMASI BATAL (Biru / Biru)
        document.querySelectorAll('.btn-cancel').forEach((btn) => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href') || '#';

                Swal.fire({
                    title: 'Apakah Anda Yakin',
                    html: '<span class="font-semibold">perubahan tidak akan disimpan</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: 'Yakin',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl p-8',
                        icon: 'mt-0 mb-3',
                        title: 'mb-1',
                        htmlContainer: 'mb-3',
                        confirmButton: 'bg-[#1e4776] hover:bg-[#16355a] text-white font-semibold px-10 py-2 rounded-lg text-base w-full sm:w-auto',
                        cancelButton: 'bg-[#2563a9] hover:bg-[#1f4f86] text-white font-semibold px-10 py-2 rounded-lg text-base w-full sm:w-auto',
                        actions: 'flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 w-full'
                    }
                }).then((res) => {
                    if (res.isConfirmed) window.location.href = url;
                });
            });
        });
    });
    </script>
</x-app-layout>
