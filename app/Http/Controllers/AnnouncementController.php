<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // Public: List active announcements
    public function index()
    {
        $announcements = Announcement::where('status', 'published')->latest()->get();
        return response()->json(['success' => true, 'data' => $announcements]);
    }

    // Admin: Store Announcement
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'nullable|in:published,draft',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully',
            'data' => $announcement
        ], 201);
    }

    // Admin: Update Announcement
    public function update(Request $request, $id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json(['success' => false, 'message' => 'Announcement not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'status' => 'nullable|in:published,draft',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully',
            'data' => $announcement
        ]);
    }

    // Admin: Delete Announcement
    public function destroy($id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json(['success' => false, 'message' => 'Announcement not found'], 404);
        }

        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted successfully']);
    }
}
