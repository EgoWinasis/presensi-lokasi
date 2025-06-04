<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\ValidasiCutiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\RekapCutiController;
use App\Http\Controllers\JadwalKaryawanController;
use App\Http\Controllers\JadwalKerjaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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
Route::get('ubah/sandi', [PasswordController::class, 'showLinkRequestForm'])
     ->name('ubah.sandi');

Route::post('ubah/kirim', [PasswordController::class, 'sendResetLinkEmail'])
     ->name('ubah.kirim');


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

    // cuti
    Route::get('/cuti', [PengajuanCutiController::class, 'index'])->name('cuti.index');
    Route::get('/cuti/create', [PengajuanCutiController::class, 'create'])->name('cuti.create');
    Route::post('/cuti', [PengajuanCutiController::class, 'store'])->name('cuti.store');

    /// Route to show the form for editing a specific leave request
    Route::get('cuti/{id}/edit', [PengajuanCutiController::class, 'edit'])->name('cuti.edit');

    // Route to update the specific leave request
    Route::put('cuti/{id}', [PengajuanCutiController::class, 'update'])->name('cuti.update');

    // Route to delete the specific leave request
    Route::delete('cuti/{id}', [PengajuanCutiController::class, 'destroy'])->name('cuti.destroy');

    // Route to print the specific leave request
    Route::get('cuti/{id}/print', [PengajuanCutiController::class, 'print'])->name('cuti.print');

    Route::get('/cuti/details/{id}', [PengajuanCutiController::class, 'getItemDetails'])->name('cuti.details');

    // Route untuk validasi cuti oleh admin
    Route::post('/cuti/validate/admin/{id}', [ValidasiCutiController::class, 'validateByAdmin'])->name('cuti.validateByAdmin');

    // Route untuk validasi cuti oleh superadmin
    Route::post('/cuti/validate/superadmin/{id}', [ValidasiCutiController::class, 'validateBySuperadmin'])->name('cuti.validateBySuperadmin');

    Route::resource('validasi-cuti', ValidasiCutiController::class);


    // rekap
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export-excel', [RekapController::class, 'exportExcel'])->name('rekap.export.excel');
    Route::get('/rekap/export-pdf', [RekapController::class, 'exportPDF'])->name('rekap.export.pdf');
    
    // rekap
    Route::get('/rekap-cuti', [RekapCutiController::class, 'index'])->name('rekap-cuti.index');
    Route::get('/rekap-cuti/export-pdf', [RekapCutiController::class, 'exportPDF'])->name('rekap.export.cuti.pdf');


    Route::prefix('jadwal-karyawan')->name('jadwal-karyawan.')->group(function () {
        Route::get('/', [JadwalKaryawanController::class, 'index'])->name('index');
        Route::get('/create', [JadwalKaryawanController::class, 'create'])->name('create');
        Route::post('/store', [JadwalKaryawanController::class, 'store'])->name('store');
        Route::post('/import-json', [JadwalKaryawanController::class, 'importJson'])->name('jadwal-karyawan.import.json');

    });

    Route::middleware(['auth', 'can:isUser'])->group(function () {
        Route::get('jadwal-kerja', [JadwalKerjaController::class, 'index'])->name('jadwal.kerja');
        Route::get('jadwal-kerja/data', [JadwalKerjaController::class, 'getJadwal'])->name('jadwal.kerja.data');
    });

  
    
});
