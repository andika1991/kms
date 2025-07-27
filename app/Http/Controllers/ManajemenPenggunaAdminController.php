<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\Role;


class ManajemenPenggunaAdminController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     */
    public function index()
    {
        $penggunas = User::latest()->get();
        return view('admin.manajemenpengguna.index', compact('penggunas'));
    }

    /**
     * Tampilkan form edit pengguna.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);


    $roleGroups = Role::select('role_group')->distinct()->pluck('role_group');
    $roles = Role::all(); // Semua role untuk dipilah di JS
        return view('admin.manajemenpengguna.edit', compact('user', 'roleGroups', 'roles'));
    }

    /**
     * Update data pengguna.
     */
   public function update(Request $request, $id)
{
    $pengguna = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
        'password' => 'nullable|string|min:6',
        'role_id' => 'required|exists:role,id',
    ]);

    $pengguna->name = $request->name;
    $pengguna->email = $request->email;
    $pengguna->role_id = $request->role_id;

    if ($request->filled('password')) {
        $pengguna->password = Hash::make($request->password);
    }

    $pengguna->save();

    return redirect()->route('admin.manajemenpengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
}


    /**
     * Hapus pengguna.
     */
    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('admin.manajemenpengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Verifikasi pengguna.
     */
    public function verifikasi($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->verified = true; // Pastikan kolom 'verified' ada di tabel users
        $pengguna->save();

        return redirect()->route('admin.manajemenpengguna.index')->with('success', 'Pengguna berhasil diverifikasi.');
    }

    /**
     * (Opsional) Tampilkan detail pengguna jika dibutuhkan.
     */
    public function show($id)
    {
        $pengguna = User::findOrFail($id);
        return view('admin.manajemenpengguna.show', compact('pengguna'));
    }

    /**
     * (Opsional) Form create user jika admin boleh menambah langsung.
     */
public function create(Request $request)
{
    $subbidangId = $request->input('subbidang_id');

    $roleGroups = Role::select('role_group')->distinct()->pluck('role_group');
    $roles = Role::all(); // Semua role untuk dipilah di JS

    return view('admin.manajemenpengguna.create', compact('subbidangId', 'roleGroups', 'roles'));
}
    /**
     * (Opsional) Simpan user baru jika admin menambah manual.
     */
  public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:pengguna,email',
        'password'  => 'required|string|min:6',
        'role_id'   => 'required|exists:role,id', // Pastikan role_id valid
        // Tambahkan validasi untuk field lain jika perlu (misalnya photo_profil)
    ]);

    User::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'password'      => Hash::make($request->password),
        'role_id'       => $request->role_id,
        'verified'      => true, // Langsung diverifikasi
        'photo_profil'  => null, // Atau $request->photo_profil jika sudah tersedia
    ]);

    return redirect()->route('admin.manajemenpengguna.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
}
}