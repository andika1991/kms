<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengetahuan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriPengetahuanController extends Controller
{
public function index()
{
    $user = Auth::user();

    // Ambil bidang_id dari relasi role
    $role = $user->role;

    if (!$role || !$role->bidang_id) {
        return back()->withErrors(['error' => 'Bidang tidak ditemukan pada akun Anda.']);
    }

    $bidangId = $role->bidang_id;
    $kategori = KategoriPengetahuan::where('bidang_id', $bidangId)->get();

    return view('kepalabagian.kategoripengetahuan', compact('kategori'));
}
      public function create()
    {
        return view('kepalabagian.kategoripengetahuan-create');
    }
  public function store(Request $request)
{
    $request->validate([
        'nama_kategoripengetahuan' => ['required', 'string', 'max:255'],
        'deskripsi' => ['nullable', 'string'],
    ]);

    // Ambil role user
    $roleId = Auth::user()->role_id;

    // Ambil data role
    $role = Role::find($roleId);

    // Jika tidak ditemukan, beri error
    if (!$role) {
        return back()->withErrors(['error' => 'Role tidak ditemukan.']);
    }

    $bidangId = $role->bidang_id;

    KategoriPengetahuan::create([
        'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
        'deskripsi' => $request->deskripsi,
        'bidang_id' => $bidangId,
    ]);

    return redirect()
        ->route('kepalabagian.kategoripengetahuan.index')
        ->with('success', 'Kategori Pengetahuan berhasil ditambahkan.');
}

  public function update(Request $request, KategoriPengetahuan $kategoripengetahuan)
    {
        $request->validate([
            'nama_kategoripengetahuan' => 'required|string|max:255',
        ]);

        $kategoripengetahuan->update([
            'nama_kategoripengetahuan' => $request->nama_kategoripengetahuan,
        ]);

        return redirect()->route('kepalabagian.kategoripengetahuan.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    public function edit(KategoriPengetahuan $kategoripengetahuan)
    {
        return view('kepalabagian.kategoripengetahuan-edit', compact('kategoripengetahuan'));
    }
    public function destroy(KategoriPengetahuan $kategoripengetahuan)
    {
        $kategoripengetahuan->delete();

        return redirect()->route('kepalabagian.kategoripengetahuan.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
