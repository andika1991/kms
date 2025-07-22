<?php

namespace App\Http\Controllers;

use Spatie\PdfToImage\Pdf;
use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DokumenkasubbidangController extends Controller
{
    public function index(Request $request)
{
    $dokumenQuery = Dokumen::with(['kategoriDokumen', 'user'])
        ->where('pengguna_id', auth()->id());

    if ($request->filled('search')) {
        $search = strtolower($request->search);
        $dokumenQuery->whereRaw('LOWER(nama_dokumen) LIKE ?', ["%{$search}%"]);
    }

    $dokumen = $dokumenQuery->latest()->get();

    
    $kategori = KategoriDokumen::where('subbidang_id', auth()->user()->role->subbidang_id)->get();

    return view('kasubbidang.dokumen.index', compact('dokumen', 'kategori'));
}

    public function create()
    {
        $subbidangId = auth()->user()->role->subbidang_id ?? null;

        $kategori = KategoriDokumen::with('subbidang')
            ->when($subbidangId, function ($query) use ($subbidangId) {
                $query->where('subbidang_id', $subbidangId);
            })
            ->get();

        return view('kasubbidang.dokumen.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['required', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['required', 'file', 'max:10240'],
            'encrypted_key'        => ['nullable', 'string', 'max:255'],
        ]);

        $kategori = KategoriDokumen::findOrFail($validated['kategori_dokumen_id']);

        if (strtolower($kategori->nama_kategoridokumen) === 'rahasia') {
            $request->validate([
                'encrypted_key' => ['required', 'string', 'max:255'],
            ]);
            $validated['encrypted_key'] = $request->encrypted_key;
        } else {
            $validated['encrypted_key'] = null;
        }

        if ($request->hasFile('path_dokumen')) {
            $file = $request->file('path_dokumen');
            $validated['path_dokumen'] = $request->file('path_dokumen')->store('dokumen', 'public');
            $extension = strtolower($request->file('path_dokumen')->getClientOriginalExtension());
            $thumbnailPath = null;

            if (in_array($extension, ['jpg','jpeg','png','webp','bmp','gif'])) {
                // Untuk gambar, copy saja jadi thumbnail
                $thumbnailPath = 'dokumen/thumbnails/' . uniqid() . '.' . $extension;
                Storage::disk('public')->copy($validated['path_dokumen'], $thumbnailPath);
            } elseif ($extension === 'pdf') {
                try {
                    $pdf = new \Spatie\PdfToImage\Pdf(storage_path('app/public/' . $validated['path_dokumen']));
                    $thumbnailName = uniqid() . '.jpg';
                    $thumbDir = storage_path('app/public/dokumen/thumbnails');
                    if (!file_exists($thumbDir)) mkdir($thumbDir, 0777, true);
                    $thumbnailPath = 'dokumen/thumbnails/' . $thumbnailName;
                } catch (\Exception $e) {
                    $thumbnailPath = null;
                }
            }
            
            $validated['thumbnail'] = $thumbnailPath;                                                                                                                                                                                                                                                
        } else {
            return back()->withErrors(['path_dokumen' => 'File dokumen wajib diunggah.']);
        }

        $validated['pengguna_id'] = auth()->id();

        Dokumen::create($validated);

        return redirect()->route('kasubbidang.manajemendokumen.index')
             ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show(Request $request, $id)
    {
        $dokumen = Dokumen::with(['kategoriDokumen', 'user'])->findOrFail($id);

        $isRahasia = $dokumen->kategoriDokumen &&
                     strtolower($dokumen->kategoriDokumen->nama_kategoridokumen) == 'rahasia';

        if ($isRahasia) {
            $inputKey = $request->encrypted_key;

            if (!$inputKey) {
                return redirect()->route('kasubbidang.manajemendokumen.index')
                    ->with('error', 'Kunci dokumen diperlukan untuk mengakses dokumen rahasia.');
            }

            if ($inputKey !== $dokumen->encrypted_key) {
                return redirect()->route('kasubbidang.manajemendokumen.index')
                    ->with('error', 'Kunci dokumen salah.');
            }
        }

        return view('kasubbidang.dokumen.show', compact('dokumen'));
    }

    public function edit(Dokumen $manajemendokuman)
    {
        $kategori = KategoriDokumen::all();

        return view('kasubbidang.dokumen.edit', compact('manajemendokuman', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_dokumen'         => ['required', 'string', 'max:255'],
            'deskripsi'            => ['nullable', 'string'],
            'kategori_dokumen_id'  => ['required', 'exists:kategori_dokumen,id'],
            'path_dokumen'         => ['nullable', 'file', 'max:10240'],
        ]);

        $dokumen = Dokumen::findOrFail($id);

        if ($request->hasFile('path_dokumen')) {
            if (
                $dokumen->path_dokumen &&
                Storage::disk('public')->exists($dokumen->path_dokumen)
            ) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $validated['path_dokumen'] = $request->file('path_dokumen')
                ->store('dokumen', 'public');
        } else {
            unset($validated['path_dokumen']);
        }

        $dokumen->update($validated);

        return redirect()->route('kasubbidang.manajemendokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            if ($dokumen->path_dokumen && Storage::disk('public')->exists($dokumen->path_dokumen)) {
                Storage::disk('public')->delete($dokumen->path_dokumen);
            }

            $dokumen->delete();

            return redirect()->route('kasubbidang.manajemendokumen.index')
                ->with('success', 'Dokumen berhasil dihapus (soft delete).');
        } catch (\Exception $e) {
            return redirect()->route('kasubbidang.manajemendokumen.index')
                ->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }
}
