<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TricycleController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'show'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [AppController::class,'show'])->name('dashboard');
    Route::get('users', [UserController::class, 'show'])->name('users');
    Route::get('drivers', [DriverController::class, 'show'])->name('drivers');
    Route::get('tricycles', [TricycleController::class, 'show'])->name('tricycles');
});
