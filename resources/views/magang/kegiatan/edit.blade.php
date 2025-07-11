<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <form method="POST" 
                      action="{{ route('magang.kegiatan.update', $kegiatan->id) }}" 
                      enctype="multipart/form-data">
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
                            <option value="publik" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'publik' ? 'selected' : '' }}>Publik</option>
                            <option value="internal" {{ old('kategori_kegiatan', $kegiatan->kategori_kegiatan) == 'internal' ? 'selected' : '' }}>Internal</option>
                        </select>
                        @error('kategori_kegiatan')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

   

                    <!-- Tambah Foto Baru -->
           <!-- Tambah Foto Baru -->
<div class="mb-4">
    <label for="foto_kegiatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Tambah Foto Kegiatan (boleh lebih dari 1)
    </label>
    <input type="file" name="foto_kegiatan[]" id="foto_kegiatan" multiple accept="image/*"
           class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
    <div id="preview-foto-baru" class="flex flex-wrap gap-4 mt-4"></div>
    @error('foto_kegiatan.*')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>


                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('magang.kegiatan.index') }}"
                       class="ml-4 inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Batal
                    </a>
                </form>
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
                                        <a href=""
                                           onclick="return confirm('Hapus foto ini?')"
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
            </div>
        </div>
    </div>

    <script>
    const fileInput = document.getElementById('foto_kegiatan');
    const previewContainer = document.getElementById('preview-foto-baru');

    let filesArray = [];

    fileInput.addEventListener('change', function(e) {
        const newFiles = Array.from(e.target.files);

        newFiles.forEach(file => {
            // Cek duplikat berdasarkan nama & ukuran
            if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
                filesArray.push(file);
            }
        });

        updatePreview();

        // Reset input supaya bisa pilih file yang sama lagi
        fileInput.value = '';
    });

    function updatePreview() {
        previewContainer.innerHTML = '';

        filesArray.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.classList.add('relative');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('h-24', 'w-24', 'object-cover', 'rounded', 'border');

                const btn = document.createElement('button');
                btn.textContent = '×';
                btn.type = 'button';
                btn.className = 'absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700';
                btn.addEventListener('click', () => {
                    filesArray.splice(index, 1);
                    updatePreview();
                });

                div.appendChild(img);
                div.appendChild(btn);
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        syncFileInput();
    }

    function syncFileInput() {
        // Buat DataTransfer baru untuk update fileInput.files
        const dataTransfer = new DataTransfer();

        filesArray.forEach(file => {
            dataTransfer.items.add(file);
        });

        fileInput.files = dataTransfer.files;
    }
</script>

</x-app-layout>
