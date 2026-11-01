<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyFollowController;
use App\Http\Controllers\Api\FeedController;//api
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EmployerCompanyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\SubscriptionController;//api
use App\Http\Controllers\Api\AdminController;//api
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\EmployerJobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SeekerProfileController;
use App\Http\Controllers\Api\CompanyController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users/me/saved-jobs', [SavedJobController::class, 'index']);
    Route::post('/jobs/{jobId}/save', [SavedJobController::class, 'store']);
    Route::delete('/jobs/{jobId}/save', [SavedJobController::class, 'destroy']);
    // Employer Job Management Routes
    Route::get('/employer/jobs', [EmployerJobController::class, 'index']);
    Route::post('/employer/jobs', [EmployerJobController::class, 'store']);
    Route::patch('/employer/jobs/{id}/status', [EmployerJobController::class, 'updateStatus']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);
Route::get('/ads', [AdController::class, 'index']);
Route::get('/plans', [SubscriptionController::class, 'plans']);
Route::get('/companies', [CompanyController::class, 'index']); 
    Route::get('/companies/{id}', [CompanyController::class, 'show']); 
    Route::get('/companies/{id}/jobs', [CompanyController::class, 'jobs']);
    Route::get('/companies/{id}/posts', [CompanyController::class, 'posts']);
    Route::get('/companies/{id}/employees', [CompanyController::class, 'employees']);

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
    Route::post('/posts/{id}/unlike', [FeedController::class, 'unlike']);
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

    Route::get('/resume', [ResumeController::class, 'index']);
    Route::post('/resume', [ResumeController::class, 'store']);
    Route::patch('/resume/visibility', [ResumeController::class, 'toggleVisibility']);
    Route::delete('/resume/{id}', [ResumeController::class, 'destroy']);

    Route::get('/subscription/invoices', [SubscriptionController::class, 'invoices']);

    // Admin Moderation Routes
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::patch('/admin/users/{id}/status', [AdminController::class, 'toggleUserStatus']);
    Route::patch('/admin/jobs/{id}/remove', [AdminController::class, 'removeJob']);

    // --- Authenticated Routes ---
    // Company Follow / Unfollow Routes
    Route::post('/companies/{id}/follow', [CompanyFollowController::class, 'follow']);
    Route::delete('/companies/{id}/unfollow', [CompanyFollowController::class, 'unfollow']);
    
    //  Job Seeker & Profile Settings
    Route::get('/users/me/profile', [SeekerProfileController::class, 'show']);
    Route::put('/users/me/profile', [SeekerProfileController::class, 'update']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    Route::get('/users/me/settings', [SeekerProfileController::class, 'getSettings']);
    Route::put('/users/me/settings', [SeekerProfileController::class, 'updateSettings']);
    Route::get('/resume/{id}/preview', [SeekerProfileController::class, 'previewResume']);
    Route::get('/notifications', [SeekerProfileController::class, 'notifications']);
    Route::patch('/notifications/{id}/read', [SeekerProfileController::class, 'markNotificationRead']);


}); 

   Route::prefix('employer')->middleware(['auth:sanctum', 'role:employer'])->group(function () {
    
    // Job Management (EmployerJobController)
    Route::get('/jobs/{id}', [EmployerJobController::class, 'show']);
    Route::put('/jobs/{id}', [EmployerJobController::class, 'update']);
    Route::post('/jobs/{id}/close', [EmployerJobController::class, 'close']);

    // Company Profile Management (EmployerCompanyController)
    Route::get('/company', [EmployerCompanyController::class, 'show']);
    Route::put('/company', [EmployerCompanyController::class, 'update']);
});