<?php

namespace App\Http\Controllers;

use App\Models\GrupChat;
use App\Models\User;
use App\Models\Bidang;
use App\Models\GrupChatUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ForumsekreController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil grup chat yang memiliki user ini sebagai anggota
        $grupchats = GrupChat::whereHas('users', function ($query) use ($user) {
            $query->where('pengguna_id', $user->id);
        })
        ->latest()
        ->get();

        return view('sekretaris.forum.index', compact('grupchats'));
    }


    public function create()
    {
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

        $bidangs = Bidang::all();

        return view('sekretaris.forum.create', compact('users', 'bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_grup'     => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'grup_role'     => 'nullable|string|max:255',
            'is_private'    => 'nullable|boolean',
            'bidang_id'     => 'nullable|exists:bidang,id',
            'pengguna_id'   => 'nullable|array',
            'pengguna_id.*' => 'exists:pengguna,id',
        ]);

        DB::beginTransaction();

        try {
            $grupChat = GrupChat::create([
                'nama_grup'    => $validated['nama_grup'],
                'deskripsi'    => $validated['deskripsi'] ?? null,
                'grup_role'    => $validated['grup_role'] ?? null,
                'is_private'   => $request->has('is_private') ? 1 : 0,
                'bidang_id'    => $validated['bidang_id'] ?? null,
                'pengguna_id'  => auth()->id(),
            ]);

            $anggota = [auth()->id()];

            if ($grupChat->is_private && isset($validated['pengguna_id'])) {
                foreach ($validated['pengguna_id'] as $userId) {
                    $anggota[] = $userId;
                }
            }

            foreach (array_unique($anggota) as $userId) {
                GrupChatUser::create([
                    'grupchat_id' => $grupChat->id,
                    'pengguna_id' => $userId,
                ]);
            }

            DB::commit();
            return redirect()->route('sekretaris.forum.index')->with('success', 'Forum berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_grup' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'grup_role' => 'nullable|string|max:255',
            'is_private' => 'boolean',
            'bidang_id' => 'nullable|exists:bidang,id',
            'pengguna_id' => 'nullable|array',
            'pengguna_id.*' => 'exists:pengguna,id',
        ]);

        DB::beginTransaction();

        try {
            $grupchat = GrupChat::findOrFail($id);

            // Update data grup chat
            $grupchat->update([
                'nama_grup' => $validated['nama_grup'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'grup_role' => $validated['grup_role'] ?? null,
                'is_private' => $request->has('is_private') ? 1 : 0,
                'bidang_id' => $validated['bidang_id'] ?? null,
            ]);

            // Kelola anggota grup
            $anggota = [];

            // Pastikan pembuat grup tetap ada di grup
            $anggota[] = auth()->id();

            if ($grupchat->is_private && isset($validated['pengguna_id'])) {
                foreach ($validated['pengguna_id'] as $userId) {
                    $anggota[] = $userId;
                }
            }

            $anggota = array_unique($anggota);

            // Hapus anggota lama
            GrupChatUser::where('grupchat_id', $grupchat->id)->delete();

            // Tambahkan anggota baru
            foreach ($anggota as $userId) {
                GrupChatUser::create([
                    'grupchat_id' => $grupchat->id,
                    'pengguna_id' => $userId,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('sekretaris.forum.index')
                ->with('success', 'Forum berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $grupChat = GrupChat::findOrFail($id);

        $anggota = $grupChat->users()->get();

        $messages = $grupChat->messages()
            ->with('pengguna')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                try {
                    $message->decrypted_message = Crypt::decryptString($message->message);
                } catch (\Exception $e) {
                    $message->decrypted_message = '[pesan tidak dapat didekripsi]';
                }
                return $message;
            });
        
        $user = auth()->user();
        $forumList = \App\Models\GrupChat::whereHas('users', function ($query) use ($user) {
            $query->where('pengguna_id', $user->id);
        })
        ->orderBy('nama_grup')
        ->get();

        return view('sekretaris.forum.show', compact('grupChat', 'anggota', 'messages','forumList'));
    }

    public function destroy($id)
    {
        $grupChat = GrupChat::findOrFail($id);
        $grupChat->delete();

        return redirect()->route('sekretaris.forum.index')->with('deleted', 'Forum berhasil dihapus.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $grupchat = GrupChat::findOrFail($id);

        // Ambil semua user dan decrypt name + email
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

        // Ambil ID anggota grup
        $anggota_ids = $grupchat->users->pluck('id')->toArray();

        // Ambil bidang dari user yang sedang login
        $bidangs = Bidang::where('id', $user->role->bidang_id)->get();

        return view('sekretaris.forum.edit', compact('grupchat', 'users', 'anggota_ids', 'bidangs'));
    }

}
