<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyFollowController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EmployerCompanyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AnalyticsController;
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/ads', [AdController::class, 'index']);

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
    Route::post('/employer/company/logo', [EmployerCompanyController::class, 'uploadLogo']);
    Route::post('/employer/company/cover', [EmployerCompanyController::class, 'uploadCover']);
    
    Route::post('/admin/blogs', [BlogController::class, 'store']);
    Route::put('/admin/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/admin/blogs/{id}', [BlogController::class, 'destroy']);  
    
    Route::post('/admin/faqs', [FaqController::class, 'store']);
    Route::put('/admin/faqs/{id}', [FaqController::class, 'update']);
    Route::delete('/admin/faqs/{id}', [FaqController::class, 'destroy']);

    Route::post('/admin/announcements', [AnnouncementController::class, 'store']);
    Route::put('/admin/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/admin/announcements/{id}', [AnnouncementController::class, 'destroy']);

    // Messaging / Chat Routes
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{id}', [ConversationController::class, 'show']);
    Route::post('/conversations/{id}/messages', [ConversationController::class, 'sendMessage']);

    Route::post('/admin/ads', [AdController::class, 'store']);
    Route::put('/admin/ads/{id}', [AdController::class, 'update']);
    Route::delete('/admin/ads/{id}', [AdController::class, 'destroy']);

    // Analytics Routes
    Route::get('/employer/analytics', [AnalyticsController::class, 'employerAnalytics']);
    Route::get('/admin/analytics', [AnalyticsController::class, 'adminAnalytics']);
});