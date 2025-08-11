<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengetahuan;
use App\Models\ArtikelPengetahuan;
use Illuminate\Http\Request;

class KategoriPengetahuankasekretarisController extends Controller
{
    /**
     * Tampilkan seluruh kategori pengetahuan sekretaris.
     * Hanya kategori yang tidak terkait bidang dan subbidang.
     */
    public function index()
    {
        $artikels = ArtikelPengetahuan::latest()->get();
        $kategoriPengetahuans = KategoriPengetahuan::all();
        // Ambil kategori yang bidang_id & subbidang_id NULL (khusus sekretaris)
        $kategori = KategoriPengetahuan::with(['bidang', 'subbidang'])
            ->whereNull('bidang_id')
            ->whereNull('subbidang_id')
            ->latest()
            ->get();

        // Kirim data kategori ke view index
        return view('sekretaris.kategoripengetahuan.index', compact('artikels', 'kategoriPengetahuans'));
    }

    /**
     * Tampilkan form tambah kategori pengetahuan.
     */
    public function create()
    {
        // Cukup kembalikan view create
        return view('sekretaris.kategoripengetahuan.create');
    }

    /**
     * Simpan kategori pengetahuan baru dari form create.
     */
    public function store(Request $request)
    {
        // Validasi data, hanya nama_kategoripengetahuan yang wajib
        $request->validate([
            'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        // Simpan kategori baru ke database
        KategoriPengetahuan::create([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('sekretaris.berbagipengetahuan.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit untuk kategori tertentu.
     */
    public function edit(KategoriPengetahuan $kategoripengetahuan)
    {
        // Kirim data kategori ke view edit
        return view('sekretaris.kategoripengetahuan.edit', compact('kategoripengetahuan'));
    }

    /**
     * Update data kategori pengetahuan yang diedit.
     */
    public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        // Validasi data, sama seperti create
        $request->validate([
            'nama_kategoripengetahuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Update data kategori di database
        $kategoripengetahuan->update([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
        ]);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('sekretaris.berbagipengetahuan.index')
            ->with('success', 'Kategori Pengetahuan berhasil diperbarui.');
    }

    /**
     * Hapus kategori pengetahuan.
     */
    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        // Hapus kategori dari database
        $kategoripengetahuan->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('sekretaris.berbagipengetahuan.index')
            ->with('deleted', 'Kategori Pengetahuan berhasil dihapus.');
    }
}