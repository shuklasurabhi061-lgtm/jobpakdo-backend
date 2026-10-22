<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function index(Request $request)
    {
        $resume = Resume::where('user_id', $request->user()->id)->first();
        return response()->json([
            'success' => true,
            'data' => $resume
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        $user = $request->user();

        $existingResume = Resume::where('user_id', $user->id)->first();
        if ($existingResume) {
            Storage::disk('public')->delete($existingResume->file_path);
            $existingResume->delete();
        }

        $file = $request->file('resume');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('resumes', $fileName, 'public');

        $resume = Resume::create([
            'user_id' => $user->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'visible' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resume uploaded successfully',
            'data' => $resume
        ], 201);
    }

    public function toggleVisibility(Request $request)
    {
        $resume = Resume::where('user_id', $request->user()->id)->first();

        if (!$resume) {
            return response()->json(['success' => false, 'message' => 'Resume not found'], 404);
        }

        $resume->visible = $request->input('visible', !$resume->visible);
        $resume->save();

        return response()->json([
            'success' => true,
            'message' => 'Resume visibility updated',
            'data' => $resume
        ]);
    }

    public function destroy($id, Request $request)
    {
        $resume = Resume::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$resume) {
            return response()->json(['success' => false, 'message' => 'Resume not found'], 404);
        }

        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resume deleted successfully'
        ]);
    }
}
