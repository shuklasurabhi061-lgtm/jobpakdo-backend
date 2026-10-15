<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyFollowController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\CandidateController;
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);

// Protected routes (Token Required)
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/jobs', [JobController::class, 'store']); // Employer only
    Route::post('/applications', [ApplicationController::class, 'store']); // Job seeker only
    Route::get('/employer/applications', [ApplicationController::class, 'employerApplications']);
    Route::patch('/employer/applications/{id}/status', [ApplicationController::class, 'updateStatus']);//[span_2](start_span)[span_2](end_span)
    // Naya Seeker Dashboard route
    Route::get('/seeker/applications', [ApplicationController::class, 'seekerApplications']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    // Company Follow & Social Feed Routes
    Route::post('/companies/{id}/follow', [CompanyFollowController::class, 'follow']);
    Route::delete('/companies/{id}/follow', [CompanyFollowController::class, 'unfollow']);

    Route::get('/feed', [FeedController::class, 'index']);
    Route::post('/posts', [FeedController::class, 'store']);
    Route::post('/posts/{id}/like', [FeedController::class, 'like']);
    Route::post('/posts/{id}/comments', [FeedController::class, 'addComment']);
    // Candidate Search Route
    Route::get('/candidates/search', [CandidateController::class, 'index']);
});