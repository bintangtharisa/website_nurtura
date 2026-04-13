<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// REGISTER PAGE (UI)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// REGISTER PROCESS
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// LOGIN PAGE
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// HOME
Route::get('/', function () {
    return redirect('/register');
});