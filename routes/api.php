<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\DashboardAdminController;
use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\API\ForgotPasswordController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('password')->group(function () {
    Route::post('/forgot', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/reset', [ForgotPasswordController::class, 'resetPassword']);
    Route::post('/change', [ForgotPasswordController::class, 'changePassword'])->middleware('auth:api');
});

Route::prefix('admin')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'dashboard']);
    Route::get('/questions', [QuestionsController::class, 'index']);
    Route::put('/questions/reorder', [QuestionsController::class, 'reorder']);
    Route::put('/questions/{id}/toggle', [QuestionsController::class, 'toggle']);
    Route::put('/questions/{id}', [QuestionsController::class, 'update']);
});

Route::middleware('auth:api')->get('/profile', [ProfileController::class, 'me']);
Route::put('/change-password', [ProfileController::class, 'changePassword'])->middleware('auth:api');