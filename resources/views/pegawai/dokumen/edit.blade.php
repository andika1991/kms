<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Dokumen
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" 
                  action="{{ route('pegawai.manajemendokumen.update', $manajemendokuman->id) }}" 
                  enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Nama Dokumen --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Nama Dokumen</label>
                    <input type="text" name="nama_dokumen" 
                           value="{{ old('nama_dokumen', $manajemendokuman->nama_dokumen) }}" 
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Kategori Dokumen</label>
                    <select id="kategoriSelect" 
                            name="kategori_dokumen_id" 
                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option 
                                value="{{ $kat->id }}"
                                data-nama="{{ strtolower($kat->nama_kategoridokumen) }}"
                                {{ old('kategori_dokumen_id', $manajemendokuman->kategori_dokumen_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoridokumen }}
                                @if($kat->subbidang)
                                    — {{ $kat->subbidang->nama }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Upload Dokumen --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Ganti File Dokumen (opsional)</label>
                    <input type="file" name="path_dokumen" 
                           class="w-full text-gray-700 dark:text-gray-200">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        Biarkan kosong jika tidak ingin mengganti file.
                    </p>
                    @if($manajemendokuman->path_dokumen)
                        <p class="text-sm text-gray-500 dark:text-gray-300 mt-2">
                            File saat ini: 
                            <a href="{{ asset('storage/' . $manajemendokuman->path_dokumen) }}" 
                               target="_blank" class="text-blue-500 hover:underline">
                                Lihat Dokumen
                            </a>
                        </p>
                    @endif
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" 
                              class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" required>{{ old('deskripsi', $manajemendokuman->deskripsi) }}</textarea>
                </div>

                {{-- Encrypted Key --}}
                <div class="mb-4 {{ strtolower($manajemendokuman->kategoriDokumen->nama_kategoridokumen) == 'rahasia' ? '' : 'hidden' }}" id="encrypted-key-field">
                    <label class="block text-gray-700 dark:text-gray-200">Kunci Rahasia / Encrypted Key</label>
                    <input type="text" name="encrypted_key" 
                           value="{{ old('encrypted_key', $manajemendokuman->encrypted_key) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategoriSelect');
            const keyField = document.getElementById('encrypted-key-field');

            function toggleKeyField() {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                const namaKategori = selectedOption?.getAttribute('data-nama');

                if (namaKategori === 'rahasia') {
                    keyField.classList.remove('hidden');
                } else {
                    keyField.classList.add('hidden');
                }
            }

            kategoriSelect?.addEventListener('change', toggleKeyField);
            toggleKeyField();
        });
    </script>
</x-app-layout>
