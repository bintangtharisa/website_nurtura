<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// routes/web.php
Route::get('/', function () {
    return view('landing');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/auth/google', function () {
    return "Fitur Google Login belum aktif";
})->name('auth.google');


Route::get('/hubungkan-akun', function () {
    return view('auth.hubungkan-akun');
})->name('hubungkan-akun');


// belum ada controler untuk fitur ini, jadi sementara hanya menampilkan view saja
Route::get('/admin/skrining', function () {
    return view('admin.skrining');
})->name('admin.skrining');

Route::get('/admin/model', function () {
    return view('admin.model'); // sementara
})->name('admin.model');

Route::get('/admin/export', function () {
    return view('admin.export'); // sementara
})->name('admin.export');
