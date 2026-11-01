<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Post;
use App\Models\Ad;

class AdminController extends Controller
{
    // --- 1. Dashboard Stats (Naye code se) ---
    public function dashboard(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_seekers' => User::where('role', 'seeker')->count(),
            'total_jobs' => Job::count(),
            'total_posts' => Post::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // --- 2. List All Users for Moderation (Purane code se) ---
    public function users(Request $request)
    {
        $users = User::latest()->paginate(15);
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // --- 3. Toggle User Status (Purane code se) ---
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

    // --- 4. Remove Job Post (Purane code se) ---
    public function removeJob($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job post removed by admin'
        ]);
    }

    // --- 5. Employers Moderation (Naye code se) ---
    public function employers(Request $request)
    {
        $employers = User::where('role', 'employer')->with('company')->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $employers]);
    }

    public function verifyEmployer($id)
    {
        $employer = User::where('role', 'employer')->findOrFail($id);
        $employer->update(['is_verified' => true]);

        return response()->json(['success' => true, 'message' => 'Employer verified successfully']);
    }

    public function suspendEmployer($id)
    {
        $employer = User::where('role', 'employer')->findOrFail($id);
        $employer->update(['status' => 'suspended']);

        return response()->json(['success' => true, 'message' => 'Employer suspended successfully']);
    }

    // --- 6. Jobs Moderation & Featured Toggle (Naye code se) ---
    public function jobs(Request $request)
    {
        $jobs = Job::with('company')->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function toggleFeaturedJob($id)
    {
        $job = Job::findOrFail($id);
        $job->update(['is_featured' => !$job->is_featured]);

        return response()->json(['success' => true, 'message' => 'Job featured status updated', 'data' => $job]);
    }

    // --- 7. Moderation Queue / Posts (Naye code se) ---
    public function moderationQueue(Request $request)
    {
        $queue = Post::where('status', 'pending')->with(['user', 'company'])->paginate(15);
        return response()->json(['success' => true, 'data' => $queue]);
    }

    public function approveModeration($id)
    {
        $post = Post::findOrFail($id);
        $post->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => 'Item approved successfully']);
    }

    public function rejectModeration($id)
    {
        $post = Post::findOrFail($id);
        $post->update(['status' => 'rejected']);

        return response()->json(['success' => true, 'message' => 'Item rejected successfully']);
    }

    public function bulkApproveModeration(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Post::whereIn('id', $request->ids)->update(['status' => 'approved']);

        return response()->json(['success' => true, 'message' => 'Selected items approved successfully']);
    }

    // --- 8. Ads Management (Naye code se) ---
    public function getAds()
    {
        $ads = Ad::latest()->paginate(10);
        return response()->json(['success' => true, 'data' => $ads]);
    }

    public function storeAd(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image_url' => 'required|string',
            'link' => 'required|url'
        ]);

        $ad = Ad::create($request->all());

        return response()->json(['success' => true, 'message' => 'Ad created successfully', 'data' => $ad], 201);
    }

    public function destroyAd($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();

        return response()->json(['success' => true, 'message' => 'Ad deleted successfully']);
    }

    // --- 9. Subscriptions & Settings (Naye code se) ---
    public function subscriptions(Request $request)
    {
        return response()->json(['success' => true, 'data' => []]);
    }

    public function getSettings()
    {
        return response()->json(['success' => true, 'data' => []]);
    }

    public function updateSettings(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'Platform settings updated successfully']);
    }
}