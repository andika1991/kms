@php
use Carbon\Carbon;
$carbon = Carbon::now()->locale('id');
$carbon->settings(['formatFunction' => 'translatedFormat']);
$tanggal = $carbon->format('l, d F Y');
@endphp

@section('title', 'Manajemen Dokumen Kasubbidang')

@if (session('success'))
<script>
window.addEventListener('load', () => {
    Swal.fire({
        position: 'top',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        background: '#f0fff4',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5',
            title: 'font-bold text-base md:text-lg text-green-800'
        },
        timer: 2200
    });
});
</script>
@endif

@if (session('deleted'))
<script>
window.addEventListener('load', () => {
    Swal.fire({
        position: 'top',
        icon: 'error',
        title: @json(session('deleted')),
        showConfirmButton: false,
        background: '#fef2f2',
        customClass: {
            popup: 'rounded-xl shadow-md px-8 py-5 border border-red-200',
            title: 'font-bold text-base md:text-lg text-red-800'
        },
        timer: 2500
    });
});
</script>
@endif

<x-app-layout>
    <div class="w-full min-h-screen bg-[#eaf5ff]">
        {{-- HEADER --}}
        <header class="p-6 md:p-8 border-b border-gray-200 bg-[#eaf5ff]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Dokumen Kasubbidang</h2>
                    <p class="text-gray-500 text-sm mt-1">{{ $tanggal }}</p>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('kasubbidang.manajemendokumen.index') }}" class="relative grow sm:grow-0 sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama dokumen..."
                            class="w-full rounded-full border-gray-300 bg-white pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition" />
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fa fa-search"></i></span>
                    </form>

                    {{-- Profile --}}
                    <div x-data="{ open:false }" class="relative">
                        <button @click="open=!open"
                            class="w-10 h-10 grid place-content-center bg-white rounded-full border border-gray-300 text-gray-600 text-lg hover:shadow-md hover:border-blue-500 hover:text-blue-600 transition"
                            title="Profile">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <nav x-show="open" @click.away="open=false" x-transition style="display:none"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-20 overflow-hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-50">Log Out</button>
                            </form>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY --}}
        <main class="p-6 md:p-8 grid grid-cols-1 xl:grid-cols-12 gap-8 max-w-[1400px] mx-auto">
            <section class="xl:col-span-8">
                <section class="overflow-x-auto">
                    {{-- TABEL --}}
                    <table class="min-w-full bg-white rounded-2xl shadow border mb-2">
                        <thead>
                            <tr class="text-left bg-[#2171b8] text-white">
                                <th class="px-6 py-4 text-sm font-semibold rounded-tl-2xl">Preview</th>
                                <th class="px-6 py-4 text-sm font-semibold">Judul</th>
                                <th class="px-6 py-4 text-sm font-semibold">Kategori</th>
                                <th class="px-6 py-4 text-sm font-semibold text-center rounded-tr-2xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumen as $item)
                                @php
                                    $filePath = $item->path_dokumen ? asset('storage/'.$item->path_dokumen) : null;
                                    $ext = $item->path_dokumen ? strtolower(pathinfo($item->path_dokumen, PATHINFO_EXTENSION)) : '';
                                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','bmp','webp']);
                                    $rahasia = ($item->kategoriDokumen && $item->kategoriDokumen->nama_kategoridokumen === 'Rahasia');
                                @endphp
                                <tr class="row-dokumen border-b border-gray-100 transition hover:bg-[#f5f9ff] cursor-pointer"
                                    data-id="{{ $item->id }}" data-rahasia="{{ $rahasia ? '1' : '0' }}">
                                    <td class="px-6 py-4">
                                        <figure class="w-20 h-14 grid place-content-center rounded-md overflow-hidden bg-gray-100 border">
                                            @if($isImage && $filePath)
                                                <img src="{{ $filePath }}" alt="Preview" class="w-full h-full object-cover" />
                                            @else
                                                <span class="material-icons text-red-600 text-3xl">picture_as_pdf</span>
                                            @endif
                                        </figure>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <h4 class="font-medium text-gray-900">{{ $item->nama_dokumen }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 48) }}</p>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <span class="inline-block rounded-lg px-3 py-1 bg-[#f3f3f3] text-gray-700 text-sm">
                                            {{ $item->kategoriDokumen->nama_kategoridokumen ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <nav class="flex items-center justify-center gap-2">
                                            <a href="javascript:void(0)"
                                                class="js-edit-key w-9 h-9 grid place-content-center rounded bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
                                                title="Edit"
                                                data-id="{{ $item->id }}"
                                                data-rahasia="{{ $rahasia ? 1 : 0 }}">
                                                <i class="fa-solid fa-pen-to-square text-lg"></i>
                                            </a>
                                            <button type="button" onclick="showHapusModal({{ $item->id }}); event.stopPropagation();"
                                                class="js-no-row w-9 h-9 grid place-content-center rounded bg-red-100 hover:bg-red-200 text-red-600 transition"
                                                title="Hapus">
                                                <i class="fa-solid fa-trash text-lg"></i>
                                            </button>
                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-gray-500 text-center py-12">Belum ada dokumen yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </section>

            {{-- SIDEBAR --}}
            <aside class="xl:col-span-4 flex flex-col gap-8 mt-8 xl:mt-0">
                <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl shadow-lg p-8 text-center">
                    <img src="{{ asset('img/artikelpengetahuan-elemen.svg') }}" class="h-16 w-16 mx-auto mb-4" alt="Role Icon">
                    <p class="font-bold text-lg leading-tight mb-2">Bidang {{ Auth::user()->role->nama_role ?? 'Kasubbidang' }}</p>
                    <p class="text-xs opacity-90">Upload dan simpan dokumen kamu di sini.</p>
                </section>

                <a href="{{ route('kasubbidang.manajemendokumen.create') }}"
                    class="w-full rounded-[12px] bg-[#27ad60] hover:bg-[#17984d] text-white font-semibold px-5 py-2.5 shadow transition inline-flex items-center justify-center gap-2 text-base">
                    <i class="fa-solid fa-plus"></i> Tambah Dokumen
                </a>

                <button type="button" onclick="openKategoriModal()"
                    class="w-full rounded-[12px] bg-[#326db5] hover:bg-[#235089] text-white font-semibold px-5 py-2.5 shadow transition inline-flex items-center justify-center gap-2 text-base -mt-1">
                    <i class="fa-solid fa-folder-plus"></i> Tambah Kategori
                </button>

                {{-- Card Kategori --}}
                <section class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-[#2563a9] font-semibold text-lg border-b pb-2 mb-3">Kategori Dokumen</h3>
                    <ul class="space-y-2">
                        @foreach($kategori as $kat)
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="text-sm text-[#232323]">{{ $kat->nama_kategoridokumen }}</span>
                                </span>
                                <span class="flex gap-1">
                                    <button type="button"
                                        onclick="openEditKategoriModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategoridokumen) }}')"
                                        class="px-2 py-1 rounded hover:bg-yellow-100" title="Edit">
                                        <i class="fa-solid fa-pen text-yellow-500 text-xs"></i>
                                    </button>
                                    <form action="{{ route('kasubbidang.kategori-dokumen.destroy', $kat->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="px-2 py-1 rounded hover:bg-red-100" title="Hapus">
                                            <i class="fa-solid fa-trash text-red-600 text-xs"></i>
                                        </button>
                                    </form>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </aside>

            {{-- MODAL PASSWORD --}}
            <div id="passwordModal" class="fixed inset-0 z-50 hidden bg-black/50 place-items-center">
                <div class="bg-white rounded-xl shadow-lg p-6 w-80 max-w-full">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Masukkan Kunci Dokumen</h3>
                    <input id="modalPasswordInput" type="password" placeholder="Kunci Dokumen"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="hidden" id="dokumenId" />
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold">Batal</button>
                        <button type="button" onclick="submitPassword()"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold">Submit</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // === Klik baris tabel → Show dokumen
    document.querySelectorAll('tr.row-dokumen').forEach(row => {
        row.addEventListener('click', e => {
            if(e.target.closest('.js-no-row')) return; // abaikan tombol hapus
            if(e.target.closest('.js-edit-key')) return; // abaikan tombol edit
            const id = row.dataset.id;
            const rahasia = row.dataset.rahasia === '1';
            if(rahasia){
                showPasswordModal(id, 'view');
            } else {
                window.location.href = `/kasubbidang/manajemendokumen/${id}`;
            }
        });
    });

    // === Klik tombol edit → Edit dokumen
    document.querySelectorAll('.js-edit-key').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation(); // <<-- penting biar gak ikut event row
            const id = btn.dataset.id;
            const rahasia = btn.dataset.rahasia === '1';
            if(rahasia){
                showPasswordModal(id, 'edit');
            } else {
                window.location.href = `/kasubbidang/manajemendokumen/${id}/edit`;
            }
        });
    });
});

// === Modal password
function showPasswordModal(dokumenId, type='view'){
    const input = document.getElementById('modalPasswordInput');
    const hidden = document.getElementById('dokumenId');
    hidden.value = dokumenId;
    hidden.dataset.type = type;
    input.value = '';
    toggleModal('passwordModal', true);
}

function closeModal(){
    toggleModal('passwordModal', false);
}

function submitPassword(){
    const dokumenId = document.getElementById('dokumenId').value;
    const type = document.getElementById('dokumenId').dataset.type;
    const password = document.getElementById('modalPasswordInput').value.trim();

    if(!password){
        alert('Kunci tidak boleh kosong.');
        return;
    }

    if(type === 'edit'){
        window.location.href = `/kasubbidang/manajemendokumen/${dokumenId}/edit?encrypted_key=${encodeURIComponent(password)}`;
    } else {
        window.location.href = `/kasubbidang/manajemendokumen/${dokumenId}?encrypted_key=${encodeURIComponent(password)}`;
    }
}

// === Hapus dokumen
function showHapusModal(id){
    Swal.fire({
        title: 'Yakin ingin menghapus dokumen ini?',
        text: 'Data yang sudah dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then(res=>{
        if(res.isConfirmed){
            document.getElementById('form-hapus-'+id)?.submit();
        }
    });
}

// === Util modal
function toggleModal(modalId, show){
    const modal = document.getElementById(modalId);
    if(!modal) return;
    if(show){
        modal.classList.remove('hidden');
        modal.classList.add('grid');
    } else {
        modal.classList.add('hidden');
        modal.classList.remove('grid');
    }
}
</script>

</x-app-layout>
