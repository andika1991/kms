@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <div class="p-6 md:p-8 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen</h2>
                    <p class="text-gray-500 text-sm font-normal">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 mt-4 sm:mt-0 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                    {{-- Profile Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
              <a href="{{ route('dokumen.dibagikan.ke.saya') }}"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-sm transition text-base mt-4">
                <i class="fa-solid fa-share-from-square"></i>
                <span>Dokumen Dibagikan ke Saya</span>
            </a>
            
        </div>
  @if(session('success'))
                <div
                    class="mb-6 px-6 py-4 rounded-lg bg-green-100 text-green-800 font-semibold shadow-md border border-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
      @if(session('error'))
    <div
        class="mb-6 px-6 py-4 rounded-lg bg-red-100 text-red-800 font-semibold shadow-md border border-red-300">
        {{ session('error') }}
    </div>
@endif

        {{-- BODY GRID --}}
        <div class="p-4 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            <section class="xl:col-span-8 w-full">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-gray-100">
                                <th class="px-6 py-4 text-base font-semibold">Judul</th>
                                <th class="px-6 py-4 text-base font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($dokumen as $item)
                            <tr class="@if($loop->even) bg-[#eaf3fa] @endif border-b border-gray-100">
                                <td class="flex items-center gap-4 px-6 py-4">
                                    {{-- Thumbnail --}}
                                    <div class="w-28 h-16 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($item->thumbnail)
                                            <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->nama_dokumen }}" class="object-cover w-full h-full" />
                                        @else
                                            <img src="{{ asset('assets/img/default-file.svg') }}" alt="No Image" class="object-contain w-12 h-12 opacity-60" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                        {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex items-center gap-2 justify-center">

                                    @if ($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen == 'Rahasia')
                                    <button
                                        onclick="showPasswordModal('{{ $item->id }}')"
                                        class="text-blue-600 hover:underline">
                                        Lihat
                                    </button>
                                    @else
                                    <a href="{{ route('kepalabagian.manajemendokumen.show', $item->id) }}" class="text-blue-600 hover:underline">
                                        Lihat
                                    </a>
                                    @endif
                                    <a href="{{ route('kepalabagian.manajemendokumen.edit', $item->id) }}"
                                       class="px-4 py-1.5 rounded-full bg-[#bbb549] hover:bg-yellow-500 text-white font-semibold transition text-sm">Edit</a>
                                    <form action="{{ route('kepalabagian.manajemendokumen.destroy', $item->id) }}"
                                          method="POST" onsubmit="return confirm('Hapus dokumen ini?');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-1.5 rounded-full bg-[#b14444] hover:bg-red-600 text-white font-semibold transition text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{-- {{ $dokumen->links() }} --}}
                </div>

            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div class="bg-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight">{{ Auth::user()->role->nama_role ?? 'User' }}</p>
                    </div>
                </div>
                <a href="{{ route('kepalabagian.manajemendokumen.create') }}"
                   class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm transition text-base">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Dokumen</span>
                </a>
          
                {{-- Kategori --}}
                
            </aside>

            <!-- MODAL PASSWORD -->
            <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-xl shadow-lg p-6 w-80 max-w-full">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen</h3>
                    <input id="modalPasswordInput" type="password" placeholder="Kunci Dokumen"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="hidden" id="dokumenId" />
                    <div class="flex justify-end gap-2">
                        <button onclick="closeModal()"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold">Batal</button>
                        <button onclick="submitPassword()"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold">Submit</button>
                    </div>
                </div>
            </div>
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
    </div>

  <script>
        function closeKategoriModal() {
            document.getElementById('kategoriModal').classList.add('hidden');
        }

        function openEditKategoriModal(id, nama) {
            document.getElementById('edit_nama_kategori').value = nama;
            document.getElementById('editKategoriForm').action = '/kasubbidang/kategori-dokumen/' + id;
            document.getElementById('editKategoriModal').classList.remove('hidden');
        }

        function closeEditKategoriModal() {
            document.getElementById('editKategoriModal').classList.add('hidden');
        }

        // Modal password untuk dokumen rahasia
        function showPasswordModal(dokumenId) {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('dokumenId').value = dokumenId;
            document.getElementById('modalPasswordInput').value = '';
        }

        function closeModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }

        function submitPassword() {
            const dokumenId = document.getElementById('dokumenId').value;
            const password = document.getElementById('modalPasswordInput').value.trim();

            if (!password) {
                alert('Kunci tidak boleh kosong.');
                return;
            }

            const url = `/kepalabagian/manajemendokumen/${dokumenId}?encrypted_key=${encodeURIComponent(password)}`;
            window.location.href = url;
        }

        // Konfirmasi hapus dokumen
        function showHapusModal(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus dokumen ini?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + id).submit();
                }
            });
        }
        </script>
</x-app-layout>