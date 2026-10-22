<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;

class AdminController extends Controller
{
    // List all users for moderation (GET /api/admin/users)
    public function users(Request $request)
    {
        $users = User::latest()->paginate(15);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Toggle user active status (PATCH /api/admin/users/{id}/status)
    public function toggleUserStatus($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->is_active = $request->input('is_active', !$user->is_active);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully',
            'data' => $user
        ]);
    }

    // Moderate/Remove a job post (PATCH /api/admin/jobs/{id}/remove)
    public function removeJob($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job post removed by admin'
        ]);
    }
}
