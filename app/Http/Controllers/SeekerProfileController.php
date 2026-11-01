<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resume;
use App\Models\Notification;

class SeekerProfileController extends Controller
{
    // GET /api/users/me/profile
    public function show(Request $request)
    {
        $user = $request->user()->load('profile', 'skills', 'experience');
        return response()->json(['success' => true, 'data' => $user]);
    }

    // PUT /api/users/me/profile
    public function update(Request $request)
    {
        $user = $request->user();
        // Validation and update logic here
        $user->update($request->only(['name', 'phone', 'bio']));
        
        if ($request->has('profile')) {
            $user->profile()->updateOrCreate(['user_id' => $user->id], $request->input('profile'));
        }

        return response()->json(['success' => true, 'message' => 'Profile updated successfully', 'data' => $user->load('profile')]);
    }

    // GET /api/users/me/settings
    public function getSettings(Request $request)
    {
        $settings = $request->user()->settings ?? []; // Assuming a settings relation or JSON column
        return response()->json(['success' => true, 'data' => $settings]);
    }

    // PUT /api/users/me/settings
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        // Update user settings logic
        return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
    }

    // GET /api/resume/{id}/preview
    public function previewResume($id, Request $request)
    {
        $resume = Resume::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        return response()->json(['success' => true, 'data' => $resume]);
    }

    // GET /api/notifications
    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(15);
        return response()->json(['success' => true, 'data' => $notifications]);
    }

    // PATCH /api/notifications/{id}/read
    public function markNotificationRead($id, Request $request)
    {
        $notification = Notification::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    // GET /api/users/{id}/profile (Public Candidate Profile)
    public function publicProfile($id)
    {
        $user = User::with(['profile', 'skills', 'experience'])->where('role', 'seeker')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $user]);
    }
}