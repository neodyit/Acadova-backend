<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AcademicController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\MediaController;

// Admin Stats & Users API
Route::get('/admin/stats', [AdminController::class, 'stats']);
Route::get('/admin/users', [AdminController::class, 'users']);
Route::post('/admin/users', [AdminController::class, 'storeUser']);
Route::get('/admin/users/{id}', [AdminController::class, 'showUser']);
Route::put('/admin/users/{id}', [AdminController::class, 'updateUser']);
Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser']);
Route::get('/admin/attempts', [AdminController::class, 'attempts']);
Route::delete('/admin/attempts/{id}', [AdminController::class, 'deleteAttempt']);
Route::get('/admin/sessions', [AdminController::class, 'sessions']);

// Academic Hierarchy & Structure CRUD API
Route::prefix('academic')->group(function () {
    // Universities
    Route::get('/universities', [AcademicController::class, 'getUniversities']);
    Route::post('/universities', [AcademicController::class, 'storeUniversity']);
    Route::put('/universities/{id}', [AcademicController::class, 'updateUniversity']);
    Route::delete('/universities/{id}', [AcademicController::class, 'deleteUniversity']);

    // Colleges
    Route::get('/colleges', [AcademicController::class, 'getColleges']);
    Route::post('/colleges', [AcademicController::class, 'storeCollege']);
    Route::put('/colleges/{id}', [AcademicController::class, 'updateCollege']);
    Route::delete('/colleges/{id}', [AcademicController::class, 'deleteCollege']);

    // Departments
    Route::get('/departments', [AcademicController::class, 'getDepartments']);
    Route::post('/departments', [AcademicController::class, 'storeDepartment']);
    Route::put('/departments/{id}', [AcademicController::class, 'updateDepartment']);
    Route::delete('/departments/{id}', [AcademicController::class, 'deleteDepartment']);

    // Courses
    Route::get('/courses', [AcademicController::class, 'getCourses']);
    Route::post('/courses', [AcademicController::class, 'storeCourse']);
    Route::put('/courses/{id}', [AcademicController::class, 'updateCourse']);
    Route::delete('/courses/{id}', [AcademicController::class, 'deleteCourse']);

    // Branches
    Route::get('/branches', [AcademicController::class, 'getBranches']);
    Route::post('/branches', [AcademicController::class, 'storeBranch']);
    Route::put('/branches/{id}', [AcademicController::class, 'updateBranch']);
    Route::delete('/branches/{id}', [AcademicController::class, 'deleteBranch']);

    // Subjects
    Route::get('/subjects', [AcademicController::class, 'getSubjects']);
    Route::post('/subjects', [AcademicController::class, 'storeSubject']);
    Route::put('/subjects/{id}', [AcademicController::class, 'updateSubject']);
    Route::delete('/subjects/{id}', [AcademicController::class, 'deleteSubject']);

    // Sections
    Route::get('/sections', [AcademicController::class, 'getSections']);
    Route::post('/sections', [AcademicController::class, 'storeSection']);
    Route::put('/sections/{id}', [AcademicController::class, 'updateSection']);
    Route::delete('/sections/{id}', [AcademicController::class, 'deleteSection']);

    // Subsections
    Route::get('/subsections', [AcademicController::class, 'getSubsections']);
    Route::post('/subsections', [AcademicController::class, 'storeSubsection']);
    Route::put('/subsections/{id}', [AcademicController::class, 'updateSubsection']);
    Route::delete('/subsections/{id}', [AcademicController::class, 'deleteSubsection']);
});

// Public Auth Endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);

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
Route::middleware(['auth:sanctum', 'validate.session'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/attempts', [QuizController::class, 'getAttempts']);
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submitAttempt']);
    Route::get('/faculty/stats', [QuizController::class, 'getFacultyStats']);
    Route::get('/faculty/submissions', [QuizController::class, 'getFacultySubmissions']);
});
