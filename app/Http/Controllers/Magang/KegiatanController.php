<?php

namespace App\Http\Controllers\Magang;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\FotoKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Kegiatan::with(['subbidang', 'pengguna','fotokegiatan'])
            ->where('pengguna_id', $userId);

        if ($request->filled('subbidang_id')) {
            $query->where('subbidang_id', $request->subbidang_id);
        }

        $kegiatan = $query->latest()->get();

        return view('magang.kegiatan.index', compact('kegiatan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $role = $user->role;

        $subbidangId = $role->subbidang_id ?? null;

        if (!$subbidangId) {
            return redirect()->back()->with('error', 'Anda belum memiliki subbidang.');
        }

        return view('magang.kegiatan.create', compact('subbidangId'));
    }


    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan data kegiatan
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
            'pengguna_id' => Auth::id(),
        ]);

        // Simpan foto jika ada
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');

                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto' => $path,
                ]);
            }
        }

        return redirect()->route('magang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        // eager load foto-foto kegiatan
        $kegiatan->load('fotokegiatan');

        return view('magang.kegiatan.show', compact('kegiatan'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {      $kegiatan->load('fotokegiatan');
        return view('magang.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update data kegiatan yang sudah ada
        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
        ]);

        // Simpan foto baru jika ada
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');

                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto' => $path,
                ]);
            }
        }

        return redirect()->route('magang.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('magang.kegiatan.index')
            ->with('deleted', 'Kegiatan berhasil dihapus.');
    }
}
