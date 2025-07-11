<?php

namespace App\Http\Controllers;
use App\Models\ArtikelPengetahuan;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;

class PengetahuanController extends Controller
{
 public function index(Request $request)
{
    $query = ArtikelPengetahuan::query();

    // Filter hanya artikel milik user yang sedang login
    $query->where('pengguna_id', auth()->id());

    // Filter pencarian jika ada input "search"
    if ($request->filled('search')) {
        $query->where('judul', 'like', '%' . $request->search . '%');
    }

    $artikels = $query->latest()->get();

    return view('magang.berbagipengetahuan', compact('artikels'));
}


 
    public function create()
{
    $user = auth()->user();

    $role = $user->role;

    $bidangId = $role->bidang_id ?? null;
    $subbidangId = $role->subbidang_id ?? null;

    $query = KategoriPengetahuan::query();

    if ($bidangId) {
        $query->where('bidang_id', $bidangId);
    }

    if ($subbidangId) {
        $query->where('subbidang_id', $subbidangId);
    }

    $kategori = $query->get();

    return view('magang.artikelpengetahuan-create', compact('kategori'));
}


    /**
     * Simpan artikel baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:artikelpengetahuan,slug'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'filedok' => ['nullable', 'file', 'max:5120'],
            'isi' => ['required', 'string'],
            'kategori_pengetahuan_id' => ['required', 'exists:kategori_pengetahuan,id'],
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
     */
    public function show(ArtikelPengetahuan $artikelpengetahuan)
    {
        if ($artikelpengetahuan->pengguna_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak melihat artikel ini.');
        }

        $artikelpengetahuan->load(['kategoriPengetahuan', 'pengguna']);

        return view('magang.berbagipengetahuan-show', compact('artikelpengetahuan'));
    }

    /**
     * Tampilkan form edit artikel.
     */
 public function edit($id)
{
    $userId = auth()->id();

    $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
        ->where('pengguna_id', $userId)
        ->firstOrFail();
$user = auth()->user();
 $role = $user->role;

    $bidangId = $role->bidang_id ?? null;
    $subbidangId = $role->subbidang_id ?? null;

    $query = KategoriPengetahuan::query();

    if ($bidangId) {
        $query->where('bidang_id', $bidangId);
    }

    if ($subbidangId) {
        $query->where('subbidang_id', $subbidangId);
    }

    $kategori = $query->get();

    return view('magang.berbagipengetahuan-edit', compact('artikelpengetahuan', 'kategori'));
}


 public function update(Request $request, $id)
{
    $userId = auth()->id();

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

    // Thumbnail
    if ($request->hasFile('thumbnail')) {
        if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
            Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
        }
        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    } else {
        $validated['thumbnail'] = $artikelpengetahuan->thumbnail;
    }

    // Filedok
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


    /**
     * Hapus artikel.
     */
   public function destroy($id)
{
    $userId = auth()->id();

    $artikelpengetahuan = ArtikelPengetahuan::where('id', $id)
        ->where('pengguna_id', $userId)
        ->firstOrFail();

    if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
        Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
    }

    if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
        Storage::disk('public')->delete($artikelpengetahuan->filedok);
    }

    $artikelpengetahuan->delete();

    return redirect()
        ->route('magang.berbagipengetahuan.index')
        ->with('success', 'Artikel berhasil dihapus.');
}

}
