<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

use Exception;
class ManajemenDokumenController extends Controller
{
    public function index(Request $request)
{
    $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user'])
        ->where('pengguna_id', auth()->id())
        ->latest();

    $dokumen = $dokumenQuery->get();

    // Search setelah auto-decrypt
    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $dokumen = $dokumen->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->nama_dokumen), $search);
        });
    }

    return view('kepalabagian.dokumen.index', compact('dokumen'));
}


    public function create()
    {
    
        $bidangId = auth()->user()->role->bidang_id;

        $kategori = KategoriDokumen::with('subbidang')
        ->when($bidangId, function ($q) use ($bidangId) {
            $q->where(function ($q) use ($bidangId) {
                $q->where('bidang_id', $bidangId)            // kategori spesifik bidang
                  ->orWhereNull('bidang_id')                 // kategori umum
                  ->orWhereHas('subbidang', function ($qq) use ($bidangId) {
                      $qq->where('bidang_id', $bidangId);    // kategori via subbidang bidang ini
                  });
            });
        })
        ->orderBy('nama_kategoridokumen')
        ->get();

        // (opsional) fallback: kalau masih kosong, tampilkan semua agar tidak blank
        if ($kategori->isEmpty()) {
        // Ambil kategori sesuai bidang_id dari role user
$kategori = KategoriDokumen::whereHas('subbidang', function ($q) use ($bidangId) {
        $q->where('bidang_id', $bidangId);
    })
    ->orderBy('nama_kategoridokumen')
    ->get();
        }

        return view('kepalabagian.dokumen.create', compact('kategori'));
    }


    public function store(Request $request)
    {
        // Validasi awal
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['required', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['required', 'file', 'max:10240'],
            'encrypted_key'        => ['nullable', 'string', 'max:255'],
        ]);

        // Ambil data kategori
        $kategori = KategoriDokumen::findOrFail($validated['kategori_dokumen_id']);

        // Cek apakah kategori adalah rahasia
        if (strtolower($kategori->nama_kategoridokumen) === 'rahasia') {
            // encrypted_key wajib diisi
            $request->validate([
                'encrypted_key' => ['required', 'string', 'max:255'],
            ]);

            $validated['encrypted_key'] = $request->encrypted_key;
        } else {
            // Pastikan encrypted_key NULL jika bukan rahasia
            $validated['encrypted_key'] = null;
        }

        if ($request->hasFile('path_dokumen')) {
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        }

        $validated['pengguna_id'] = auth()->id();

        Dokumen::create($validated);

        return redirect()->route('kepalabagian.manajemendokumen.index')
                        ->with('success', 'Dokumen berhasil ditambahkan.');
    }


public function show(Request $request, $id)
{
    // Ambil data dokumen dengan relasi kategori dan user
    $dokumen = Dokumen::with(['kategoriDokumen', 'user'])->findOrFail($id);

    // Cek apakah dokumen termasuk kategori "Rahasia"
    $isRahasia = $dokumen->kategoriDokumen 
        && $dokumen->kategoriDokumen->nama_kategoridokumen === 'Rahasia';

    // Jika dokumen rahasia, cek kunci yang dimasukkan
    if ($isRahasia) {
        $inputKey = $request->encrypted_key;

        if (!$inputKey) {
            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('error', 'Kunci dokumen diperlukan untuk mengakses dokumen rahasia.');
        }

        // Tidak perlu decrypt() lagi, karena sudah otomatis via casts
        if ($inputKey !== $dokumen->encrypted_key) {
            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('error', 'Kunci dokumen salah.');
        }
    }

    // Tampilkan halaman dokumen jika lolos semua pemeriksaan
    return view('kepalabagian.dokumen.show', compact('dokumen'));
}



public function edit(Request $request, Dokumen $dokumen)
{
    $user = auth()->user();

    // 1. Validasi akses: Kepala Bagian hanya bisa edit dokumen di subbidangnya
    if ($dokumen->subbidang_id !== $user->role->subbidang_id) {
        abort(403, 'Anda tidak memiliki akses untuk mengedit dokumen ini.');
    }

    // 2. Cek apakah dokumen kategori Rahasia
    $isRahasia = $dokumen->kategoriDokumen 
        && strtolower($dokumen->kategoriDokumen->nama_kategoridokumen) === 'Rahasia';

    if ($isRahasia) {
        $inputKey = $request->encrypted_key;

        // Kalau belum input kunci
        if (!$inputKey) {
            return back()->with('info', 'Masukkan kunci dokumen untuk mengedit dokumen rahasia.');
        }

        // Karena field terenkripsi, kita decrypt dulu untuk validasi
        try {
            $savedKey = decrypt($dokumen->encrypted_key);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca kunci dokumen.');
        }

        if ($inputKey !== $savedKey) {
            return back()->with('error', 'Kunci dokumen salah.');
        }
    }

    // 3. Ambil daftar kategori sesuai subbidang user
    $kategori = KategoriDokumen::where('bidang_id', $user->role->bidang_id)->get();

    // 4. Tampilkan form edit
    return view('kepalabagian.dokumen.edit', compact('dokumen', 'kategori'));
}



   
    public function destroy(Dokumen $dokumen)
    {
        try {
            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $dokumen->delete();

            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('deleted', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

}
