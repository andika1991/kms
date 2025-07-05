<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleGroupMiddleware
{
    public function handle(Request $request, Closure $next, $group)
    {
        // Belum login → redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Sudah login tetapi belum verified
        if (!$user->verified) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda belum diverifikasi. Silakan hubungi admin.'
            ]);
        }

        // Jika role_group cocok → lanjut
        if ($user->role_group === $group) {
            return $next($request);
        }

        // Role_group tidak cocok → abort 403
        abort(403, 'Unauthorized');
    }
}
