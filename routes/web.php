<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgotPasswordController;
use Illuminate\Http\Request;

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

    Route::get('/lupa-password', function () {
        return view('auth.forgot-password');
    })->name('lupa-password');

Route::get('/reset-password', function (Request $request) {

    if (!$request->email || !$request->token) {
        abort(404);
    }

    return view('auth.reset-password', [
        'email' => $request->email,
        'token' => $request->token
    ]);
});


Route::get('/auth/google', function () {
    return "Fitur Google Login belum aktif";
})->name('auth.google');

// Admin views - proteksi via JS
Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
Route::get('/admin/skrining', function () { return view('admin.skrining'); })->name('admin.skrining');
Route::get('/admin/manajemen', function () { return view('admin.manajemen'); })->name('admin.manajemen');
Route::get('/admin/model', function () { return view('admin.model'); })->name('admin.model');
Route::get('/admin/exportdata', function () { return view('admin.exportdata'); })->name('admin.exportdata');
Route::get('/admin/profile', function () { return view('admin.profile'); })->name('admin.profile');

Route::get('/father/dashboard', function () { return view('father.dashboard'); })->name('father.dashboard');
Route::get('/father/monitoring', function () { return view('father.monitoring'); })->name('father.monitoring');
Route::get('/father/profile', function () { return view('father.profile'); })->name('father.profile');
Route::get('/father/support', function () { return view('father.support'); })->name('father.support');
Route::get('logout', function () { return view('father.logout'); })->name('father.logout');

Route::get('/hubungkan-akun', function () {
    return view('auth.hubungkan-akun');
})->name('hubungkan-akun');