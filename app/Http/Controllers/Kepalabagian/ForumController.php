<?php

namespace App\Http\Controllers\Kepalabagian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupChat;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Bidang;
use App\Models\GrupChatUser;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    public function index()
    {
        $grupchats = GrupChat::latest()->get();

        return view('kepalabagian.forum.index', compact('grupchats'));
    }

  public function create()
{
    // Ambil semua user dan decrypt data yang terenkripsi
    $users = User::all()->map(function ($user) {
        try {
            $user->decrypted_name = Crypt::decryptString($user->getRawOriginal('name'));
        } catch (\Exception $e) {
            $user->decrypted_name = '[decrypt error]';
        }
        try {
            $user->decrypted_email = Crypt::decryptString($user->getRawOriginal('email'));
        } catch (\Exception $e) {
            $user->decrypted_email = '[decrypt error]';
        }
        return $user;
    });

    // Ambil data bidang untuk dropdown
    $bidangs = Bidang::all();

    return view('kepalabagian.forum.create', compact('users', 'bidangs'));
}

  public function store(Request $request)
{
    $validated = $request->validate([
        'nama_grup' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'grup_role' => 'nullable|string|max:255',
        'is_private' => 'nullable|boolean',
        'bidang_id' => 'nullable|exists:bidang,id',
        'pengguna_id' => 'nullable|array',
        'pengguna_id.*' => 'exists:pengguna,id',
    ]);

    DB::beginTransaction();

    try {
        // Simpan grup chat
        $grupChat = GrupChat::create([
            'nama_grup'   => $validated['nama_grup'],
            'deskripsi'   => $validated['deskripsi'] ?? null,
            'grup_role'   => $validated['grup_role'] ?? null,
            'is_private'  => $request->has('is_private') ? 1 : 0,
            'bidang_id'   => $validated['bidang_id'] ?? null,
        ]);

        // Buat array anggota grup
        $anggota = [];

        // Tambahkan user yang membuat grup ke array anggota
        $anggota[] = auth()->id();

        // Jika grup private, tambahkan user yang dipilih ke array anggota
        if ($grupChat->is_private && isset($validated['pengguna_id'])) {
            foreach ($validated['pengguna_id'] as $userId) {
                $anggota[] = $userId;
            }
        }

        // Hapus duplikat (kalau pembuat grup juga dipilih manual)
        $anggota = array_unique($anggota);

        // Simpan ke tabel pivot grup_chat_user
        foreach ($anggota as $userId) {
            GrupChatUser::create([
                'grupchat_id' => $grupChat->id,
                'pengguna_id' => $userId,
            ]);
        }

        DB::commit();

        return redirect()
            ->route('kepalabagian.forum.index')
            ->with('success', 'Forum berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function show($id)
{
    $grupChat = \App\Models\GrupChat::findOrFail($id);

    // Load anggota grup
    $anggota = $grupChat->users()->get();

    // Load pesan-pesan chat
    $messages = $grupChat->messages()
        ->with(['pengguna']) // eager load user
        ->latest()
        ->get();

    return view('kepalabagian.forum.show', compact('grupChat', 'anggota', 'messages'));
}


    public function edit($id)
    {
        $grupchat = GrupChat::findOrFail($id);

        return view('kepalabagian.forum.edit', compact('grupchat'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_grup' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'grup_role' => 'nullable|string|max:255',
            'is_private' => 'boolean',
            'bidang_id' => 'nullable|exists:bidang,id',
        ]);

        $grupchat = GrupChat::findOrFail($id);
        $grupchat->update($validated);

        return redirect()->route('kepalabagian.forum.index')
                         ->with('success', 'Forum berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $grupchat = GrupChat::findOrFail($id);
        $grupchat->delete();

        return redirect()->route('kepalabagian.forum.index')
                         ->with('success', 'Forum berhasil dihapus.');
    }
}
