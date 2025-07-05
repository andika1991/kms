<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin');
    }

    public function kepalabagian()
    {
        return view('kepalabagian.dashboard');
    }

    public function kasubbidang()
    {
        return view('dashboard.kasubbidang');
    }

    public function pegawai()
    {
        return view('pegawai.dashboard');
    }

    public function magang()
    {
        return view('magang.dashboard');
    }

    public function sekretaris()
    {
        return view('dashboard.sekretaris');
    }

    public function kadis()
    {
        return view('dashboard.kadis');
    }

    public function index()
    {
        return view('dashboard.index');
    }
}
