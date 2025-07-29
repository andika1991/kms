@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Kasubbidang')

<x-app-layout>
    <!-- SweetAlert2 CDN for Notification -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-lg'
            }
        });
    });
    </script>
    @endif

    @if(session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-lg'
            }
        });
    });
    </script>
    @endif

    <div class="w-full min-h-screen bg-[#eaf5ff]">
        <!-- HEADER -->
        <div class="p-6 md:p-8 border-b border-gray-200 bg-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen Kasubbidang</h2>
                    <p class="text-gray-500 text-sm font-normal mt-1">{{ $tanggal }}</p>
                </div>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('kasubbidang.manajemendokumen.index') }}"
                        class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </span>
                    </form>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20" x-transition
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
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

        <!-- BODY GRID -->
        <div class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            <!-- KOLOM UTAMA (DAFTAR DOKUMEN) -->
            <section class="xl:col-span-8 w-full">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-lg text-[#2171b8]">Daftar Dokumen Kasubbidang</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-gray-100">
                                <th class="px-6 py-4 text-base font-semibold">Judul Dokumen</th>
                                <th class="px-6 py-4 text-base font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-base font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                            <tr class="@if($loop->even) bg-[#eaf3fa] @endif border-b border-gray-100">
                                <td class="flex items-center gap-4 px-6 py-4">
                                    @php
                                    $filePath = $item->path_dokumen ? asset('storage/'.$item->path_dokumen) : null;
                                    $extension = $item->path_dokumen ? strtolower(pathinfo($item->path_dokumen,
                                    PATHINFO_EXTENSION)) : '';
                                    $isImage = in_array($extension, ['jpg','jpeg','png','gif','bmp','webp']);
                                    @endphp
                                    <div
                                        class="w-20 h-14 flex items-center justify-center rounded-md overflow-hidden bg-gray-100 border">
                                        @if($isImage)
                                        <img src="{{ asset('storage/'.$item->path_dokumen) }}"
                                            alt="{{ $item->nama_dokumen }}" class="object-cover w-full h-full" />
                                        @elseif($extension == 'pdf')
                                        <img src="{{ asset('assets/img/icon-pdf.svg') }}"
                                            class="object-contain w-10 h-10" />
                                        @elseif(in_array($extension, ['doc','docx']))
                                        <img src="{{ asset('assets/img/icon-word.svg') }}" alt="Word"
                                            class="object-contain w-10 h-10" />
                                        @elseif(in_array($extension, ['xls','xlsx']))
                                        <img src="{{ asset('assets/img/icon-excel.svg') }}" alt="Excel"
                                            class="object-contain w-10 h-10" />
                                        @else
                                        <img src="{{ asset('assets/img/default-file.svg') }}" alt="File"
                                            class="object-contain w-10 h-10 opacity-60" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $item->nama_dokumen }}</div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}</div>
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
                                    <a href="{{ route('kasubbidang.manajemendokumen.show', $item->id) }}" class="text-blue-600 hover:underline">
                                        Lihat
                                    </a>
                                    @endif

                                    <a href="{{ route('kasubbidang.manajemendokumen.edit', $item->id) }}"
                                        class="px-4 py-1.5 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold transition text-sm">Edit</a>
                                    <form id="form-hapus-{{ $item->id }}"
                                        action="{{ route('kasubbidang.manajemendokumen.destroy', $item->id) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showHapusModal({{ $item->id }})"
                                            class="px-4 py-1.5 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold transition text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.
                                </td>
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

            <!-- KOLOM SIDEBAR -->
            <aside class="xl:col-span-4 w-full flex flex-col gap-8">
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-7 flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" alt="Role Icon" class="h-16 w-16 mb-4">
                    <div>
                        <p class="font-bold text-lg leading-tight mb-2">
                            {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                        <p class="text-xs">Upload, simpan, dan kelola dokumen kegiatan, inovasi, dan knowledge sharing
                            di sini.</p>
                    </div>
                </div>
                <!-- Tombol Aksi -->
                <a href="{{ route('kasubbidang.manajemendokumen.create') }}"
                    class="block w-full text-center rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold text-[16px] py-2.5 shadow transition">
                    <i class="fa-solid fa-plus"></i> <span>Tambah Dokumen</span>
                </a>
                <button onclick="document.getElementById('kategoriModal').classList.remove('hidden')"
                    class="block w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold text-[16px] py-2.5 shadow transition -mt-1">
                    <i class="fa-solid fa-folder-plus"></i> <span>Tambah Kategori</span>
                </button>
                <!-- Card Kategori -->
                <div class="bg-white rounded-[16px] shadow p-5">
                    <div class="text-[#2563a9] font-semibold text-[15px] mb-2">Kategori Dokumen</div>
                    <ul class="flex flex-col gap-2">
                        @foreach($kategori as $kat)
                        <li class="flex items-center justify-between group bg-transparent px-1 py-1 rounded transition">
                            <span class="text-[15px] text-[#232323] leading-5 group-hover:font-semibold">
                                {{ $kat->nama_kategoridokumen }}
                            </span>
                            <span class="flex gap-1 opacity-80 group-hover:opacity-100">
                                <!-- Edit Kategori Modal Trigger -->
                                <button
                                    onclick="openEditKategoriModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategoridokumen) }}')"
                                    class="px-2 py-1 rounded hover:bg-yellow-100" title="Edit">
                                    <i class="fa-solid fa-pen text-yellow-500 text-xs"></i>
                                </button>
                                <!-- Hapus Kategori Form -->
                                <form action="{{ route('kasubbidang.kategori-dokumen.destroy', $kat->id) }}"
                                    method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                        class="px-2 py-1 rounded hover:bg-red-100" title="Hapus">
                                        <i class="fa-solid fa-trash text-red-600 text-xs"></i>
                                    </button>
                                </form>
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- MODAL EDIT KATEGORI -->
            <div id="editKategoriModal"
                class="fixed z-50 inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center transition">
                <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
                    <h2 class="font-bold text-lg text-gray-800 mb-4 text-center">Edit Kategori Dokumen</h2>
                    <form id="editKategoriForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="nama_kategori" id="edit_nama_kategori"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center mb-4"
                            placeholder="Masukkan nama kategori" required>
                        <div class="flex w-full gap-2 mt-2 justify-end">
                            <button type="button" onclick="closeEditKategoriModal()"
                                class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">Batal</button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL TAMBAH KATEGORI -->
            <div id="kategoriModal"
                class="fixed z-50 inset-0 hidden bg-black bg-opacity-60 flex items-center justify-center transition">
                <div class="bg-white rounded-2xl w-[90vw] max-w-md shadow-xl p-8 flex flex-col items-center relative">
                    <!-- Icon Figma Style -->
                    <div class="flex flex-col items-center mb-3">
                        <div
                            class="rounded-full bg-gradient-to-br from-blue-500 to-blue-300 w-16 h-16 flex items-center justify-center mb-2">
                            <i class="fa-solid fa-folder-plus text-white text-3xl"></i>
                        </div>
                        <h2 class="font-bold text-lg text-gray-800 mb-2 text-center">Tambah Kategori Dokumen</h2>
                    </div>
                    <form action="{{ route('kasubbidang.kategori-dokumen.store') }}" method="POST"
                        class="w-full flex flex-col items-center gap-4">
                        @csrf
                        <input type="text" name="nama_kategori" id="nama_kategori"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 px-4 py-3 text-base text-center"
                            placeholder="Masukkan nama kategori" required>
                        <div class="flex w-full gap-2 mt-2 justify-end">
                            <button type="button" onclick="closeKategoriModal()"
                                class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white font-semibold">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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

        <!-- SCRIPT -->
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

            const url = `/kasubbidang/manajemendokumen/${dokumenId}?encrypted_key=${encodeURIComponent(password)}`;
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
