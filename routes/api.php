<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json($request->user());
});
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/reset-password/verify/{token}', [AuthController::class, 'verifyResetToken']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);