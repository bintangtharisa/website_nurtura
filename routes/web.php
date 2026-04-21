<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardController;

// 👇 UNTUK LANDING PAGE 👇
Route::get('/', function () {
    return view('landing'); 
});
// 👆--------------------👆

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/auth/google', function () {
    return "Fitur Google Login belum aktif";
})->name('auth.google');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');
Route::view('/admin/skrining', 'admin.skrining')->name('admin.skrining')->middleware('auth');
Route::view('/admin/manajemen', 'admin.manajemen')->name('admin.manajemen')->middleware('auth');
Route::view('/admin/exportdata', 'admin.exportdata')->name('admin.exportdata')->middleware('auth');

Route::get('/admin/model', [ModelController::class, 'index'])->name('admin.model');
Route::get('/admin/export', [ModelController::class, 'index'])->name('admin.export');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// 👇 INI ROUTE TAMBAHAN BUAT CEK TAMPILAN (FRONTEND AJA) 👇
Route::get('/hubungkan-akun', function () {
    return view('auth.hubungkan-akun');
})->name('hubungkan-akun');