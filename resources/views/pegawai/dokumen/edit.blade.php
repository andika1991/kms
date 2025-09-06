@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;

    $carbon = Carbon::now()->locale('id');
    $carbon->settings(['formatFunction' => 'translatedFormat']);
    $tanggal = $carbon->format('l, d F Y');

    $filePath = $manajemendokuman->path_dokumen ? ('storage/'.$manajemendokuman->path_dokumen) : null;
    $ext = $manajemendokuman->path_dokumen ? strtolower(pathinfo($manajemendokuman->path_dokumen, PATHINFO_EXTENSION)) : '';
    $isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
@endphp

@section('title', 'Edit Dokumen Pegawai')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] pb-12">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search dummy (biar konsisten dgn halaman lain) --}}
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari nama dokumen..."
                               class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition"/>
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                          <i class="fa fa-search"></i>
                        </span>
                    </div>

                    {{-- Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                                class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                                title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open=false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                             style="display:none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY GRID --}}
        <div class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- KOLOM UTAMA: PREVIEW + FORM (dalam satu kartu) --}}
            <section class="xl:col-span-8 w-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    {{-- Notifikasi validasi --}}
                    @if($errors->any())
                        <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 text-red-700 border border-red-200 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                        {{-- PREVIEW FILE --}}
                        <div class="flex flex-col">
                            <div class="rounded-2xl border bg-white overflow-hidden">
                                @if($filePath && Storage::disk('public')->exists($manajemendokuman->path_dokumen))
                                    @if($ext === 'pdf')
                                        <iframe src="{{ asset($filePath) }}" class="w-full h-[440px] md:h-[520px]" style="border:0;"></iframe>
                                    @elseif($isImage)
                                        <img src="{{ asset($filePath) }}" alt="Preview Dokumen" class="w-full h-[440px] md:h-[520px] object-contain bg-gray-50">
                                    @else
                                        <div class="h-[320px] md:h-[360px] flex items-center justify-center bg-gray-50">
                                            <i class="fa-solid fa-file text-6xl text-gray-400"></i>
                                        </div>
                                        <div class="px-4 py-3 text-xs text-gray-600 border-t">
                                            File saat ini: <a href="{{ asset($filePath) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Dokumen</a>
                                        </div>
                                    @endif
                                @else
                                    <div class="h-[320px] md:h-[360px] flex items-center justify-center bg-gray-50">
                                        <span class="text-gray-400 text-sm italic">Preview tidak tersedia.</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Tombol Ganti Dokumen (memicu input file asli) --}}
                            <div class="mt-4">
                                <button type="button" id="btn-ganti-file"
                                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#2B6CB0] hover:bg-[#1f4e86] text-white font-semibold shadow-sm transition">
                                    <i class="fa-solid fa-rotate"></i>
                                    Ganti Dokumen
                                </button>
                                @if($filePath && Storage::disk('public')->exists($manajemendokuman->path_dokumen))
                                    <span class="ml-3 text-sm text-gray-500">File saat ini:
                                        <a href="{{ asset($filePath) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Dokumen</a>
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengganti file.</p>
                            </div>
                        </div>

                        {{-- FORM EDIT (bawaan, tak ada logika yg diubah) --}}
                        <div>
                            <form id="form-edit-dokumen"
                                  method="POST"
                                  action="{{ route('pegawai.manajemendokumen.update', $manajemendokuman->id) }}"
                                  enctype="multipart/form-data"
                                  class="flex flex-col gap-5">
                                @csrf
                                @method('PATCH')

                                {{-- Nama Dokumen --}}
                                <div>
                                    <label class="block font-semibold text-gray-800 mb-1">Judul</label>
                                    <input type="text" name="nama_dokumen"
                                           value="{{ old('nama_dokumen', $manajemendokuman->nama_dokumen) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500" required>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block font-semibold text-gray-800 mb-1">Kategori</label>
                                    <select id="kategoriSelect" name="kategori_dokumen_id"
                                            class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id }}"
                                                    data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                                    {{ old('kategori_dokumen_id', $manajemendokuman->kategori_dokumen_id) == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategoridokumen }}
                                                @if($kat->subbidang) — {{ $kat->subbidang->nama }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- INPUT FILE ASLI (disembunyikan, dipicu tombol "Ganti Dokumen") --}}
                                <input type="file" name="path_dokumen" id="path_dokumen"
                                       class="hidden"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">

                                {{-- Deskripsi --}}
                                <div>
                                    <label class="block font-semibold text-gray-800 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="8"
                                              class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 resize-y"
                                              required>{{ old('deskripsi', $manajemendokuman->deskripsi) }}</textarea>
                                </div>

                                {{-- Encrypted Key (muncul hanya untuk Rahasia) --}}
                                <div id="encrypted-key-field"
                                     class="{{ strtolower(optional($manajemendokuman->kategoriDokumen)->nama_kategoridokumen) === 'rahasia' ? '' : 'hidden' }}">
                                    <label class="block font-semibold text-gray-800 mb-1">Kunci Rahasia / Encrypted Key</label>
                                    <input type="text" name="encrypted_key"
                                           value="{{ old('encrypted_key', $manajemendokuman->encrypted_key) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- Tombol submit asli tetap di dalam form (disembunyikan). --}}
                                <button type="submit" id="btn-submit-hidden" class="hidden">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8 mt-2 xl:mt-0">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">{{ Auth::user()->role->nama_role ?? 'Pegawai' }}</p>
                        <p class="text-xs">Perbarui judul, kategori, deskripsi, atau ganti file dokumennya.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row xl:flex-col items-center gap-4">
                    {{-- Tombol Update (pakai SweetAlert, lalu submit form) --}}
                    <button type="button" id="btn-update-dokumen"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Update</span>
                    </button>

                    {{-- Batalkan --}}
                    <a href="{{ route('pegawai.manajemendokumen.index') }}"
                       class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow-sm transition text-base">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Batalkan</span>
                    </a>
                </div>
            </aside>
        </div>

        {{-- FOOTER --}}
        <x-slot name="footer">
            <footer class="bg-[#2b6cb0] py-4 mt-8">
                <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                    <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
                </div>
            </footer>
        </x-slot>
    </div>

    {{-- SweetAlert2 (terbaru) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.3/dist/sweetalert2.all.min.js"></script>
    <script>
        // Trigger input file via tombol "Ganti Dokumen"
        document.getElementById('btn-ganti-file')?.addEventListener('click', () => {
            document.getElementById('path_dokumen')?.click();
        });

        // Toggle field kunci untuk kategori "Rahasia"
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategoriSelect');
            const keyField = document.getElementById('encrypted-key-field');

            function toggleKeyField() {
                const opt = kategoriSelect.options[kategoriSelect.selectedIndex];
                const nama = (opt?.getAttribute('data-nama') || '').toLowerCase();
                if (nama === 'rahasia') keyField.classList.remove('hidden');
                else keyField.classList.add('hidden');
            }

            kategoriSelect?.addEventListener('change', toggleKeyField);
            toggleKeyField();
        });

        // Konfirmasi Update dengan SweetAlert2
        document.getElementById('btn-update-dokumen')?.addEventListener('click', () => {
            Swal.fire({
                title: 'Simpan Perubahan?',
                html: 'Perubahan pada dokumen akan <b>disimpan</b>.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl p-8',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2 rounded-lg mr-2',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-2 rounded-lg',
                    actions: 'flex justify-center gap-4',
                },
                buttonsStyling: false,
            }).then((r) => {
                if (r.isConfirmed) {
                    document.getElementById('form-edit-dokumen')?.submit();
                }
            });
        });
    </script>
</x-app-layout>
