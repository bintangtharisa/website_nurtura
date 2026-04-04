<?php
use Illuminate\Support\Facades\Route;

// ── HALAMAN LOGIN ──────────────────────────────────────────────
// GET  /login  → tampilkan form login
// POST /login  → proses form login (nanti dibuat sama backend)
Route::get('/login', function () {
    return view('auth.login');        // file: resources/views/auth/login.blade.php
})->name('login');

Route::post('/login', function () {
// TODO: logika backend (AuthController@login)
// Ini placeholder supaya form tidak error saat submit
    return redirect()->route('login')->with('status', 'Login diterima! (belum ada logika)');
})->name('login.post');


// ── HALAMAN REGISTER ───────────────────────────────────────────
// GET  /register  → tampilkan form daftar
// POST /register  → proses pendaftaran
Route::get('/register', function () {
    return view('auth.register');     // file: resources/views/auth/register.blade.php
})->name('register');

Route::post('/register', function () {
    // TODO: logika backend (AuthController@register)
    return redirect()->route('login')->with('status', 'Akun berhasil dibuat! Silakan login.');
})->name('register.post');


// ── HALAMAN FORGOT PASSWORD ────────────────────────────────────
// GET  /forgotpassword  → tampilkan form lupa sandi
// POST /forgotpassword  → proses kirim email reset
Route::get('/forgotpassword', function () {
    return view('auth.forgotpassword'); // file: resources/views/auth/forgot-password.blade.php
})->name('password.request');

Route::post('/forgotpassword', function () {
    // TODO: logika backend kirim email reset (PasswordController@sendResetLink)
    // Laravel punya built-in password reset, nanti backend yang integrasi
    return back()->with('status', 'Link reset kata sandi telah dikirim ke email kamu!');
})->name('password.email');


// ── HALAMAN HOME (sementara redirect ke login) ─────────────────
Route::get('/', function () {
    return redirect()->route('login');
});