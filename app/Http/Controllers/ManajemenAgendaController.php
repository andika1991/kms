<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaPimpinan;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
class ManajemenAgendaController extends Controller
{
    /**
     * Tampilkan daftar agenda.
     */
    public function index()
    {
        $agendas = AgendaPimpinan::with('pengguna.role')->latest()->get();
        return view('sekretaris.agenda.index', compact('agendas'));
    }

    /**
     * Tampilkan form untuk membuat agenda baru.
     */


public function create()
{
    // Ambil user yang role_group-nya "Kadis" atau "Kepala Bagian"
    $users = User::whereHas('role', function ($query) {
        $query->whereIn('role_group', ['Kadis', 'kepalabagian']);
    })->get()->map(function ($user) {
        try {
            $user->decrypted_name = Crypt::decryptString($user->getRawOriginal('name'));
        } catch (\Exception $e) {
            $user->decrypted_name = '[nama terenkripsi]';
        }
        return $user;
    });

    return view('sekretaris.agenda.create', compact('users'));
}

public function showAllUsersWithAgenda()
{
    // Ambil semua pengguna yang memiliki role pimpinan (misal role_group tertentu)
    $users = User::with(['role', 'agenda' => function($query) {
        // Ambil agenda untuk hari ini saja (atau bisa disesuaikan)
        $query->whereDate('date_agenda', Carbon::today())->orderBy('waktu_agenda');
    }])->whereHas('role', function($query){
        // Sesuaikan filter role_group pimpinan, misal:
        $query->whereIn('role_group', ['Kadis', 'kepalabagian', 'rektor', 'wakilrektor']);
    })->get()->map(function ($user) {
        try {
            $user->decrypted_name = Crypt::decryptString($user->getRawOriginal('name'));
        } catch (\Exception $e) {
            $user->decrypted_name = '[nama terenkripsi]';
        }
        return $user;
    });

    // Kirim ke view
    return view('sekretaris.agenda.all_users', compact('users'));
}

    /**
     * Simpan agenda ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_agenda'   => 'required|string|max:255',
            'date_agenda'   => 'required|date',
            'waktu_agenda'  => 'required|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'id_pengguna'   => 'required|exists:pengguna,id',
        ]);

        AgendaPimpinan::create([
            'nama_agenda'   => $request->nama_agenda,
            'date_agenda'   => $request->date_agenda,
            'waktu_agenda'  => $request->waktu_agenda,
            'waktu_selesai' => $request->waktu_selesai,
            'id_pengguna'   => $request->id_pengguna,
        ]);

        return redirect()->route('sekretaris.agenda.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit agenda.
     */
   public function edit($id)
{
    // Ambil data agenda berdasarkan ID, gagal jika tidak ditemukan
    $agenda = AgendaPimpinan::findOrFail($id);
        $agenda->waktu_agenda = Carbon::createFromFormat('H:i:s', $agenda->waktu_agenda)->format('H:i');
    $agenda->waktu_selesai = Carbon::createFromFormat('H:i:s', $agenda->waktu_selesai)->format('H:i');

    // Ambil pengguna dengan role "Kadis" atau "Kepala Bagian"
    $users = User::whereHas('role', function ($query) {
        $query->whereIn('role_group', ['Kadis', 'kepalabagian']);
    })->get()->map(function ($user) {
        try {
            $user->decrypted_name = Crypt::decryptString($user->getRawOriginal('name'));
        } catch (\Exception $e) {
            $user->decrypted_name = '[nama terenkripsi]';
        }
        return $user;
    });


    // Kirim data agenda dan pengguna ke view
    return view('sekretaris.agenda.edit', compact('agenda', 'users'));
}

    /**
     * Update data agenda.
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'nama_agenda'   => 'required|string|max:255',
        'date_agenda'   => 'required|date',
      'waktu_agenda'    => 'required|date_format:H:i',
    'waktu_selesai'   => 'required|date_format:H:i|after_or_equal:waktu_agenda',
        'id_pengguna'   => 'required|exists:pengguna,id',
    ]);

    $agenda = AgendaPimpinan::findOrFail($id);
    $agenda->update([
        'nama_agenda'   => $request->nama_agenda,
        'date_agenda'   => $request->date_agenda,
        'waktu_agenda'  => $request->waktu_agenda,
        'waktu_selesai' => $request->waktu_selesai,
        'id_pengguna'   => $request->id_pengguna,
    ]);

    return redirect()->route('sekretaris.agenda.index')->with('success', 'Agenda berhasil diperbarui.');
}


    /**
     * Hapus agenda.
     */
    public function destroy($id)
    {
        $agenda = AgendaPimpinan::findOrFail($id);
        $agenda->delete();

        return redirect()->route('sekretaris.agenda.index')->with('success', 'Agenda berhasil dihapus.');
    }
}
