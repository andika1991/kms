<?php

namespace App\Http\Controllers;

use App\Models\ArtikelPengetahuan;
use App\Models\ArticleView;
use App\Models\KategoriPengetahuan;
use App\Models\Subbidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class ArtikelPengetahuanController extends Controller
{
    /**
     * Tampilkan daftar artikel pengetahuan.
     */
 public function index(Request $request)
    {
        $user = Auth::user();
        $bidangId = $user->role->bidang_id ?? null;

        // Ambil semua subbidang yang terkait dengan bidang pengguna yang sedang login
        $subbidangIds = Subbidang::where('bidang_id', $bidangId)->pluck('id');

        // Buat query untuk mengambil artikel
        $query = ArtikelPengetahuan::query();

        // Filter artikel berdasarkan subbidang yang relevan dengan pengguna
        $query->whereHas('kategoriPengetahuan', function ($q) use ($subbidangIds) {
            $q->whereIn('subbidang_id', $subbidangIds);
        });

        // Tambahkan filter pencarian jika ada
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Ambil artikel yang relevan
        $artikels = $query->latest()->get();

        // Ambil kategori yang juga relevan dengan subbidang pengguna
        $kategori = KategoriPengetahuan::whereIn('subbidang_id', $subbidangIds)->get();

        return view('kepalabagian.artikelpengetahuan', compact('artikels', 'kategori'));
    }
    /**
     * Tampilkan form create artikel.
     */
    public function create()
    {
        $user = Auth::user();
        $bidangId = $user->role->bidang_id ?? null;

        // Ambil semua subbidang yang terkait dengan bidang pengguna yang sedang login
        $subbidangIds = Subbidang::where('bidang_id', $bidangId)->pluck('id');
        
        // Filter kategori berdasarkan subbidang yang relevan
        $kategori = KategoriPengetahuan::whereIn('subbidang_id', $subbidangIds)->get();

        return view('kepalabagian.artikelpengetahuan-create', compact('kategori'));
    }
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

        if (auth()->check()) {
            \App\Models\ArticleView::updateOrCreate(
                ['artikel_id' => $artikelpengetahuan->id, 'user_id' => auth()->id()],
                ['viewed_at' => now()]
            );
        }

        return view('kepalabagian.artikelpengetahuan-show', [
            'artikel' => $artikelpengetahuan,
        ]);
    }

    /**
     * Tampilkan form edit artikel.
     */
   public function edit(ArtikelPengetahuan $artikelpengetahuan)
    {
        $user = Auth::user();
        $bidangId = $user->role->bidang_id ?? null;

        $subbidangIds = Subbidang::where('bidang_id', $bidangId)->pluck('id');
        
        $kategori = KategoriPengetahuan::whereIn('subbidang_id', $subbidangIds)->get();

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
                         ->with('deleted', 'Artikel berhasil dihapus.');
    }
}
