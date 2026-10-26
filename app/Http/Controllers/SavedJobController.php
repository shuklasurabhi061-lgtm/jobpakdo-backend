<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function index(Request $request)
    {
        $savedJobs = SavedJob::where('user_id', $request->user()->id)
            ->with('job')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $savedJobs
        ]);
    }

    public function store(Request $request, $jobId)
    {
        $saved = SavedJob::firstOrCreate([
            'user_id' => $request->user()->id,
            'job_id' => $jobId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job saved successfully',
            'data' => $saved
        ], 201);
    }

    public function destroy(Request $request, $jobId)
    {
        SavedJob::where('user_id', $request->user()->id)
            ->where('job_id', $jobId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job unsaved successfully'
        ]);
    }
}