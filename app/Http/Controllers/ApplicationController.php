<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // 1. Job Seeker apply karega
    public function store(Request $request)
    {
        $fields = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'resume_link' => 'nullable|url',
        ]);

        // Duplicate Check
        $exists = Application::where('user_id', $request->user()->id)
                             ->where('job_id', $fields['job_id'])
                             ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this job.'
            ], 400);
        }

        $application = Application::create([
            'user_id' => $request->user()->id,
            'job_id' => $fields['job_id'],
            'resume_link' => $fields['resume_link'] ?? null,
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => $application
        ], 201);
    }

    // 2. Employer apni jobs par aayi applications dekhega
    public function employerApplications(Request $request)
    {
        $user = $request->user();

        $applications = Application::whereHas('job', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['job', 'user'])->get();

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    // 3. Employer status update karega (Security & Auth added)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Accepted,Rejected,Shortlisted',
        ]);

        $application = Application::with('job')->find($id);

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found'], 404);
        }

        // Authorization check
        if ($application->job->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You cannot modify applications for other employers.'
            ], 403);
        }

        $application->status = $request->status;
        $application->save();

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully!',
            'data' => $application
        ]);
    }

    // 4. Job Seeker apni applications dekhega
    public function seekerApplications(Request $request)
    {
        $applications = Application::where('user_id', $request->user()->id)
                            ->with('job')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }
}