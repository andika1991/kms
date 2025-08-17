<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrupChat;
use App\Models\User;
use App\Models\Bidang;
use App\Models\GrupChatUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ForumMagangController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Daftar grup: yang berisi user saat ini ATAU grup publik pada bidang user
        $grupchats = GrupChat::where(function ($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('pengguna_id', $user->id);
                });
            })
            ->orWhere(function ($query) use ($user) {
                if ($user->role && $user->role->bidang_id) {
                    $query->where('is_private', false)
                          ->where('bidang_id', $user->role->bidang_id);
                }
            })
            ->latest()
            ->get();

        return view('magang.forum.index', compact('grupchats'));
    }

    public function create()
    {
        $user = Auth::user();
        $priorRoleId   = optional($user->role)->id;
        $priorBidangId = optional($user->role)->bidang_id;

        // === AMBIL SEMUA USER lintas role & bidang, lalu decrypt tampilannya ===
        $users = User::with('role')->get()->map(function ($u) {
            try { $u->decrypted_name  = Crypt::decryptString($u->getRawOriginal('name')); }
            catch (\Exception $e) { $u->decrypted_name = $u->name; }
            try { $u->decrypted_email = Crypt::decryptString($u->getRawOriginal('email')); }
            catch (\Exception $e) { $u->decrypted_email = $u->email; }
            return $u;
        });

        // Prioritaskan user dengan role yang sama (mis. Magang) tampil lebih dulu
        $users = $users->sortBy(function ($u) use ($priorRoleId) {
            $sameRole = (int) ((optional($u->role)->id) === $priorRoleId) ? 0 : 1; // 0 duluan
            return sprintf('%d-%06d', $sameRole, $u->id);
        })->values();

        // === AMBIL SEMUA BIDANG; prioritaskan bidang user login di urutan paling atas ===
        $bidangs = Bidang::all()->sortBy(function ($b) use ($priorBidangId) {
            $isPrior = ($b->id == $priorBidangId) ? 0 : 1; // 0 duluan
            $nama = $b->nama ?? $b->nama_bidang ?? '';
            return sprintf('%d-%s', $isPrior, mb_strtolower($nama));
        })->values();

        return view('magang.forum.create', compact('users', 'bidangs'));
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
                'nama_grup'   => $validated['nama_grup'],
                'deskripsi'   => $validated['deskripsi'] ?? null,
                'grup_role'   => $validated['grup_role'] ?? null,
                'is_private'  => $request->has('is_private') ? 1 : 0,
                'bidang_id'   => $validated['bidang_id'] ?? null,
            ]);

            // Anggota minimal: pembuat
            $anggota = [auth()->id()];

            // Jika private, tambahkan pilihan user
            if ($grupChat->is_private && isset($validated['pengguna_id'])) {
                foreach ($validated['pengguna_id'] as $userId) {
                    $anggota[] = $userId;
                }
            }

            $anggota = array_unique($anggota);

            foreach ($anggota as $userId) {
                GrupChatUser::create([
                    'grupchat_id' => $grupChat->id,
                    'pengguna_id' => $userId,
                ]);
            }

            DB::commit();

            return redirect()->route('magang.forum.index')
                             ->with('success', 'Forum berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = auth()->user();

        // Grup saat ini
        $grupChat = GrupChat::with('bidang')->findOrFail($id);

        // Anggota grup
        $anggota = $grupChat->users()->get();

        // Pesan (dengan dekripsi aman)
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

        // ===== Forum List untuk sidebar kanan =====
        // Prioritaskan forum pada bidang yang sama dengan forum yang sedang dibuka.
        // Hormati privasi: tampilkan forum publik ATAU forum private yang memang diikuti user login.
        $forumListQuery = GrupChat::query()
            ->when($grupChat->bidang_id, function ($q) use ($grupChat) {
                $q->where('bidang_id', $grupChat->bidang_id);
            }, function ($q) use ($user) {
                // fallback jika forum tidak punya bidang: pakai bidang user bila ada
                if (optional($user->role)->bidang_id) {
                    $q->where('bidang_id', $user->role->bidang_id);
                }
            })
            ->where(function ($q) use ($user) {
                $q->where('is_private', false)
                  ->orWhereHas('users', function ($qq) use ($user) {
                      $qq->where('pengguna_id', $user->id);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(8);

        $forumList = $forumListQuery->get(['id', 'nama_grup']);

        return view('magang.forum.show', compact('grupChat', 'anggota', 'messages', 'forumList'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $grupchat = GrupChat::findOrFail($id);
        $priorRoleId   = optional($user->role)->id;
        $priorBidangId = optional($user->role)->bidang_id;

        // === SEMUA USER (lintas role/bidang) + prioritas role sama ===
        $users = User::with('role')->get()->map(function ($u) {
            try { $u->decrypted_name  = Crypt::decryptString($u->getRawOriginal('name')); }
            catch (\Exception $e) { $u->decrypted_name = $u->name; }
            try { $u->decrypted_email = Crypt::decryptString($u->getRawOriginal('email')); }
            catch (\Exception $e) { $u->decrypted_email = $u->email; }
            return $u;
        });
        $users = $users->sortBy(function ($u) use ($priorRoleId) {
            $sameRole = (int) ((optional($u->role)->id) === $priorRoleId) ? 0 : 1;
            return sprintf('%d-%06d', $sameRole, $u->id);
        })->values();

        // Anggota yang sudah bergabung
        $anggota_ids = $grupchat->users->pluck('id')->toArray();

        // === SEMUA BIDANG + prioritas bidang user login ===
        $bidangs = Bidang::all()->sortBy(function ($b) use ($priorBidangId) {
            $isPrior = ($b->id == $priorBidangId) ? 0 : 1;
            $nama = $b->nama ?? $b->nama_bidang ?? '';
            return sprintf('%d-%s', $isPrior, mb_strtolower($nama));
        })->values();

        return view('magang.forum.edit', compact('grupchat', 'users', 'anggota_ids', 'bidangs'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_grup'    => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'grup_role'    => 'nullable|string|max:255',
            'is_private'   => 'boolean',
            'bidang_id'    => 'nullable|exists:bidang,id',
            'pengguna_id'  => 'nullable|array',
            'pengguna_id.*'=> 'exists:pengguna,id',
        ]);

        DB::beginTransaction();

        try {
            $grupchat = GrupChat::findOrFail($id);

            $grupchat->update([
                'nama_grup'  => $validated['nama_grup'],
                'deskripsi'  => $validated['deskripsi'] ?? null,
                'grup_role'  => $validated['grup_role'] ?? null,
                'is_private' => $request->has('is_private') ? 1 : 0,
                'bidang_id'  => $validated['bidang_id'] ?? null,
            ]);

            $anggota = [auth()->id()];

            if ($grupchat->is_private && isset($validated['pengguna_id'])) {
                foreach ($validated['pengguna_id'] as $userId) {
                    $anggota[] = $userId;
                }
            }

            $anggota = array_unique($anggota);

            GrupChatUser::where('grupchat_id', $grupchat->id)->delete();

            foreach ($anggota as $userId) {
                GrupChatUser::create([
                    'grupchat_id' => $grupchat->id,
                    'pengguna_id' => $userId,
                ]);
            }

            DB::commit();

            return redirect()->route('magang.forum.index')
                             ->with('success', 'Forum berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $grupchat = GrupChat::findOrFail($id);
        $grupchat->delete();

        return redirect()->route('magang.forum.index')
                         ->with('deleted', 'Forum berhasil dihapus.');
    }
}
