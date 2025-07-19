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
use App\Http\Controllers\ForumMagangController;
use App\Http\Controllers\PengetahuanpegawaiController;
use App\Http\Controllers\KegiatanpegawaiController;
use App\Http\Controllers\DokumenpegawaiController;
use App\Http\Controllers\ForumPegawaiController;
use App\Http\Controllers\PengetahuankasubbidangController;
use App\Http\Controllers\KategoriPengetahuankasubbidangController;
use App\Http\Controllers\KegiatankasubidangController;
use App\Http\Controllers\DokumenkasubbidangController;
use App\Http\Controllers\KategoriDokumenController;
use App\Http\Controllers\ManajemenPenggunaKaSubbidangController;
use App\Http\Controllers\ForumKasubbidangController;
use App\Http\Controllers\ManajemenAgendaController;
use App\Http\Controllers\KategoriPengetahuankasekretarisController;
use App\Http\Controllers\PengetahuansekretarisController;
use App\Http\Controllers\DokumensekretarisController;
use App\Http\Controllers\KategoriDokumensekreController;
use App\Http\Controllers\ForumsekreController;
Route::middleware(['auth', 'role_group:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role_group:kepalabagian'])
    ->prefix('kepalabagian')
    ->name('kepalabagian.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'kepalabagian'])->name('dashboard');
        Route::resource('kategoripengetahuan', KategoriPengetahuanController::class);
        Route::resource('artikelpengetahuan', ArtikelPengetahuanController::class);
        Route::delete('artikelpengetahuan/{artikelpengetahuan}/delete-thumbnail', [ArtikelPengetahuanController::class, 'deleteThumbnail'])
            ->name('artikelpengetahuan.deleteThumbnail');
        Route::resource('kategoridokumen', \App\Http\Controllers\Kepalabagian\KategoriDokumenController::class);
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
Route::resource('kategoripengetahuan', KategoriPengetahuanController::class);
Route::delete('/magang/kegiatan/foto/{foto}', [FotoKegiatanController::class, 'destroy'])
    ->name('kegiatan.foto.delete');
Route::resource('forum', ForumMagangController::class);
Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
    });

Route::prefix('pegawai')
    ->as('pegawai.')
    ->middleware(['auth', 'role_group:pegawai'])
    ->group(function () {
Route::get('/', [DashboardController::class, 'pegawai'])->name('dashboard');
Route::resource('berbagipengetahuan', PengetahuanpegawaiController::class);
Route::resource('kegiatan', KegiatanpegawaiController::class);
Route::resource('manajemendokumen', DokumenpegawaiController::class);
Route::resource('forum', ForumPegawaiController::class);
 Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
    });


    Route::prefix('kasubbidang')
    ->as('kasubbidang.')
    ->middleware(['auth', 'role_group:kasubbidang'])
    ->group(function () {
Route::get('/', [DashboardController::class, 'kasubbidang'])->name('dashboard');
 Route::resource('kategoripengetahuan', KategoriPengetahuankasubbidangController::class);
Route::resource('berbagipengetahuan', PengetahuankasubbidangController::class);
Route::resource('manajemendokumen', DokumenkasubbidangController::class);
 Route::resource('kegiatan', KegiatankasubidangController::class);
Route::resource('manajemenpengguna', ManajemenPenggunaKaSubbidangController::class);
Route::patch('manajemenpengguna/{id}/verifikasi', [ManajemenPenggunaKaSubbidangController::class, 'verifikasi'])
    ->name('manajemenpengguna.verifikasi');
    Route::resource('kategori-dokumen', KategoriDokumenController::class)->except(['index', 'create', 'show']);
Route::resource('forum', ForumKasubbidangController::class);
 Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
    });

        Route::prefix('sekretaris')
    ->as('sekretaris.')
    ->middleware(['auth', 'role_group:sekretaris'])
    ->group(function () {
Route::get('/', [DashboardController::class, 'sekretaris'])->name('dashboard');
 Route::resource('kategoripengetahuan', KategoriPengetahuankasekretarisController::class);
Route::resource('berbagipengetahuan', PengetahuansekretarisController::class);
Route::resource('manajemendokumen', DokumensekretarisController::class);
 Route::resource('kegiatan', KegiatankasubidangController::class);
 Route::get('/agenda/all-users', [ManajemenAgendaController::class, 'showAllUsersWithAgenda'])
->name('all_users');
 Route::resource('agenda', ManajemenAgendaController::class);
 Route::resource('kategori-dokumen', KategoriDokumensekreController::class)->except(['index', 'create', 'show']);
Route::resource('forum', ForumsekreController::class);
 Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
Route::post('/grup-chat/{grupchat}/pesan', [GrupChatMessageController::class, 'store'])
    ->name('grupchat.pesan.store');
    });

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