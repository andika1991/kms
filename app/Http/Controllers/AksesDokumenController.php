<?php

namespace App\Http\Controllers;
use App\Models\Notifikasi; 
use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Models\AksesDokumenPengguna;

class AksesDokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // semua yang login bisa akses
    }

    // Halaman form bagikan dokumen
public function bagikanForm($id, Request $request)
{
    $dokumen = Dokumen::findOrFail($id);
    $query = strtolower($request->input('q'));

    // Ambil semua user (atau batasi dulu role jika ingin)
    $users = User::all();

    // Ambil user_id yang sudah punya akses pada dokumen ini
    $aksesUserIds = $dokumen->aksesDokumenPengguna()->pluck('pengguna_id')->toArray();

    // Filter jika ada query pencarian
    if ($query) {
        $users = $users->filter(function ($user) use ($query) {
            return str_contains(strtolower($user->name), $query)
                || str_contains(strtolower($user->email), $query);
        });
    }

    return view('aksesdokumen.bagikan', compact('dokumen', 'users', 'aksesUserIds'));
}



public function prosesBagikan(Request $request, $id)
{
    $dokumen = Dokumen::findOrFail($id);

    // User yang dipilih dari form checkbox
    $selectedUserIds = $request->input('user_ids', []); // default [] kalau tidak ada

    // User yang sudah punya akses di DB
    $currentUserIds = $dokumen->aksesDokumenPengguna()->pluck('pengguna_id')->toArray();

    // 1. User yang harus dihapus aksesnya (ada di DB tapi tidak di form)
    $toRemove = array_diff($currentUserIds, $selectedUserIds);

    // 2. User yang harus ditambah aksesnya (ada di form tapi belum di DB)
    $toAdd = array_diff($selectedUserIds, $currentUserIds);

    // Hapus akses yang sudah tidak dicentang
    if (!empty($toRemove)) {
        AksesDokumenPengguna::where('dokumen_id', $id)
            ->whereIn('pengguna_id', $toRemove)
            ->delete();
    }

    // Tambah akses yang baru dicentang dan buat notifikasi
    foreach ($toAdd as $userId) {
        AksesDokumenPengguna::create([
            'dokumen_id' => $id,
            'pengguna_id' => $userId,
        ]);

        // Buat notifikasi
        Notifikasi::create([
            'judul' => 'Dokumen Baru Dibagikan',
            'isi' => "Dokumen '{$dokumen->nama_dokumen}' telah dibagikan kepada Anda.",
            'sudahdibaca' => false,
            'pengguna_id' => $userId,
            'dokumen_id' => $id,
        ]);
    }

    $redirectTo = $request->input('redirect_to') ?? route('magang.manajemendokumen.index');

    return redirect($redirectTo)->with('success', 'Akses dokumen berhasil diperbarui dan notifikasi telah dikirim.');
}



public function dokumenDibagikanKeSaya()
{
    $userId = Auth::id();

    // Asumsikan tabel pivot 'akses_dokumen_pengguna' dengan kolom: dokumen_id, user_id
    // Relasi many to many sudah ada di model User dan Dokumen (jika belum, buat ya)

    $dokumenDibagikan = Dokumen::whereHas('aksesDokumenPengguna', function($query) use ($userId) {
        $query->where('pengguna_id', $userId);
    })->paginate(10);

    return view('aksesdokumen.dibagikan_ke_saya', compact('dokumenDibagikan'));
}


}
