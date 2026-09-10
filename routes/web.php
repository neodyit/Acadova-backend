<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminWebController;

// Public Custom Admin Auth Routes
Route::get('/neodyit/login', [AdminWebController::class, 'showLogin'])->name('admin.login');
Route::get('/login', [AdminWebController::class, 'showLogin'])->name('login');
Route::post('/neodyit/login', [AdminWebController::class, 'processLogin'])->name('admin.login.process');
Route::post('/neodyit/logout', [AdminWebController::class, 'logout'])->name('admin.logout');

// Root Landing Page
Route::get('/', [AdminWebController::class, 'landingPage'])->name('landing');

Route::get('/neodyit', function () {
    return redirect()->route('admin.dashboard');
});

// Protected Custom Admin Web Portal Routes (/neodyit/*)
Route::middleware(['auth:web', 'admin'])->prefix('neodyit')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/quizzes', [AdminWebController::class, 'quizzes'])->name('quizzes');
    Route::get('/campaigns', [AdminWebController::class, 'campaigns'])->name('campaigns');
    Route::get('/users', [AdminWebController::class, 'users'])->name('users');
    Route::get('/media', [AdminWebController::class, 'media'])->name('media');
    Route::get('/settings', [AdminWebController::class, 'settings'])->name('settings');
});

Route::get('/sample-csv', function () {
    $csvContent = "Question,Type,Option1,Option2,Option3,Option4,Correct_Option\n"
        . '"What is the capital of France?",single,Paris,London,Berlin,Rome,Paris' . "\n"
        . '"Which of the following are primary colors?",multiple,Red,Green,Blue,Yellow,Red|Blue|Yellow' . "\n"
        . '"What is 2 + 2?",single,3,4,5,6,2' . "\n"
        . '"Select all programming languages.",multiple,Python,Java,HTML,C++,1|2|4' . "\n"
        . '"Which planet is known as the Red Planet?",single,Earth,Mars,Jupiter,Venus,Mars' . "\n";

    return response($csvContent, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="sample_questions.csv"',
    ]);
});

// Fallback Media Serving Route for production environments
Route::get('/storage/{path}', [App\Http\Controllers\Api\MediaController::class, 'showFile'])->where('path', '.*');
