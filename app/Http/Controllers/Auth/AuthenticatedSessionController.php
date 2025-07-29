<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Notifikasi;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani request login.
     */
   public function store(LoginRequest $request): RedirectResponse
{
    // Jalankan proses autentikasi
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();

    // Jika user tidak punya relasi role, logout
    if (!$user || !$user->role) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Akun Anda belum memiliki role. Silakan hubungi administrator.',
        ]);
    }

    // Ambil role_group dari relasi role
    $roleGroup = $user->role->role_group;

    // Hitung jumlah notifikasi belum dibaca
    $jumlahNotifikasi = \App\Models\Notifikasi::where('pengguna_id', $user->id)
        ->where('sudahdibaca', false)
        ->count();


    $redirectRoutes = [
        'admin'         => 'admin.dashboard',
        'kepalabagian'  => 'kepalabagian.dashboard',
        'kasubbidang'   => 'kasubbidang.dashboard',
        'pegawai'       => 'pegawai.dashboard',
        'magang'        => 'magang.dashboard',
        'sekretaris'    => 'sekretaris.dashboard',
        'Kadis'         => 'kadis.dashboard',
    ];

    if (isset($redirectRoutes[$roleGroup])) {
        return redirect()->route($redirectRoutes[$roleGroup]);
    }

    // Jika role_group tidak valid, logout user
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->withErrors([
        'email' => 'Role tidak valid, silakan hubungi administrator.',
    ]);
}


    /**
     * Logout user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
