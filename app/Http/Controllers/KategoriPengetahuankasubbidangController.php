<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengetahuan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriPengetahuankasubbidangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if (!$role || !$role->bidang_id) {
            return back()->withErrors(['error' => 'Bidang tidak ditemukan pada akun Anda.']);
        }

        $bidangId = $role->bidang_id;

        $kategori = KategoriPengetahuan::where('bidang_id', $bidangId)->get();

        return view('kasubbidang.kategoripengetahuan.index', compact('kategori'));
    }

    public function create()
    {
        return view('kasubbidang.kategoripengetahuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $roleId = Auth::user()->role_id;
        $role = Role::find($roleId);

        if (!$role) {
            return back()->withErrors(['error' => 'Role tidak ditemukan.']);
        }

        $bidangId = $role->bidang_id;
         $subbidangId = $role->subbidang_id;

        KategoriPengetahuan::create([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
            'bidang_id' => $bidangId,
            'subbidang_id'=>$subbidangId,
        ]);

        return redirect()
            ->route('kasubbidang.berbagipengetahuan.index')
            ->with('success', 'Kategori Pengetahuan berhasil ditambahkan.');
    }

    public function edit(KategoriPengetahuan $kategoripengetahuan)
    {
        return view('kasubbidang.kategoripengetahuan.edit', compact('kategoripengetahuan'));
    }

    public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        $request->validate([
            'nama_kategoripengetahuan' => 'required|string|max:255',
        ]);

        $kategoripengetahuan->update([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route('kasubbidang.berbagipengetahuan.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        $kategoripengetahuan->delete();

        return redirect()
            ->route('kasubbidang.kategoripengetahuan.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
