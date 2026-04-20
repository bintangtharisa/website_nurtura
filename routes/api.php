<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;


// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
