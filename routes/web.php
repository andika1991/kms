<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriPengetahuanController;
use App\Http\Middleware\RoleGroupMiddleware;
use App\Http\Controllers\ArtikelPengetahuanController;
use App\Http\Controllers\ManajemenDokumenController;
use App\Http\Controllers\GrupChatMessageController;
use App\Http\Controllers\PengetahuanController;
use \App\Http\Controllers\Magang\KegiatanController;
use App\Http\Controllers\DokumenmagangController;
use App\Http\Controllers\FotoKegiatanController;
Route::middleware(['auth', 'role_group:admin'])->group(function () {
    Route::get('dokumen/view/{filename}', function($filename) {
    $path = storage_path('app/public/dokumen/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'X-Frame-Options' => 'ALLOWALL', // override header
    ]);
});

    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role_group:kepalabagian'])
    ->prefix('kepalabagian')
    ->name('kepalabagian.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'kepalabagian'])->name('dashboard');
        Route::resource('kategoripengetahuan', KategoriPengetahuanController::class);
        Route::resource('artikelpengetahuan', ArtikelPengetahuanController::class);
Route::resource('manajemendokumen', ManajemenDokumenController::class)
    ->parameters(['manajemendokumen' => 'dokumen']);
Route::resource('forum', \App\Http\Controllers\Kepalabagian\ForumController::class);

        Route::get('artikelpengetahuan/kategori/{id}', [ArtikelPengetahuanController::class, 'byKategori'])
            ->name('artikelpengetahuan.byKategori');
          

Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');

    });





Route::prefix('magang')
    ->as('magang.')
    ->middleware(['auth', 'role_group:magang'])
    ->group(function () {
Route::get('/', [DashboardController::class, 'magang'])->name('dashboard');
Route::resource('kegiatan', KegiatanController::class);
Route::resource('berbagipengetahuan', PengetahuanController::class);
Route::resource('manajemendokumen', DokumenmagangController::class);
Route::delete('/magang/kegiatan/foto/{foto}', [FotoKegiatanController::class, 'destroy'])
    ->name('kegiatan.foto.delete');

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
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Route untuk Halaman Pengetahuan
Route::get('/pengetahuan', function () {
    return view('pengetahuan'); 
})->name('pengetahuan');

// Route untuk Halaman Dokumen
Route::get('/dokumen', function () { 
    return view('dokumen');
})->name('dokumen');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
