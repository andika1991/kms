<?php

namespace App\Http\Controllers;
use App\Models\Bidang;
use App\Models\SubBidang;
use Illuminate\Http\Request;
use App\Models\KategoriDokumen;

class KategoriDokumenadminController extends Controller
{
    // Menampilkan semua kategori dokumen
    public function index()
    {
                  $kategori = KategoriDokumen::all();
    $bidangList = Bidang::all();
    $subbidangList = SubBidang::all();

    return view('admin.dokumen.index', compact('kategori', 'bidangList', 'subbidangList'));
    }

    // Menampilkan data kategori untuk form edit (optional JSON)
    public function edit($id)
    {
           $kategori = KategoriDokumen::all();
    $bidangList = Bidang::all();
    $subbidangList = SubBidang::all();

    return view('admin.dokumen.index', compact('kategori', 'bidangList', 'subbidangList'));
    }

    // Mengupdate kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->nama_kategoridokumen = $request->nama_kategori;
        $kategori->save();

        return redirect()->route('admin.manajemendokumen.index')->with('success', 'Kategori berhasil diupdate!');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.manajemendokumen.index')->with('deleted', 'Kategori berhasil dihapus!');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        KategoriDokumen::create([
            'nama_kategoridokumen' => $request->nama_kategori
        ]);

        return redirect()->route('admin.manajemendokumen.index')
            ->with('success', 'Kategori dokumen berhasil ditambahkan!');
    }
}
