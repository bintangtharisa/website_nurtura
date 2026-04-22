<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::get('/', function () {
    return view('landing');
});

// Auth views
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

Route::get('/auth/google', function () {
    return "Fitur Google Login belum aktif";
})->name('auth.google');

// Admin views - proteksi via JS
Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
Route::get('/admin/skrining', function () { return view('admin.skrining'); })->name('admin.skrining');
Route::get('/admin/manajemen', function () { return view('admin.manajemen'); })->name('admin.manajemen');
Route::get('/admin/model', function () { return view('admin.model'); })->name('admin.model');
Route::get('/admin/exportdata', function () { return view('admin.exportdata'); })->name('admin.exportdata');

Route::get('/hubungkan-akun', function () {
    return view('auth.hubungkan-akun');
})->name('hubungkan-akun');