<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelPengetahuanAdmController extends Controller
{
    /**
     * Tampilkan daftar artikel pengetahuan untuk admin.
     */
    public function index(Request $request)
    {
        $query = ArtikelPengetahuan::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $artikels = $query->latest()->get();
        $kategori = KategoriPengetahuan::all();

        return view('admin.artikelpengetahuan.index', compact('artikels', 'kategori'));
    }

    /**
     * Tampilkan form create artikel.
     */
    public function create()
    {
        $kategori = KategoriPengetahuan::all();
        return view('admin.artikelpengetahuan.create', compact('kategori'));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug',
            'thumbnail' => 'nullable|image|max:2048',
            'filedok' => 'nullable|file|max:5120',
            'isi' => 'required|string',
            'kategori_pengetahuan_id' => 'required|exists:kategori_pengetahuan,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('filedok')) {
            $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
        }

        $validated['pengguna_id'] = auth()->id();

        ArtikelPengetahuan::create($validated);

        return redirect()->route('admin.berbagipengetahuan.index')
                         ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail artikel.
     */
    public function show(ArtikelPengetahuan $artikelpengetahuan)
    {
        $artikelpengetahuan->load(['kategoriPengetahuan', 'pengguna']);
        return view('admin.artikelpengetahuan.show', compact('artikelpengetahuan'));
    }

    /**
     * Tampilkan form edit artikel.
     */
public function edit($id)
{
    $artikelpengetahuan = ArtikelPengetahuan::findOrFail($id);  // cari artikel berdasar ID, kalau tidak ada 404
    $kategori = KategoriPengetahuan::all();

    return view('admin.artikelpengetahuan.edit', compact('artikelpengetahuan', 'kategori'));
}

    /**
     * Update artikel.
     */
public function update(Request $request, ArtikelPengetahuan $berbagipengetahuan)
{
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug,' . $berbagipengetahuan->id,
        'kategori_pengetahuan_id' => 'required|exists:kategori_pengetahuan,id',
        'thumbnail' => 'nullable|image|max:2048',
        'filedok' => 'nullable|mimes:pdf,doc,docx|max:5120',
        'isi' => 'required|string',
    ]);

    // Proses thumbnail
    if ($request->hasFile('thumbnail')) {
        if ($berbagipengetahuan->thumbnail && Storage::disk('public')->exists($berbagipengetahuan->thumbnail)) {
            Storage::disk('public')->delete($berbagipengetahuan->thumbnail);
        }
        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    } else {
        $validated['thumbnail'] = $berbagipengetahuan->thumbnail;
    }

    // Proses filedok
    if ($request->hasFile('filedok')) {
        if ($berbagipengetahuan->filedok && Storage::disk('public')->exists($berbagipengetahuan->filedok)) {
            Storage::disk('public')->delete($berbagipengetahuan->filedok);
        }
        $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
    } else {
        $validated['filedok'] = $berbagipengetahuan->filedok;
    }

    $berbagipengetahuan->update($validated);

    return redirect()->route('admin.berbagipengetahuan.index')
                     ->with('success', 'Artikel berhasil diperbarui.');
}


    /**
     * Hapus artikel.
     */
 public function destroy($id)
{
    $artikelpengetahuan = ArtikelPengetahuan::findOrFail($id);

    if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
        Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
    }

    if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
        Storage::disk('public')->delete($artikelpengetahuan->filedok);
    }

    $artikelpengetahuan->delete();

    return redirect()->route('admin.berbagipengetahuan.index')
                     ->with('success', 'Artikel berhasil dihapus.');
}

}
