<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\DashboardAdminController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout',       [AuthController::class, 'logout']);  // pindah ke sini
    Route::get('/profile',       [ProfileController::class, 'me']);
    Route::get('/dashboard',     [DashboardAdminController::class, 'dashboard']);
    Route::get('/screenings',    [DashboardAdminController::class, 'screenings']);
});