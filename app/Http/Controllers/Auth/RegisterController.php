<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $rolesPegawai = Role::where('nama_role', 'like', '%Pegawai%')->get();
        $rolesMagang = Role::where('nama_role', 'like', '%Magang%')->get();

        return view('auth.register', compact('rolesPegawai', 'rolesMagang'));
    }
}

