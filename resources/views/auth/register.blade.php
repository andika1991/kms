<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NEW: Tipe User -->
   <!-- Tipe User -->
<div class="mt-4">
    <x-input-label for="tipe_user" :value="__('Tipe Pendaftar')" />
    <select id="tipe_user" name="tipe_user" class="block mt-1 w-full" required onchange="toggleRoleOptions()">
        <option value="">-- Pilih Tipe Pendaftar --</option>
        <option value="pegawai">Pegawai</option>
        <option value="magang">Magang</option>
    </select>
    <x-input-error :messages="$errors->get('tipe_user')" class="mt-2" />
</div>

<!-- Role Pegawai -->
<div class="mt-4 hidden" id="role-pegawai">
    <x-input-label for="role_id" :value="__('Pilih Role Pegawai')" />
    <select name="role_id" class="block mt-1 w-full">
        @foreach ($rolesPegawai as $role)
            <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
        @endforeach
    </select>
</div>

<!-- Role Magang -->
<div class="mt-4 hidden" id="role-magang">
    <x-input-label for="role_id" :value="__('Pilih Role Magang')" />
    <select name="role_id" class="block mt-1 w-full">
        @foreach ($rolesMagang as $role)
            <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
        @endforeach
    </select>
</div>


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
    function toggleRoleOptions() {
        const tipeUser = document.getElementById('tipe_user').value;
        const rolePegawai = document.getElementById('role-pegawai');
        const roleMagang = document.getElementById('role-magang');

        if (tipeUser === 'pegawai') {
            rolePegawai.classList.remove('hidden');
            roleMagang.classList.add('hidden');
        } else if (tipeUser === 'magang') {
            roleMagang.classList.remove('hidden');
            rolePegawai.classList.add('hidden');
        } else {
            rolePegawai.classList.add('hidden');
            roleMagang.classList.add('hidden');
        }
    }
</script>

</x-guest-layout>
