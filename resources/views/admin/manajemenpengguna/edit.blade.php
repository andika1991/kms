@section('title', 'Edit Pengguna')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col">
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Pengguna</h2>
        </div>

        <!-- FLASH & ERROR -->
        <div class="max-w-2xl mx-auto w-full mt-4 px-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 p-4 rounded-xl mb-4 text-sm font-semibold text-center">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-800 p-4 rounded-xl mb-4 text-sm font-semibold text-center">
                    <ul class="list-disc list-inside text-left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- FORM -->
        <div class="w-full px-2 md:px-10 py-10">
            <form action="{{ route('admin.manajemenpengguna.update', $user->id) }}" method="POST"
                  class="bg-white rounded-2xl shadow-lg p-6 md:p-10 w-full max-w-3xl mx-auto flex flex-col gap-6">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-2">
                    <label for="name" class="font-semibold text-gray-800">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="email" class="font-semibold text-gray-800">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Role Group -->
                <div class="flex flex-col gap-2">
                    <label for="role_group" class="font-semibold text-gray-800">Role Group</label>
                    <select name="role_group" id="role_group" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih Role Group --</option>
                        @foreach ($roleGroups as $group)
                            <option value="{{ $group }}" {{ old('role_group', $user->role?->role_group) === $group ? 'selected' : '' }}>
                                {{ ucfirst($group) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Role -->
                <div class="flex flex-col gap-2">
                    <label for="role_id" class="font-semibold text-gray-800">Role</label>
                    <select name="role_id" id="role_id" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih Role --</option>
                        {{-- Opsi akan diisi oleh JS --}}
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="font-semibold text-gray-800">Password <span class="text-sm text-gray-500">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" id="password"
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.manajemenpengguna.index') }}"
                        class="px-6 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const allRoles = @json($roles);
        const currentRoleId = "{{ old('role_id', $user->role_id) }}";

        const roleGroupSelect = document.getElementById('role_group');
        const roleSelect = document.getElementById('role_id');

        function populateRoles(group) {
            roleSelect.innerHTML = '<option value="">-- Pilih Role --</option>';
            if (!group) return;

            const filteredRoles = allRoles.filter(role => role.role_group === group);

            filteredRoles.forEach(role => {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = role.nama_role;
                if (role.id == currentRoleId) option.selected = true;
                roleSelect.appendChild(option);
            });
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            if (roleGroupSelect.value) {
                populateRoles(roleGroupSelect.value);
            }
        });

        roleGroupSelect.addEventListener('change', function () {
            populateRoles(this.value);
        });
    </script>
</x-app-layout>
