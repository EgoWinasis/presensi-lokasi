<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PengaturanController;

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('profile', ProfileController::class);
    Route::resource('password', PasswordController::class);
    Route::resource('presensi', PresensiController::class);

    // kelola aktivasi user
    Route::get('aktivasi-user', [UserController::class, 'aktivasi']);
    Route::post('/user/aktivasi/{id}', [UserController::class, 'aktivasiUser'])->name('user.aktivasi');
    Route::post('/aktivasi-user-all', [UserController::class, 'aktivasiSemua']);
    // kelola admin
    Route::get('/kelola-admin', [UserController::class, 'kelolaAdmin'])
    ->name('user.kelolaAdmin')
    ->middleware('can:isSuper');

    Route::get('/user/create-admin', [UserController::class, 'createAdmin'])
     ->name('user.create-admin')
     ->middleware(['auth', 'can:isSuper']);

    Route::post('/user/store-admin', [UserController::class, 'storeAdmin'])
    ->name('user.store-admin')
    ->middleware(['auth', 'can:isSuper']);

    Route::resource('user', UserController::class);

    Route::get('/pengaturan-lokasi', [PengaturanController::class, 'pengaturanLokasi'])
    ->name('pengaturan.lokasi')
    ->middleware('can:isAdmin');
    Route::post('/pengaturan-lokasi', [PengaturanController::class, 'updateLokasi'])
    ->name('pengaturan.lokasi.update')
    ->middleware('can:isAdmin');

    Route::get('/pengaturan-hari-libur', [PengaturanController::class, 'pengaturanHariLibur'])
    ->name('pengaturan.hari-libur')
    ->middleware('can:isAdmin');

    // Route to handle adding/updating holidays
    Route::post('/pengaturan-hari-libur', [PengaturanController::class, 'updateHariLibur'])
    ->name('pengaturan.hari-libur.update')
    ->middleware('can:isAdmin');

    // Route to handle holiday deletion
    Route::delete('/pengaturan-hari-libur/{id}', [PengaturanController::class, 'deleteHariLibur'])
    ->name('pengaturan.hari-libur.delete')
    ->middleware('can:isAdmin');

    Route::get('/pengaturan-jam-absen', [PengaturanController::class, 'pengaturanJamAbsen'])
    ->name('pengaturan.jam-absen')
    ->middleware('can:isAdmin');
    Route::post('/pengaturan-jam-absen', [PengaturanController::class, 'updateJamAbsen'])
    ->name('pengaturan.jam-absen.update')
    ->middleware('can:isAdmin');


    // presensi
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.destroy');

});
