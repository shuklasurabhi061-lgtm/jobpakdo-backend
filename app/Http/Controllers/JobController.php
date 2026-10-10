<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
   // All Jobs (Seekers & Public) - With Search & Filter
public function index(Request $request)
{
    $query = Job::with('user');

    // Title, description ya company name se search
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%");
        });
    }

    // Location se filter
    if ($request->has('location') && !empty($request->location)) {
        $query->where('location', 'like', "%{$request->location}%");
    }

    $jobs = $query->latest()->get();

    return response()->json([
        'jobs' => $jobs
    ]);
}

    // Single Job Detail
    public function show($id)
    {
        $job = Job::with('user')->find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json($job, 200);
    }

    // Post a Job (Employer Only)
    public function store(Request $request)
    {
        // Check if authenticated user is an Employer
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized. Only employers can post jobs.'], 403);
        }

        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'company' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        $job = $request->user()->jobs()->create($fields);

        return response()->json([
            'message' => 'Job posted successfully',
            'job' => $job
        ], 201);
    }
    // Delete a Job (Employer Only)
public function destroy(Request $request, $id)
{
    $job = Job::find($id);

    if (!$job) {
        return response()->json(['message' => 'Job not found'], 404);
    }

    // Check if the authenticated user is the owner of the job
    if ($job->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized. You can only delete your own jobs.'], 403);
    }

    $job->delete();

    return response()->json([
        'message' => 'Job deleted successfully'
    ], 200);
}
}