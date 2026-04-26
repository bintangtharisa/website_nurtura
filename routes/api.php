<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\DashboardAdminController;
use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\API\ForgotPasswordController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::prefix('forgot-password')->group(function () {
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
});

Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'dashboard']);
    Route::get('/screenings', [DashboardAdminController::class, 'screenings']);
    Route::get('/questions', [QuestionsController::class, 'index']);
    Route::put('/questions/{id}/toggle', [QuestionsController::class, 'toggle']);
    Route::put('/questions/{id}', [QuestionsController::class, 'update']);
    Route::put('/questions/reorder', [QuestionsController::class, 'reorder']);
});