<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ReportController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Courses
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);

    // Materials
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('/materials/{id}/download', [MaterialController::class, 'download']);

    // Assignments
    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::post('/submissions', [AssignmentController::class, 'submit']);
    Route::post('/submissions/{id}/grade', [AssignmentController::class, 'grade']);

    // Forum
    Route::post('/discussions', [DiscussionController::class, 'store']);
    Route::post('/discussions/{id}/replies', [DiscussionController::class, 'reply']);

    // Reports
    Route::get('/reports/courses', [ReportController::class, 'courses']);
    Route::get('/reports/assignments', [ReportController::class, 'assignments']);
    Route::get('/reports/students/{id}', [ReportController::class, 'student']);
});
