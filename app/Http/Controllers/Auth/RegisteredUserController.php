<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $rolesPegawai = Role::where('role_group', 'pegawai')->get();
        $rolesMagang = Role::where('role_group', 'magang')->get();

        return view('auth.register', compact('rolesPegawai', 'rolesMagang'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
 public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:pengguna,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'tipe_user' => ['required', 'in:pegawai,magang'],
        'role_id' => ['required', 'exists:role,id'],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id,
    ]);

    event(new Registered($user));

  

return redirect()
        ->route('login')
        ->with('status', 'Terima kasih sudah mendaftar. Tunggu admin memvalidasi akun Anda.');
}

}
