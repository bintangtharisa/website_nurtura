<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\DashboardAdminController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\Admin\QuestionsController as QuestionsControllerAdmin;
use App\Http\Controllers\Mother\QuestionsController as QuestionsControllerMother;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\Father\DashboardController;
use App\Http\Controllers\Mother\ScreeningController;


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
    Route::get('/questions', [QuestionsControllerAdmin::class, 'index']);
    Route::put('/questions/reorder', [QuestionsControllerAdmin::class, 'reorder']);
    Route::put('/questions/{id}/toggle', [QuestionsControllerAdmin::class, 'toggle']);
    Route::put('/questions/{id}', [QuestionsControllerAdmin::class, 'update']);
});

Route::prefix('father')->middleware(['auth:api', 'role:father'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
});

Route::prefix('mother')->middleware(['auth:api', 'role:mother'])->group(function () {
    Route::get('/dashboard', function () {
        return response()->json([
            'status' => true,
            'message' => 'Welcome Mother'
        ]);
    });

    Route::get('/questions', [QuestionsControllerMother::class, 'getQuestions']);
    Route::post('/screening', [ScreeningController::class, 'screening']);

});

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [ProfileController::class, 'me']);
    Route::put('/profile', [ProfileController::class, 'updateProfile']);
    Route::put('/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll']);
});