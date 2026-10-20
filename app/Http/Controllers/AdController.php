<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    // Public: List active ads
    public function index()
    {
        $ads = Ad::where('status', 'active')->latest()->get();
        return response()->json(['success' => true, 'data' => $ads]);
    }

    // Admin: Store Ad
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'banner_image' => 'nullable|string',
            'link_url' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $ad = Ad::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ad created successfully',
            'data' => $ad
        ], 201);
    }

    // Admin: Update Ad
    public function update(Request $request, $id)
    {
        $ad = Ad::find($id);

        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Ad not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'banner_image' => 'nullable|string',
            'link_url' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $ad->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ad updated successfully',
            'data' => $ad
        ]);
    }

    // Admin: Delete Ad
    public function destroy($id)
    {
        $ad = Ad::find($id);

        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Ad not found'], 404);
        }

        $ad->delete();

        return response()->json(['success' => true, 'message' => 'Ad deleted successfully']);
    }
}
