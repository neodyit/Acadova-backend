<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminWebController;

// Public Custom Admin Auth Routes
Route::get('/neodyit/login', [AdminWebController::class, 'showLogin'])->name('admin.login');
Route::get('/login', [AdminWebController::class, 'showLogin'])->name('login');
Route::post('/neodyit/login', [AdminWebController::class, 'processLogin'])->name('admin.login.process');
Route::post('/neodyit/logout', [AdminWebController::class, 'logout'])->name('admin.logout');

// Root Landing Page & Password Reset Page
Route::get('/', [AdminWebController::class, 'landingPage'])->name('landing');
Route::get('/reset-password', function () {
    return view('reset_password');
})->name('password.reset');

// Public Privacy Policy Page
Route::get('/page/privacy-policy', function () {
    return view('privacy_policy');
})->name('privacy.policy');

Route::get('/privacy-policy', function () {
    return redirect()->route('privacy.policy');
});

// Android App Links /.well-known/assetlinks.json route
Route::get('/.well-known/assetlinks.json', function () {
    $filePath = public_path('.well-known/assetlinks.json');
    if (file_exists($filePath)) {
        return response()->file($filePath, ['Content-Type' => 'application/json']);
    }
    return response()->json([
        [
            'relation' => ['delegate_permission/common.handle_all_urls'],
            'target' => [
                'namespace' => 'android_app',
                'package_name' => 'com.neodyit.acadova',
                'sha256_cert_fingerprints' => [
                    'FA:C6:17:45:DC:09:03:78:6F:B9:ED:E6:2A:96:2B:39:9F:73:48:F0:BB:6F:89:9B:83:32:66:75:91:03:3B:9C'
                ]
            ]
        ]
    ]);
});

Route::get('/neodyit', function () {
    return redirect()->route('admin.dashboard');
});

// Protected Custom Admin Web Portal Routes (/neodyit/*)
Route::middleware(['auth:web', 'admin'])->prefix('neodyit')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/academic', [AdminWebController::class, 'academic'])->name('academic');
    Route::get('/attempts', [AdminWebController::class, 'attemptsPage'])->name('attempts');
    Route::get('/quizzes', [AdminWebController::class, 'quizzes'])->name('quizzes');
    Route::get('/campaigns', [AdminWebController::class, 'campaigns'])->name('campaigns');
    Route::get('/users', [AdminWebController::class, 'users'])->name('users');
    Route::get('/media', [AdminWebController::class, 'media'])->name('media');
    Route::get('/settings', [AdminWebController::class, 'settings'])->name('settings');
});

Route::get('/sample-csv', function () {
    $csvContent = "Question,Type,Difficulty,Option1,Option2,Option3,Option4,Correct_Option\n"
        . '"What is the capital of France?",single,easy,Paris,London,Berlin,Rome,Paris' . "\n"
        . '"Which of the following are primary colors?",multiple,medium,Red,Green,Blue,Yellow,Red|Blue|Yellow' . "\n"
        . '"What is 2 + 2?",single,easy,3,4,5,6,2' . "\n"
        . '"Select all programming languages.",multiple,hard,Python,Java,HTML,C++,1|2|4' . "\n"
        . '"Which planet is known as the Red Planet?",single,easy,Earth,Mars,Jupiter,Venus,Mars' . "\n";

    return response($csvContent, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="sample_questions.csv"',
    ]);
});

// Fallback Media Serving Route for production environments
Route::get('/storage/{path}', [App\Http\Controllers\Api\MediaController::class, 'showFile'])->where('path', '.*');
