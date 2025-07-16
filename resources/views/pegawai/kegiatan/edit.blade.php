<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <form id="form-kegiatan" method="POST" action="{{ route('pegawai.kegiatan.update', $kegiatan->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kegiatan -->
                    <div class="mb-4">
                        <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Kegiatan
                        </label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                               value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}">
                        @error('nama_kegiatan')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    <div class="mb-4">
                        <label for="deskripsi_kegiatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Deskripsi Kegiatan
                        </label>
                        <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                                  rows="4">{{ old('deskripsi_kegiatan', $kegiatan->deskripsi_kegiatan) }}</textarea>
                        @error('deskripsi_kegiatan')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kategori Kegiatan -->
                    <div class="mb-4">
                        <label for="kategori_kegiatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kategori Kegiatan
                        </label>
                        <select name="kategori_kegiatan" id="kategori_kegiatan"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="publik" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'publik' ? 'selected' : '' }}>
                                Publik
                            </option>
                            <option value="internal" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'internal' ? 'selected' : '' }}>
                                Internal
                            </option>
                        </select>
                        @error('kategori_kegiatan')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input file (hidden) -->
                    <input type="file" id="foto_kegiatan_input" multiple accept="image/*" style="display:none" />

                    <!-- Tombol pilih foto -->
                    <div class="mb-4">
                        <button type="button" id="btn-pilih-foto"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Pilih Foto Kegiatan Baru
                        </button>
                    </div>

                    <!-- Preview foto baru -->
                    <div id="preview-foto" class="flex flex-wrap gap-4 mb-4"></div>

                    <!-- Hidden inputs container -->
                    <div id="hidden-file-inputs"></div>

                    <!-- Foto Lama -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto Kegiatan Saat Ini
                        </label>
                        @if($kegiatan->fotokegiatan->count())
                            <div class="flex flex-wrap gap-4 mt-2">
                                @foreach($kegiatan->fotokegiatan as $foto)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $foto->path_foto) }}"
                                             class="h-24 w-24 object-cover rounded border" alt="Foto Kegiatan">
                                        <a href="#"
                                           onclick="event.preventDefault(); if(confirm('Hapus foto ini?')) document.getElementById('delete-foto-{{ $foto->id }}').submit();"
                                           class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700">
                                            &times;
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 mt-2">Belum ada foto kegiatan.</p>
                        @endif
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('pegawai.kegiatan.index') }}"
                       class="ml-4 inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Batal
                    </a>
                </form>

                <!-- Form hapus foto (HARUS di luar form utama) -->
                @foreach($kegiatan->fotokegiatan as $foto)
                    <form id="delete-foto-{{ $foto->id }}" action="{{ route('magang.kegiatan.foto.delete', $foto->id) }}" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        let filesToUpload = [];

        const inputFile = document.getElementById('foto_kegiatan_input');
        const previewContainer = document.getElementById('preview-foto');
        const btnPilihFoto = document.getElementById('btn-pilih-foto');
        const hiddenInputsContainer = document.getElementById('hidden-file-inputs');

        btnPilihFoto.addEventListener('click', () => {
            inputFile.click();
        });

        inputFile.addEventListener('change', (event) => {
            for (const file of event.target.files) {
                if (!file.type.startsWith('image/')) continue;

                // Hindari duplikat
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
                    btnRemove.classList.add(
                        'absolute',
                        'top-0',
                        'right-0',
                        'bg-red-600',
                        'text-white',
                        'rounded-full',
                        'w-6',
                        'h-6',
                        'flex',
                        'items-center',
                        'justify-center',
                        'cursor-pointer',
                        'hover:bg-red-700'
                    );
                    btnRemove.addEventListener('click', () => {
                        filesToUpload.splice(index, 1);
                        renderPreview();
                    });

                    div.appendChild(img);
                    div.appendChild(btnRemove);
                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);

                // Hidden input agar file bisa dikirim
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
</x-app-layout>
