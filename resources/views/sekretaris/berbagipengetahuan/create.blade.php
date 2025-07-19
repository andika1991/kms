<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Artikel Pengetahuan
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('sekretaris.berbagipengetahuan.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- JUDUL --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Judul</label>
                    <input type="text" 
                           id="judul" 
                           name="judul" 
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" 
                           required 
                           value="{{ old('judul') }}">
                </div>

                {{-- SLUG --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Slug</label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" 
                           required 
                           value="{{ old('slug') }}">
                </div>

                {{-- KATEGORI --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Kategori Pengetahuan</label>
                    <select name="kategori_pengetahuan_id" 
                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" 
                            required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pengetahuan_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoripengetahuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- THUMBNAIL --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Thumbnail</label>
                    <input type="file" name="thumbnail" class="w-full text-gray-700 dark:text-gray-200">
                </div>

                {{-- FILE DOKUMEN --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">File Dokumen</label>
                    <input type="file" name="filedok" class="w-full text-gray-700 dark:text-gray-200">
                </div>

                {{-- ISI ARTIKEL --}}
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Isi Artikel</label>
                    <textarea id="isi" name="isi" rows="10" 
                              class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">{{ old('isi') }}</textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- TinyMCE CDN --}}
    <script src="https://cdn.tiny.cloud/1/5tsdsuoydzm2f0tjnkrffxszmoas3as1xlmcg5ujs82or4wz/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#isi',
            height: 300,
            plugins: 'lists link image preview',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image | preview',
            menubar: false,
            content_css: false,
            skin: "oxide-dark",
        });

        // Generate slug otomatis
        document.getElementById('judul').addEventListener('keyup', function () {
            let judul = this.value;
            let slug = judul
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')  // hilangkan karakter aneh
                .trim()
                .replace(/\s+/g, '-')          // ganti spasi dengan -
                .replace(/-+/g, '-');          // hapus double -
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
