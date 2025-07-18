<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Grup Chat
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('kasubbidang.forum.update', $grupchat->id) }}">
                @csrf
                @method('PUT')

                {{-- Nama Grup --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Nama Grup</label>
                    <input type="text" name="nama_grup" value="{{ old('nama_grup', $grupchat->nama_grup) }}"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" required>
                    @error('nama_grup')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">{{ old('deskripsi', $grupchat->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grup Role --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Grup Role</label>
                    <input type="text" name="grup_role" value="{{ old('grup_role', $grupchat->grup_role) }}"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">
                    @error('grup_role')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Private Checkbox --}}
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_private" id="is_private" value="1"
                            class="rounded text-blue-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                            {{ old('is_private', $grupchat->is_private) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-200">Private Grup</span>
                    </label>
                </div>

                {{-- Anggota Grup --}}
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Pilih Anggota Grup</label>
                    <select id="user-select" name="pengguna_id[]" multiple
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ in_array($user->id, $anggota_ids) ? 'selected' : '' }}>
                                {{ $user->decrypted_name }} ({{ $user->decrypted_email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Ketik untuk mencari dan pilih anggota grup. Bisa pilih lebih dari satu.</small>
                </div>

                {{-- Bidang --}}
                <div id="bidang-field" class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Bidang</label>
                    <select name="bidang_id"
                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-gray-200" readonly>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}"
                                {{ $grupchat->bidang_id == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect("#user-select", {
                maxItems: null,
                valueField: "value",
                labelField: "text",
                searchField: ["text"],
            });

            const isPrivateCheckbox = document.getElementById('is_private');
            const bidangField = document.getElementById('bidang-field');
            const usersField = document.getElementById('user-select').closest('div');

            function toggleFields() {
                if (isPrivateCheckbox.checked) {
                    bidangField.classList.add('hidden');
                    usersField.classList.remove('hidden');
                } else {
                    bidangField.classList.remove('hidden');
                    usersField.classList.add('hidden');
                }
            }

            isPrivateCheckbox.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
</x-app-layout>
