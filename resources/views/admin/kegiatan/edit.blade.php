@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Edit Kegiatan ')

<x-app-layout>
    {{-- HEADER --}}
    <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Kegiatan Kasubbidang</h2>
                <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex flex-col lg:flex-row gap-8 justify-center items-start min-h-[80vh] px-2 py-10 bg-[#eaf5ff]">
        <div class="w-full max-w-2xl mx-auto">
            <form id="form-kegiatan" method="POST" action="{{ route('admin.kegiatan.update', $kegiatan->id) }}" enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-xl px-7 py-8 flex flex-col gap-6">
                @csrf
                @method('PUT')

                {{-- Nama Kegiatan --}}
                <div>
                    <label for="nama_kegiatan" class="block font-semibold text-gray-700 mb-1">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                        value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>
                    @error('nama_kegiatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi_kegiatan" class="block font-semibold text-gray-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>{{ old('deskripsi_kegiatan', $kegiatan->deskripsi_kegiatan) }}</textarea>
                    @error('deskripsi_kegiatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori_kegiatan" class="block font-semibold text-gray-700 mb-1">Kategori Kegiatan</label>
                    <select name="kategori_kegiatan" id="kategori_kegiatan"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-3 py-2" required>
                        <option value="">Pilih Kategori</option>
                        <option value="publik" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'publik' ? 'selected' : '' }}>Publik</option>
                        <option value="internal" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'internal' ? 'selected' : '' }}>Internal</option>
                    </select>
                    @error('kategori_kegiatan')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Upload Foto Baru --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Upload Foto Kegiatan Baru (opsional, max 5)</label>
                    <label for="foto_kegiatan_input"
                        class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-blue-200 rounded-lg cursor-pointer hover:border-blue-500 bg-blue-50 transition">
                        <i class="fa fa-cloud-upload text-3xl text-blue-400 mb-1"></i>
                        <span class="text-blue-600 text-sm font-medium">Klik atau tarik foto ke sini</span>
                        <input type="file" id="foto_kegiatan_input" name="foto_kegiatan[]" multiple accept="image/*" class="hidden">
                    </label>
                    <div id="preview-foto" class="flex flex-wrap gap-4 mt-2"></div>
                    <div id="hidden-file-inputs"></div>
                    @error('foto_kegiatan')
                        <span class="text-red-500 text-xs mt-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Foto Lama --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Foto Kegiatan Saat Ini</label>
                    @if($kegiatan->fotokegiatan->count())
                        <div class="flex flex-wrap gap-4 mt-2">
                            @foreach($kegiatan->fotokegiatan as $foto)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $foto->path_foto) }}" class="h-20 w-20 object-cover rounded-lg border" alt="Foto Kegiatan">
                                    <a href="#" onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-foto-{{ $foto->id }}').submit();"
                                        class="absolute top-[-7px] right-[-7px] bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700">&times;</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mt-2">Belum ada foto kegiatan.</p>
                    @endif
                </div>

                @foreach($kegiatan->fotokegiatan as $foto)
                    <form id="delete-foto-{{ $foto->id }}" action="{{ route('admin.kegiatan.foto.delete', $foto->id) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

                {{-- Tombol --}}
                <div class="flex gap-3 mt-2">
                    <button id="btn-update-kegiatan" type="button"
                        class="flex-1 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-base">
                        <i class="fa-solid fa-save"></i> Update Perubahan
                    </button>
                    <button id="btn-cancel-kegiatan" type="button"
                        class="flex-1 px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white font-semibold shadow transition text-base text-center">
                        <i class="fa-solid fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- SIDEBAR --}}
        <div class="flex flex-col gap-6 w-full max-w-sm mx-auto mt-10 lg:mt-0">
            <div class="rounded-xl shadow-xl bg-gradient-to-r from-green-400 via-blue-500 to-blue-700 p-6 flex flex-col items-center">
                <i class="fa fa-tasks text-4xl mb-3 text-white drop-shadow"></i>
                <div class="font-bold text-lg text-white mb-1">Progress Kegiatan Kasubbidang</div>
                <div class="text-white text-sm opacity-90 text-center">
                    Dokumentasikan setiap aktivitas kerja, inovasi, knowledge sharing, pelatihan, dan kolaborasi tim di sini.
                </div>
            </div>
            <div class="rounded-xl shadow-lg bg-white p-6">
                <div class="font-semibold text-blue-700 mb-3">Tips Produktif Kasubbidang</div>
                <ul class="text-gray-700 text-sm space-y-1 pl-4 list-disc">
                    <li>Update laporan kegiatan secara berkala.</li>
                    <li>Unggah dokumentasi foto setiap aktivitas.</li>
                    <li>Laporkan kegiatan inovatif & kolaboratif.</li>
                    <li>Jaga kualitas dokumentasi pengetahuan.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- PREVIEW & UPLOAD VALIDATION --}}
    <script>
        let filesToUpload = [];

        const inputFile = document.getElementById('foto_kegiatan_input');
        const previewContainer = document.getElementById('preview-foto');
        const hiddenInputsContainer = document.getElementById('hidden-file-inputs');

        inputFile.addEventListener('change', (event) => {
            for (const file of event.target.files) {
                if (!file.type.startsWith('image/')) continue;

                if (filesToUpload.length >= 5) {
                    alert("Maksimal 5 foto yang dapat diunggah.");
                    break;
                }

                if (!filesToUpload.some(f => f.name === file.name && f.size === file.size)) {
                    filesToUpload.push(file);
                }
            }
            renderPreview();
            inputFile.value = '';
        });

        function renderPreview() {
            previewContainer.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            filesToUpload.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.classList.add('relative');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-24', 'w-24', 'object-cover', 'rounded-md', 'border');

                    const btnRemove = document.createElement('button');
                    btnRemove.type = 'button';
                    btnRemove.textContent = '×';
                    btnRemove.classList.add('absolute', 'top-0', 'right-0', 'bg-red-600', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center', 'hover:bg-red-700');
                    btnRemove.addEventListener('click', () => {
                        filesToUpload.splice(index, 1);
                        renderPreview();
                    });

                    div.appendChild(img);
                    div.appendChild(btnRemove);
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = 'foto_kegiatan[]';
                newInput.files = dataTransfer.files;
                newInput.style.display = 'none';
                hiddenInputsContainer.appendChild(newInput);
            });
        }
    </script>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik" class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Konfirmasi Simpan
            document.getElementById('btn-update-kegiatan').addEventListener('click', function () {
                Swal.fire({
                    icon: 'question',
                    title: 'Simpan Perubahan?',
                    text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                        cancelButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-kegiatan').submit();
                    }
                });
            });

            // Konfirmasi Batal
            document.getElementById('btn-cancel-kegiatan').addEventListener('click', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batalkan Edit?',
                    text: 'Perubahan tidak akan disimpan. Lanjutkan?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'bg-red-700 hover:bg-red-800 text-white font-semibold px-6 py-2 rounded-lg mx-2',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold px-6 py-2 rounded-lg mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.kegiatan.index') }}";
                    }
                });
            });
        });
    </script>
</x-app-layout>
