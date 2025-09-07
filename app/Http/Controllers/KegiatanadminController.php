<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\FotoKegiatan;
use App\Models\Subbidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 

class KegiatanadminController extends Controller
{
    // Tampilkan semua kegiatan (dari semua subbidang)
    public function index(Request $request)
    {
        $query = Kegiatan::with(['subbidang', 'pengguna', 'fotokegiatan'])
            ->withCount('views') // -> views_count
            ->withCount([
                'views as views_today' => function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                },
                'views as views_unique' => function ($q) {
                    // hitung unique view berdasarkan ip
                    $q->select(DB::raw('COUNT(DISTINCT ip)'));
                },
            ]);

        if ($request->filled('subbidang_id')) {
            $query->where('subbidang_id', $request->subbidang_id);
        }

        if ($request->filled('kategori_kegiatan')) {
            $query->where('kategori_kegiatan', $request->kategori_kegiatan);
        }

        $kegiatan = $query->latest()->get();
        $subbidangList = Subbidang::all();

        return view('admin.kegiatan.index', compact('kegiatan', 'subbidangList'));
    }

    // Lihat detail kegiatan
    public function show($id)
    {
        $kegiatan = Kegiatan::with('fotokegiatan')
            // ===== VIEWS COUNTS UNTUK IKON 👁️ =====
            ->withCount('views') // -> views_count
            ->withCount([
                'views as views_today' => function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                },
                'views as views_unique' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT ip)'));
                },
            ])
            ->findOrFail($id);

        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    // Form edit
    public function edit($id)
    {
        $kegiatan = Kegiatan::with('fotokegiatan')->findOrFail($id);
        $subbidangList = Subbidang::all();
        return view('admin.kegiatan.edit', compact('kegiatan', 'subbidangList'));
    }

    // Update kegiatan
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'kategori_kegiatan' => 'required|in:publik,internal',
            'subbidang_id' => 'nullable|exists:subbidang,id',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
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

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    // Hapus kegiatan beserta foto
    public function destroy($id)
    {
        $kegiatan = Kegiatan::with('fotokegiatan')->findOrFail($id);

        foreach ($kegiatan->fotokegiatan as $foto) {
            if (Storage::disk('public')->exists($foto->path_foto)) {
                Storage::disk('public')->delete($foto->path_foto);
            }
            $foto->delete();
        }

        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')->with('deleted', 'Kegiatan berhasil dihapus.');
    }

    // Form tambah kegiatan
    public function create(Request $request)
    {
        $subbidangId = $request->input('subbidang_id');
        $subbidangList = Subbidang::with('bidang')->get();

        return view('admin.kegiatan.create', compact('subbidangId', 'subbidangList'));
    }

    // Simpan kegiatan baru
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
            'pengguna_id' => auth()->id(),
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

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }
}
