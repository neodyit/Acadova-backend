<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

// Public Auth Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public/Protected Quizzes API
Route::get('/quizzes', [QuizController::class, 'index']);
Route::get('/quizzes/{id}', [QuizController::class, 'show']);
Route::post('/quizzes', [QuizController::class, 'store']);
Route::put('/quizzes/{id}', [QuizController::class, 'update']);
Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
Route::post('/quizzes/{id}/questions', [QuizController::class, 'addQuestion']);
Route::put('/questions/{id}', [QuizController::class, 'updateQuestion']);
Route::delete('/questions/{id}', [QuizController::class, 'deleteQuestion']);
Route::post('/quizzes/{id}/import-csv', [QuizController::class, 'importQuestionsCsv']);

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/attempts', [QuizController::class, 'getAttempts']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submitAttempt']);
});
