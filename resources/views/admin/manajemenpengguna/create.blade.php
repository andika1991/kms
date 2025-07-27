
@section('title', 'Tambah Pengguna')

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff] flex flex-col">
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Pengguna Baru</h2>
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
            <form action="{{ route('admin.manajemenpengguna.store') }}" method="POST"
                  class="bg-white rounded-2xl shadow-lg p-6 md:p-10 w-full max-w-3xl mx-auto flex flex-col gap-6">
                @csrf

                <div class="flex flex-col gap-2">
                    <label for="name" class="font-semibold text-gray-800">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="flex flex-col gap-2">
                    <label for="email" class="font-semibold text-gray-800">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
<!-- Role Group -->
<div class="flex flex-col gap-2">
    <label for="role_group" class="font-semibold text-gray-800">Role Group</label>
    <select name="role_group" id="role_group" required
        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
        <option value="">-- Pilih Role Group --</option>
        @foreach ($roleGroups as $group)
            <option value="{{ $group }}">{{ ucfirst($group) }}</option>
        @endforeach
    </select>
</div>

<!-- Role -->
<div class="flex flex-col gap-2">
    <label for="role_id" class="font-semibold text-gray-800">Role</label>
    <select name="role_id" id="role_id" required
        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
        <option value="">-- Pilih Role --</option>
        {{-- Diisi via JS --}}
    </select>
</div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="font-semibold text-gray-800">Password</label>
                    <input type="password" name="password" id="password" required
                        class="rounded-xl border border-gray-300 px-4 py-2 bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.manajemenpengguna.index') }}"
                        class="px-6 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
    // Ambil semua data role dari controller (sudah di-passing via Blade)
    const allRoles = @json($roles);

    const roleGroupSelect = document.getElementById('role_group');
    const roleSelect = document.getElementById('role_id');

    // Event saat role_group dipilih
    roleGroupSelect.addEventListener('change', function () {
        const selectedGroup = this.value;

        // Kosongkan dropdown role dulu
        roleSelect.innerHTML = '<option value="">-- Pilih Role --</option>';

        if (selectedGroup === '') return;

        // Filter role berdasarkan role_group
        const filteredRoles = allRoles.filter(role => role.role_group === selectedGroup);

        // Tambahkan ke dropdown role
        filteredRoles.forEach(role => {
            const option = document.createElement('option');
            option.value = role.id;
            option.textContent = role.nama_role;
            roleSelect.appendChild(option);
        });
    });
</script>

</x-app-layout>
