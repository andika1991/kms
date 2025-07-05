<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    $redirectRoutes = [
        'admin'         => 'dashboard.admin.index',
        'kepalabagian'  => 'kepalabagian.dashboard',
        'kasubbidang'   => 'dashboard.kasubbidang',
        'pegawai'       => 'pegawai.dashboard',
        'magang'        => 'magang.dashboard',
        'sekretaris'    => 'dashboard.sekretaris',
        'kadis'         => 'dashboard.kadis',
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
