<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::get('/admin', function () {
    return redirect('/admin/dashboard');
});

// Explicit Page Routes for Web Panel
Route::get('/admin/{section?}', function ($section = 'dashboard') {
    $allowed = ['dashboard', 'quizzes', 'campaigns', 'users', 'media', 'settings'];
    if (!in_array($section, $allowed)) {
        return redirect('/admin/dashboard');
    }
    return view('admin', ['activeSection' => $section]);
})->where('section', 'dashboard|quizzes|campaigns|users|media|settings');

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
