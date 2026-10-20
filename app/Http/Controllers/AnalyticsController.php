<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    // Employer Dashboard Analytics
    public function employerAnalytics()
    {
        $employerId = Auth::id();

        $totalJobs = Job::where('user_id', $employerId)->count();
        $totalApplications = Application::whereHas('job', function($q) use ($employerId) {
            $q->where('user_id', $employerId);
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_jobs_posted' => $totalJobs,
                'total_applications_received' => $totalApplications,
            ]
        ]);
    }

    // Admin Dashboard Analytics
    public function adminAnalytics()
    {
        $totalUsers = User::count();
        $totalJobs = Job::count();
        $totalApplications = Application::count();
        $totalBlogs = Blog::count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_jobs' => $totalJobs,
                'total_applications' => $totalApplications,
                'total_blogs' => $totalBlogs,
            ]
        ]);
    }
}
