<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AcademicController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\MediaController;

/*
|--------------------------------------------------------------------------
| Public Routes (Authentication & Public Academic Lookups)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Public Academic hierarchy getters (Used during registration & initial profile setup)
Route::prefix('academic')->group(function () {
    Route::get('/universities', [AcademicController::class, 'getUniversities']);
    Route::get('/colleges', [AcademicController::class, 'getColleges']);
    Route::get('/departments', [AcademicController::class, 'getDepartments']);
    Route::get('/courses', [AcademicController::class, 'getCourses']);
    Route::get('/branches', [AcademicController::class, 'getBranches']);
    Route::get('/subjects', [AcademicController::class, 'getSubjects']);
    Route::get('/sections', [AcademicController::class, 'getSections']);
    Route::get('/subsections', [AcademicController::class, 'getSubsections']);
    Route::get('/semesters', [AcademicController::class, 'getSemesters']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Require Valid Sanctum Token & Session)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum,web', 'validate.session'])->group(function () {

    // User Profile & Authentication Management
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Media & File Upload
    Route::post('/upload', [MediaController::class, 'store']);
    Route::get('/media', [MediaController::class, 'index']);
    Route::get('/media/file/{path}', [MediaController::class, 'showFile'])->where('path', '.*');

    // General Quiz Browsing & Student Quiz Attempts
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submitAttempt']);
    Route::get('/attempts', [QuizController::class, 'getAttempts']);

    // Announcements & Campaigns View
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaigns/{id}', [CampaignController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Faculty & Admin Protected Routes (Role: faculty or admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:faculty,admin'])->group(function () {
        // Quiz & Question Management (Creation, Modification, Deletion, CSV Import)
        Route::post('/quizzes', [QuizController::class, 'store']);
        Route::put('/quizzes/{id}', [QuizController::class, 'update']);
        Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
        Route::post('/quizzes/{id}/questions', [QuizController::class, 'addQuestion']);
        Route::put('/questions/{id}', [QuizController::class, 'updateQuestion']);
        Route::delete('/questions/{id}', [QuizController::class, 'deleteQuestion']);
        Route::post('/quizzes/{id}/import-csv', [QuizController::class, 'importQuestionsCsv']);

        // Faculty Dashboard Metrics & Submissions
        Route::get('/faculty/stats', [QuizController::class, 'getFacultyStats']);
        Route::get('/faculty/submissions', [QuizController::class, 'getFacultySubmissions']);
        Route::get('/faculty/my-allocations', [AdminController::class, 'getMyFacultyAllocations']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Only Protected Routes (Role: admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        // Admin Management APIs
        Route::get('/admin/stats', [AdminController::class, 'stats']);
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::post('/admin/users', [AdminController::class, 'storeUser']);
        Route::get('/admin/users/{id}', [AdminController::class, 'showUser']);
        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser']);
        Route::get('/admin/attempts', [AdminController::class, 'attempts']);
        Route::delete('/admin/attempts/{id}', [AdminController::class, 'deleteAttempt']);
        Route::get('/admin/sessions', [AdminController::class, 'sessions']);

        // Faculty Allocations Management
        Route::get('/admin/faculty/allocations', [AdminController::class, 'getFacultyAllocations']);
        Route::get('/admin/faculty/{id}/allocations', [AdminController::class, 'getFacultyAllocations']);
        Route::post('/admin/faculty/allocations', [AdminController::class, 'storeFacultyAllocation']);
        Route::delete('/admin/faculty/allocations/{id}', [AdminController::class, 'deleteFacultyAllocation']);

        // Academic Hierarchy Structural Mutations
        Route::prefix('academic')->group(function () {
            Route::post('/universities', [AcademicController::class, 'storeUniversity']);
            Route::put('/universities/{id}', [AcademicController::class, 'updateUniversity']);
            Route::delete('/universities/{id}', [AcademicController::class, 'deleteUniversity']);

            Route::post('/colleges', [AcademicController::class, 'storeCollege']);
            Route::put('/colleges/{id}', [AcademicController::class, 'updateCollege']);
            Route::delete('/colleges/{id}', [AcademicController::class, 'deleteCollege']);

            Route::post('/departments', [AcademicController::class, 'storeDepartment']);
            Route::put('/departments/{id}', [AcademicController::class, 'updateDepartment']);
            Route::delete('/departments/{id}', [AcademicController::class, 'deleteDepartment']);

            Route::post('/courses', [AcademicController::class, 'storeCourse']);
            Route::put('/courses/{id}', [AcademicController::class, 'updateCourse']);
            Route::delete('/courses/{id}', [AcademicController::class, 'deleteCourse']);

            Route::post('/branches', [AcademicController::class, 'storeBranch']);
            Route::put('/branches/{id}', [AcademicController::class, 'updateBranch']);
            Route::delete('/branches/{id}', [AcademicController::class, 'deleteBranch']);

            Route::post('/subjects', [AcademicController::class, 'storeSubject']);
            Route::put('/subjects/{id}', [AcademicController::class, 'updateSubject']);
            Route::delete('/subjects/{id}', [AcademicController::class, 'deleteSubject']);

            Route::post('/sections', [AcademicController::class, 'storeSection']);
            Route::put('/sections/{id}', [AcademicController::class, 'updateSection']);
            Route::delete('/sections/{id}', [AcademicController::class, 'deleteSection']);

            Route::post('/subsections', [AcademicController::class, 'storeSubsection']);
            Route::put('/subsections/{id}', [AcademicController::class, 'updateSubsection']);
            Route::delete('/subsections/{id}', [AcademicController::class, 'deleteSubsection']);

            Route::post('/semesters', [AcademicController::class, 'storeSemester']);
            Route::put('/semesters/{id}', [AcademicController::class, 'updateSemester']);
            Route::delete('/semesters/{id}', [AcademicController::class, 'deleteSemester']);
        });

        // Campaign Management
        Route::post('/campaigns', [CampaignController::class, 'store']);
        Route::put('/campaigns/{id}', [CampaignController::class, 'update']);
        Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy']);
    });
});
