<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\FotoKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatankasubidangController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Kegiatan::with(['subbidang', 'pengguna'])
            ->where('pengguna_id', $userId);

        if ($request->filled('subbidang_id')) {
            $query->where('subbidang_id', $request->subbidang_id);
        }

        $kegiatan = $query->latest()->get();

        return view('kasubbidang.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        $user = auth()->user();
        $role = $user->role;

        $subbidangId = $role->subbidang_id ?? null;

        if (!$subbidangId) {
            return redirect()->back()->with('error', 'Anda belum memiliki subbidang.');
        }

        return view('kasubbidang.kegiatan.create', compact('subbidangId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
            'pengguna_id' => Auth::id(),
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');

                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto' => $path,
                ]);
            }
        }

        return redirect()->route('kasubbidang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('fotokegiatan');

        return view('kasubbidang.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan->load('fotokegiatan');

        return view('kasubbidang.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');

                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto' => $path,
                ]);
            }
        }

        return redirect()->route('kasubbidang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('kasubbidang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
