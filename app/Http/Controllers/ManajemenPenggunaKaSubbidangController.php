<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ManajemenPenggunaKaSubbidangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subbidangId = $user->role->subbidang_id ?? null;

        $penggunas = User::whereHas('role', function ($query) use ($subbidangId) {
            $query->where('subbidang_id', $subbidangId);
        })->get();

        return view('kasubbidang.manajemenpengguna.index', compact('penggunas'));
    }

    public function edit($id)
    {
        $pengguna = User::findOrFail($id);
        return view('kasubbidang.manajemenpengguna.edit', compact('pengguna'));
    }



//...

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'nullable|string|min:6', // password opsional
    ]);

    $pengguna = User::findOrFail($id);

    $pengguna->name = $request->name;
    $pengguna->email = $request->email;

    if ($request->filled('password')) {
        $pengguna->password = Hash::make($request->password);
    }

    $pengguna->save();

    return redirect()->route('kasubbidang.manajemenpengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
}


    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('kasubbidang.manajemenpengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    
    public function verifikasi($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->verified = true;
        $pengguna->save();

        return redirect()->route('kasubbidang.manajemenpengguna.index')->with('success', 'Pengguna berhasil diverifikasi.');
    }
}
