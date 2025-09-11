@php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');

// Ambil foto user dari storage, fallback ke placeholder jika null
$photoPath = $user->photo_profil;
$photoUrl = $photoPath 
    ? Storage::disk('public')->url($photoPath) 
    : asset('assets/img/avatar-placeholder.png');

// Tambahkan query string timestamp untuk cache busting
$photoUrl .= $photoPath ? ('?v=' . (optional($user->updated_at)->timestamp ?? time())) : '';
@endphp

<x-app-layout>
    {{-- Scoped style khusus halaman profil --}}
    <style>
    .profile-card { color-scheme: light; }
    .profile-card input, .profile-card select, .profile-card textarea {
        background-color: #f6f8fa !important;
        color: #111827 !important;
    }
    .profile-card input::placeholder, .profile-card textarea::placeholder { color: #9ca3af; }
    .profile-card input:-webkit-autofill,
    .profile-card input:-webkit-autofill:hover,
    .profile-card input:-webkit-autofill:focus,
    .profile-card select:-webkit-autofill,
    .profile-card textarea:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #f6f8fa inset !important;
        box-shadow: 0 0 0 1000px #f6f8fa inset !important;
        -webkit-text-fill-color: #111827 !important;
        transition: background-color 9999s ease-in-out 0s;
    }
    </style>

    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Profil</h2>
                    <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                    <label class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" placeholder="Cari..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </label>

                    {{-- Dropdown Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display:none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY GRID --}}
        <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Form Informasi Profil --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border profile-card">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Informasi Profil</h2>
                            <p class="mt-1 text-sm text-gray-600">Ubah informasi akun dan alamat email anda.</p>
                        </header>
                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')
                            <div>
                                <x-input-label for="name" :value="__('Nama')" class="text-gray-900" />
                                <x-text-input id="name" name="name" type="text"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-[#f6f8fa] text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                                    :value="old('name', $user->name)" required autofocus autocomplete="name"
                                    placeholder="Nama" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-gray-900" />
                                <x-text-input id="email" name="email" type="email"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-[#f6f8fa] text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                                    :value="old('email', $user->email)" required autocomplete="username"
                                    placeholder="Email" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex justify-end items-center gap-4">
                                <x-primary-button
                                    class="!bg-green-600 hover:!bg-green-700 focus:!bg-green-700 active:!bg-green-800 !border-transparent !text-white">
                                    {{ __('Simpan') }}
                                </x-primary-button>
                                @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                    x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                    {{ __('Tersimpan.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>

                {{-- Form Ubah Kata Sandi --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border profile-card">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Ubah Kata Sandi</h2>
                            <p class="mt-1 text-sm text-gray-600">Ganti kata sandi anda.</p>
                        </header>

                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-input-label for="current_password" :value="__('Kata sandi saat ini')"
                                    class="text-gray-900" />
                                <x-text-input id="current_password" name="current_password" type="password"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-[#f6f8fa] text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                                    autocomplete="current-password" placeholder="Kata sandi saat ini" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')"
                                    class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Kata sandi baru')" class="text-gray-900" />
                                <x-text-input id="password" name="password" type="password"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-[#f6f8fa] text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                                    autocomplete="new-password" placeholder="Kata sandi baru" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi kata sandi')"
                                    class="text-gray-900" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-[#f6f8fa] text-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-none transition"
                                    autocomplete="new-password" placeholder="Konfirmasi kata sandi" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')"
                                    class="mt-2" />
                            </div>

                            <div class="flex justify-end items-center gap-4">
                                <x-primary-button
                                    class="!bg-green-600 hover:!bg-green-700 focus:!bg-green-700 active:!bg-green-800 !border-transparent !text-white">
                                    {{ __('Simpan') }}
                                </x-primary-button>
                                @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                    x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                    {{ __('Tersimpan.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>

                {{-- Form Hapus Akun --}}
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Hapus Akun</h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Saat akun anda terhapus, semua data yang ada akan terhapus secara permanen.
                                Sebelum menghapus akun anda, mohon untuk mengunduh seluruh data yang anda perlukan.
                            </p>
                        </header>

                        <x-danger-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                            {{ __('Hapus Akun') }}
                        </x-danger-button>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            {{-- Kode modal konfirmasi --}}
                        </x-modal>
                    </section>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <aside class="lg:col-span-1 w-full flex flex-col gap-8">
                {{-- Kartu Foto Profil --}}
                <form id="form-upload-foto" method="POST" action="{{ route('profile.uploadPhoto') }}"
                    enctype="multipart/form-data" data-has-photo="{{ $photoPath ? 1 : 0 }}"
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 flex flex-col items-center justify-center text-center mb-2">
                    @csrf
                    @method('PATCH')

                    <div class="relative w-32 h-32 mb-4 group">
                        

                        <img id="preview-foto" src="{{ asset('storage/' . $user->photo_profil) }}" alt="Foto Profil" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/avatar-placeholder.png') }}';"
                            class="w-full h-full rounded-full object-cover border-4 border-white/50 transition duration-150">
                        <label for="photo_profil"
                            class="absolute inset-0 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 bg-black/30 rounded-full transition">
                            <span class="text-sm font-bold text-white">Pilih Foto</span>
                            <input type="file" id="photo_profil" name="photo_profil" accept="image/*" class="hidden"
                                onchange="previewPhoto(event)">
                        </label>
                    </div>

                    {{-- Tombol pilih file --}}
                    <button type="button" onclick="document.getElementById('photo_profil').click()"
                        class="w-full bg-white/90 text-blue-800 font-semibold py-2 rounded-lg hover:bg-white transition">
                        Pilih Foto
                    </button>

                    {{-- Tombol upload --}}
                    <button type="submit"
                        class="w-full bg-green-500 text-white font-semibold py-2 rounded-lg hover:bg-green-600 transition mt-2">
                        Upload Foto
                    </button>

                    {{-- Notifikasi --}}
                    @if(session('status') === 'profile-photo-updated')
                        <p id="photo-status" class="mt-2 text-green-200 text-sm font-bold">
                            Foto berhasil diubah!
                        </p>
                    @endif

                    @error('photo_profil')
                        <p class="text-red-200 font-semibold mt-2 text-sm">{{ $message }}</p>
                    @enderror
                </form>

                {{-- Info User --}}
                <div class="bg-gradient-to-br from-blue-800 to-blue-900 text-white rounded-2xl shadow-lg p-7 space-y-4">
                    <div>
                        <p class="text-sm opacity-80">Nama</p>
                        <p class="font-bold text-lg">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-80">Email</p>
                        <p class="font-bold text-lg">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-80">Role</p>
                        <p class="font-bold text-lg">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <x-slot name="footer">
        <footer class="bg-[#2b6cb0] py-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 flex justify-center items-center">
                <img src="{{ asset('assets/img/logo_footer_diskominfotik.png') }}" alt="Footer Diskominfotik"
                    class="h-10 object-contain">
            </div>
        </footer>
    </x-slot>

    {{-- JS Preview --}}
    <script>
    function previewPhoto(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => document.getElementById('preview-foto').src = ev.target.result;
        reader.readAsDataURL(file);
    }
    </script>
</x-app-layout>
