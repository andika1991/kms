<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\ArticleView;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PengetahuanController extends Controller
{
public function index(Request $request)
{
    // Ambil user yang sedang login
    $user = auth()->user();
    $userId = $user->id;
    $subbidangId = $user->role->subbidang_id ?? null;

    // Query artikel milik user
    $query = ArtikelPengetahuan::where('pengguna_id', $userId);

    // Filter pencarian jika ada input "search"
    if ($request->filled('search')) {
        $query->where('slug', 'like', '%' . $request->search . '%');
    }

    // Ambil hasil, urutkan terbaru
    $artikels = $query->latest()->get();

    // Ambil kategori sesuai subbidang user (misal untuk sidebar)
    $kategoriQuery = KategoriPengetahuan::query();
    if ($subbidangId) {
        $kategoriQuery->where('subbidang_id', $subbidangId);
    }

    $kategori = $kategoriQuery->orderBy('nama_kategoripengetahuan')->get();

    return view('magang.berbagipengetahuan', compact('artikels', 'kategori'));
}

   public function create()
{
    $user = auth()->user();
    $role = $user->role;

    $bidangId = $role->bidang_id ?? null;
    $subbidangId = $role->subbidang_id ?? null;

    // Ambil kategori sesuai bidang/subbidang user
    $kategoriQuery = KategoriPengetahuan::query();

    if ($bidangId) {
        $kategoriQuery->where('bidang_id', $bidangId);
    }

    if ($subbidangId) {
        $kategoriQuery->where('subbidang_id', $subbidangId);
    }

    $kategori = $kategoriQuery->orderBy('nama_kategoripengetahuan')->get();

    return view('magang.artikelpengetahuan-create', compact('kategori'));
}


    public function store(Request $request)
    {
        // [MAGANG] daftar ID kategori khusus magang
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:artikelpengetahuan,slug'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'filedok' => ['nullable', 'file', 'max:5120'],
            'isi' => ['required', 'string'],
            'kategori_pengetahuan_id' => [
                'required',
                'exists:kategori_pengetahuan,id',// [MAGANG] wajib kategori khusus magang
            ],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('filedok')) {
            $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
        }

        $validated['pengguna_id'] = auth()->id();

        ArtikelPengetahuan::create($validated);

        return redirect()
            ->route('magang.berbagipengetahuan.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail artikel (hanya jika milik user login).
     */public function show(ArtikelPengetahuan $berbagipengetahuan)
{
    // Pastikan artikel milik user yang login
    if ($berbagipengetahuan->pengguna_id !== auth()->id()) {
        abort(403, 'Akses Ditolak: Anda tidak memiliki izin melihat artikel ini.');
    }

    // Load relasi kategori dan pengguna
    $berbagipengetahuan->load(['kategoriPengetahuan', 'pengguna']);

    // Catat view jika user login
    if (auth()->check()) {
        ArticleView::updateOrCreate(
            ['artikel_id' => $berbagipengetahuan->id, 'user_id' => auth()->id()],
            ['viewed_at' => now()]
        );
    }

    // Kirim ke view
    return view('magang.berbagipengetahuan-show', [
        'artikelpengetahuan' => $berbagipengetahuan
    ]);
}

    /**
     * Tampilkan form edit artikel.
     */
   public function edit($id)
{
    $user = auth()->user();
    $userId = $user->id;
    $role = $user->role;

    $bidangId = $role->bidang_id ?? null;
    $subbidangId = $role->subbidang_id ?? null;

    // Ambil artikel milik user
    $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
        ->where('pengguna_id', $userId)
        ->firstOrFail();

    // Ambil kategori sesuai bidang/subbidang user
    $kategoriQuery = KategoriPengetahuan::query();
    if ($bidangId) {
        $kategoriQuery->where('bidang_id', $bidangId);
    }
    if ($subbidangId) {
        $kategoriQuery->where('subbidang_id', $subbidangId);
    }
    $kategori = $kategoriQuery->orderBy('nama_kategoripengetahuan')->get();

    return view('magang.berbagipengetahuan-edit', compact('artikelpengetahuan', 'kategori'));
}

  public function update(Request $request, $id)
{
    $userId = auth()->id();

    // Ambil artikel milik user
    $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
        ->where('pengguna_id', $userId)
        ->firstOrFail();

    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug,' . $artikelpengetahuan->id,
        'kategori_pengetahuan_id' => 'required|exists:kategori_pengetahuan,id',
        'thumbnail' => 'nullable|image|max:2048',
        'filedok' => 'nullable|mimes:pdf,doc,docx|max:5120',
        'isi' => 'required|string',
    ]);

    // Upload Thumbnail
    if ($request->hasFile('thumbnail')) {
        if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
            Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
        }
        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    } else {
        $validated['thumbnail'] = $artikelpengetahuan->thumbnail;
    }

    // Upload File Dokumen
    if ($request->hasFile('filedok')) {
        if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
            Storage::disk('public')->delete($artikelpengetahuan->filedok);
        }
        $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
    } else {
        $validated['filedok'] = $artikelpengetahuan->filedok;
    }

    $artikelpengetahuan->update($validated);

    return redirect()
        ->route('magang.berbagipengetahuan.index')
        ->with('success', 'Artikel berhasil diperbarui.');
}

public function destroy($id)
{
    $userId = auth()->id();

    // Ambil artikel milik user
    $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
        ->where('pengguna_id', $userId)
        ->firstOrFail();

    // Hapus file jika ada
    if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
        Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
    }

    if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
        Storage::disk('public')->delete($artikelpengetahuan->filedok);
    }

    $artikelpengetahuan->delete();

    return redirect()
        ->route('magang.berbagipengetahuan.index')
        ->with('deleted', 'Artikel berhasil dihapus.');
}
}
