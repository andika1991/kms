<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\KategoriPengetahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelPengetahuanController extends Controller
{
    /**
     * Tampilkan daftar artikel pengetahuan.
     */
    public function index(Request $request)
    {
        $query = ArtikelPengetahuan::query();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $artikels = $query->latest()->get();

        $kategori = KategoriPengetahuan::all();

        return view('kepalabagian.artikelpengetahuan', compact('artikels', 'kategori'));
    }

    /**
     * Tampilkan form create artikel.
     */
    public function create()
    {
        $kategori = KategoriPengetahuan::all();

        return view('kepalabagian.artikelpengetahuan-create', compact('kategori'));
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
            ->route('kepalabagian.artikelpengetahuan.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail artikel.
     */
    public function show(ArtikelPengetahuan $artikelpengetahuan)
    {
        $artikelpengetahuan->load(['kategoriPengetahuan', 'pengguna']);

        return view('kepalabagian.artikelpengetahuan-show', [
            'artikel' => $artikelpengetahuan,
        ]);
    }

    /**
     * Tampilkan form edit artikel.
     */
    public function edit(ArtikelPengetahuan $artikelpengetahuan)
    {
        $kategori = KategoriPengetahuan::all();

        return view('kepalabagian.artikelpengetahuan-edit', compact('artikelpengetahuan', 'kategori'));
    }

    /**
     * Update artikel.
     */
    public function update(Request $request, ArtikelPengetahuan $artikelpengetahuan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:artikelpengetahuan,slug,' . $artikelpengetahuan->id,
            'kategori_pengetahuan_id' => 'required|exists:kategori_pengetahuan,id',
            'thumbnail' => 'nullable|image|max:2048',
            'filedok' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'isi' => 'required|string',
        ]);

        // Handle thumbnail
        if ($request->hasFile('thumbnail')) {
            if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
                Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        } else {
            $validated['thumbnail'] = $artikelpengetahuan->thumbnail;
        }

        // Handle file dokumen
        if ($request->hasFile('filedok')) {
            if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
                Storage::disk('public')->delete($artikelpengetahuan->filedok);
            }
            $validated['filedok'] = $request->file('filedok')->store('filedok', 'public');
        } else {
            $validated['filedok'] = $artikelpengetahuan->filedok;
        }

        $artikelpengetahuan->update($validated);

        return redirect()->route('kepalabagian.artikelpengetahuan.index')
                         ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Hapus artikel.
     */
    public function destroy(ArtikelPengetahuan $artikelpengetahuan)
    {
        if ($artikelpengetahuan->thumbnail && Storage::disk('public')->exists($artikelpengetahuan->thumbnail)) {
            Storage::disk('public')->delete($artikelpengetahuan->thumbnail);
        }

        if ($artikelpengetahuan->filedok && Storage::disk('public')->exists($artikelpengetahuan->filedok)) {
            Storage::disk('public')->delete($artikelpengetahuan->filedok);
        }

        $artikelpengetahuan->delete();

        return redirect()->route('kepalabagian.artikelpengetahuan.index')
                         ->with('success', 'Artikel berhasil dihapus.');
    }
}
