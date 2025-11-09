<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'mobilelogin']);
Route::get('/device/locations', [LocationController::class, 'getTricycleLocation']);
Route::post('/device/location', [LocationController::class, 'store']);