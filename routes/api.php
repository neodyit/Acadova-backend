<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\MediaController;

// Public Auth Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Structured Media & File Upload API
Route::post('/upload', [MediaController::class, 'store']);
Route::get('/media', [MediaController::class, 'index']);
Route::get('/media/file/{path}', [MediaController::class, 'showFile'])->where('path', '.*');

// Public/Protected Quizzes & Campaigns API
Route::get('/quizzes', [QuizController::class, 'index']);
Route::get('/quizzes/{id}', [QuizController::class, 'show']);
Route::post('/quizzes', [QuizController::class, 'store']);
Route::put('/quizzes/{id}', [QuizController::class, 'update']);
Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
Route::post('/quizzes/{id}/questions', [QuizController::class, 'addQuestion']);
Route::put('/questions/{id}', [QuizController::class, 'updateQuestion']);
Route::delete('/questions/{id}', [QuizController::class, 'deleteQuestion']);
Route::post('/quizzes/{id}/import-csv', [QuizController::class, 'importQuestionsCsv']);

// Campaigns & Announcements API
Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/{id}', [CampaignController::class, 'show']);
Route::post('/campaigns', [CampaignController::class, 'store']);
Route::put('/campaigns/{id}', [CampaignController::class, 'update']);
Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy']);

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/attempts', [QuizController::class, 'getAttempts']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submitAttempt']);
});
