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
    Route::get('aktivasi-user', [UserController::class, 'aktivasi']);
    Route::post('/user/aktivasi/{id}', [UserController::class, 'aktivasiUser'])->name('user.aktivasi');
    Route::post('/aktivasi-user-all', [UserController::class, 'aktivasiSemua']);

    Route::resource('user', UserController::class);
});

