<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;

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
Route::middleware('auth')->group(function () {
Route::resource('profile', ProfileController::class);
Route::resource('password', PasswordController::class);

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
});

