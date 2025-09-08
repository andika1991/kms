<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\FotoKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KegiatanpegawaiController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Kegiatan::with(['subbidang', 'pengguna', 'fotokegiatan'])
            ->where('pengguna_id', $userId)
            ->when($request->filled('search'), fn ($q) =>
                $q->where('nama_kegiatan', 'like', '%'.$request->search.'%')
            )
            ->withCount('views') // -> views_count
            ->withCount([
                'views as views_today' => function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                },
                'views as views_unique' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT ip)'));
                },
            ]);

        if ($request->filled('subbidang_id')) {
            $query->where('subbidang_id', $request->subbidang_id);
        }

        $kegiatan = $query->latest()->get();

        return view('pegawai.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        $user = auth()->user();
        $role = $user->role;

        $subbidangId = $role->subbidang_id ?? null;
        $bidangId    = $role->bidang_id ?? null;

        return view('pegawai.kegiatan.create', compact('subbidangId', 'bidangId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'       => 'required|string|max:255',
            'deskripsi_kegiatan'  => 'required|string',
            'kategori_kegiatan'   => 'required|in:publik,internal',
            'subbidang_id'        => 'nullable|exists:subbidang,id',
            'bidang_id'           => 'nullable|exists:bidang,id',
            'thumbnail_kegiatan'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'foto_kegiatan.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan data kegiatan
        $kegiatan = Kegiatan::create([
            'nama_kegiatan'       => $request->nama_kegiatan,
            'deskripsi_kegiatan'  => $request->deskripsi_kegiatan,
            'kategori_kegiatan'   => $request->kategori_kegiatan,
            'subbidang_id'        => $request->subbidang_id,
            'bidang_id'           => $request->bidang_id,
            'pengguna_id'         => Auth::id(),
        ]);

        // Thumbnail jadi foto utama
        if ($request->hasFile('thumbnail_kegiatan')) {
            $thumbPath = $request->file('thumbnail_kegiatan')->store('foto_kegiatan', 'public');
            FotoKegiatan::create([
                'kegiatan_id' => $kegiatan->id,
                'path_foto'   => $thumbPath,
            ]);
        }

        // Foto tambahan
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');
                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto'   => $path,
                ]);
            }
        }

        return redirect()->route('pegawai.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        // Eager load foto & metrik views untuk ikon 👁️
        $kegiatan->load('fotokegiatan')
            ->loadCount('views')
            ->loadCount([
                'views as views_today' => function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                },
                'views as views_unique' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT ip)'));
                },
            ]);

        return view('pegawai.kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan->load('fotokegiatan');

        return view('pegawai.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan'       => 'required|string|max:255',
            'deskripsi_kegiatan'  => 'required|string',
            'kategori_kegiatan'   => 'required|in:publik,internal',
            'subbidang_id'        => 'nullable|exists:subbidang,id',
            'thumbnail_kegiatan'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'foto_kegiatan.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kegiatan->update([
            'nama_kegiatan'       => $request->nama_kegiatan,
            'deskripsi_kegiatan'  => $request->deskripsi_kegiatan,
            'kategori_kegiatan'   => $request->kategori_kegiatan,
            'subbidang_id'        => $request->subbidang_id,
        ]);

        // Ganti thumbnail bila ada file baru
        if ($request->hasFile('thumbnail_kegiatan')) {
            $oldThumb = $kegiatan->fotokegiatan()->orderBy('id', 'asc')->first();
            if ($oldThumb) {
                if (Storage::disk('public')->exists($oldThumb->path_foto)) {
                    Storage::disk('public')->delete($oldThumb->path_foto);
                }
                $oldThumb->delete();
            }
            $thumbPath = $request->file('thumbnail_kegiatan')->store('foto_kegiatan', 'public');
            FotoKegiatan::create([
                'kegiatan_id' => $kegiatan->id,
                'path_foto'   => $thumbPath,
            ]);
        }

        // Foto baru (tambahan)
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                $path = $file->store('foto_kegiatan', 'public');
                FotoKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'path_foto'   => $path,
                ]);
            }
        }

        return redirect()->route('pegawai.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        // hapus file foto dari storage juga
        $kegiatan->load('fotokegiatan');
        foreach ($kegiatan->fotokegiatan as $foto) {
            if (Storage::disk('public')->exists($foto->path_foto)) {
                Storage::disk('public')->delete($foto->path_foto);
            }
            $foto->delete();
        }

        $kegiatan->delete();

        return redirect()->route('pegawai.kegiatan.index')
            ->with('deleted', 'Kegiatan berhasil dihapus.');
    }
}
