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
    $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user']);
    $dokumen = $dokumenQuery->latest()->get();

    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $dokumen = $dokumen->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->nama_dokumen ?? ''), $search);
        });
    }

    return view('kepalabagian.dokumen.index', compact('dokumen'));
}

   public function create()
{
   
    $bidangId = auth()->user()->role->bidang_id;

     $kategori = \App\Models\KategoriDokumen::with('subbidang')
        ->where('bidang_id', $bidangId)
        ->get();
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

        try {
            // Dekripsi kunci yang disimpan di database
            $decryptedKey = decrypt($dokumen->encrypted_key);
        } catch (\Exception $e) {
            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('error', 'Data kunci dokumen tidak valid.');
        }

        // Cocokkan input dengan hasil dekripsi
        if ($inputKey !== $decryptedKey) {
            return redirect()->route('kepalabagian.manajemendokumen.index')
                ->with('error', 'Kunci dokumen salah.');
        }
    }

    // Tampilkan halaman dokumen jika lolos semua pemeriksaan
    return view('kepalabagian.dokumen.show', compact('dokumen'));
}


    public function edit(Dokumen $dokumen)
    {
        $kategori = KategoriDokumen::all();

        return view('kepalabagian.dokumen.edit', compact('dokumen', 'kategori'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'nama_dokumen' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori_dokumen_id' => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen' => ['nullable', 'file', 'max:10240'],
        ]);

        if ($request->hasFile('path_dokumen')) {
            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
        }

        $dokumen->update($validated);

        return redirect()->route('kepalabagian.manajemendokumen.index')
                         ->with('success', 'Dokumen berhasil diperbarui.');
    }

   
public function destroy(Dokumen $dokumen)
{
    try {
        if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
            Storage::disk('public')->delete($dokumen->path_dokumen);
        }

        $dokumen->delete();

        return redirect()->route('kepalabagian.manajemendokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    } catch (\Exception $e) {
        return redirect()->route('kepalabagian.manajemendokumen.index')
            ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
    }
}








}




