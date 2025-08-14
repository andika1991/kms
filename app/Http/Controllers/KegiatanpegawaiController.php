<?php

namespace App\Http\Controllers;
use App\Models\Kegiatan;
use App\Models\FotoKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class KegiatanpegawaiController extends Controller
{
   public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Kegiatan::with(['subbidang', 'pengguna'])
            ->where('pengguna_id', $userId)
            ->when($request->filled('search'), fn($q) =>
                $q->where('nama_kegiatan', 'like', '%'.$request->search.'%')
            );

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
        $bidangId = $role->bidang_id ?? null;

        return view('pegawai.kegiatan.create', compact('subbidangId', 'bidangId'));
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
            'bidang_id' => 'nullable|exists:bidang,id',
            'thumbnail_kegiatan'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan data kegiatan
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
            'bidang_id' => $request->bidang_id,
            'pengguna_id' => Auth::id(),
        ]);

        // Thumbnail jadi foto utama (disimpan terlebih dulu)
        if ($request->hasFile('thumbnail_kegiatan')) {
            $thumbPath = $request->file('thumbnail_kegiatan')->store('foto_kegiatan', 'public');
            FotoKegiatan::create([
                'kegiatan_id' => $kegiatan->id,
                'path_foto'   => $thumbPath,
            ]);
        }

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

        return redirect()->route('pegawai.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        // eager load foto-foto kegiatan
        $kegiatan->load('fotokegiatan');

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
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'thumbnail_kegiatan'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update data kegiatan yang sudah ada
        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'subbidang_id' => $request->subbidang_id,
        ]);

        // ganti thumbnail bila diunggah
        if ($request->hasFile('thumbnail_kegiatan')) {
            $oldThumb = $kegiatan->fotokegiatan()->orderBy('id','asc')->first(); // anggap entri pertama = thumbnail lama
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

        return redirect()->route('pegawai.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('pegawai.kegiatan.index')
            ->with('deleted', 'Kegiatan berhasil dihapus.');
    }
}
