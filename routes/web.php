<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriPengetahuanController;
use App\Http\Middleware\RoleGroupMiddleware;

Route::middleware(['auth', 'role_group:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role_group:kepalabagian'])->group(function () {
    Route::get('/kepalabagian', [DashboardController::class, 'kepalabagian'])->name('kepalabagian.dashboard');
    Route::get('/kategoripengetahuan', [KategoriPengetahuanController::class, 'showkepalabagian'])->name('kepalabagian.kategoripengetahuan');
     Route::get('/kategoripengetahuan/create', [KategoriPengetahuanController::class, 'create'])->name('kepalabagian.kategoripengetahuan.create');
     Route::post('/kategoripengetahuan', [KategoriPengetahuanController::class, 'store'])->name('kepalabagian.kategoripengetahuan.store');
});

Route::middleware(['auth', 'role_group:magang'])->group(function () {
    Route::get('/magang', [DashboardController::class, 'magang'])->name('magang.dashboard');
        Route::get('/berbagipengetahuan', [PengetahuanController::class, 'showmagang'])->name('magang.berbagipengetahuan');
});

// dst. untuk group lain


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\Auth\RegisterController;

Route::get('/registers', [RegisterController::class, 'showRegistrationForm'])->middleware('guest')->name('register');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
