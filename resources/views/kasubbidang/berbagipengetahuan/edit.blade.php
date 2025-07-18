<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Artikel Pengetahuan
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" 
                  action="{{ route('kasubbidang.berbagipengetahuan.update', $artikelpengetahuan->id) }}" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Judul</label>
                    <input type="text" name="judul"
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200"
                           required
                           value="{{ old('judul', $artikelpengetahuan->judul) }}">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Slug</label>
                    <input type="text" name="slug"
                           class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200"
                           required
                           value="{{ old('slug', $artikelpengetahuan->slug) }}">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Kategori Pengetahuan</label>
                    <select name="kategori_pengetahuan_id"
                            class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200"
                            required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}"
                                {{ old('kategori_pengetahuan_id', $artikelpengetahuan->kategori_pengetahuan_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategoripengetahuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Thumbnail (Optional)</label>
                    @if($artikelpengetahuan->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $artikelpengetahuan->thumbnail) }}" class="h-32 rounded" alt="Thumbnail">
                        </div>
                    @endif
                    <input type="file" name="thumbnail"
                           class="w-full text-gray-700 dark:text-gray-200">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">File Dokumen (Optional)</label>
                    @if($artikelpengetahuan->filedok)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $artikelpengetahuan->filedok) }}"
                               target="_blank"
                               class="text-blue-600 dark:text-blue-400 underline">
                                Lihat Dokumen
                            </a>
                        </div>
                    @endif
                    <input type="file" name="filedok"
                           class="w-full text-gray-700 dark:text-gray-200">
                </div>

                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-200">Isi Artikel</label>
                    <textarea id="isi" name="isi" rows="10"
                              class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">
                        {{ old('isi', $artikelpengetahuan->isi) }}
                    </textarea>
                </div>

                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update
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
        
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.querySelector('input[name="judul"]');
        const slugInput = document.querySelector('input[name="slug"]');

        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function () {
                let slug = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                slugInput.value = slug;
            });
        }
    });


    </script>
</x-app-layout>
