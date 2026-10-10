<?php

namespace App\Http\Controllers;

use App\Models\Application as JobApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // Seeker applies for a job
    public function store(Request $request)
    {
        $fields = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'resume_link' => 'nullable|url',
        ]);

        $application = JobApplication::create([
            'job_id' => $fields['job_id'],
            'user_id' => $request->user()->id,
            'resume_link' => $fields['resume_link'] ?? null,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application
        ], 201);
    }

    // Employer views applications received for their posted jobs
    public function employerApplications(Request $request)
    {
        $user = $request->user();

    // TEMPORARY DEBUG: Check karne ke liye ki konsa employer login hai aur uski kitni jobs hain
   /* return response()->json([
        'logged_in_user_id' => $user->id,
        'user_role' => $user->role,
        'employer_jobs' => \App\Models\Job::where('user_id', $user->id)->get(),
        'all_applications_in_db' => \App\Models\Application::all()
    ]);*/


        /*if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized. Only employers can view applications.'], 403);
        }

        $applications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['job', 'user'])->get();

        return response()->json([
            'applications' => $applications
        ], 200);*/

       

    // Employer ki banayi gayi sari jobs ki IDs nikal lo
    $jobIds = \App\Models\Job::where('user_id', $user->id)->pluck('id');

    // Un jobs par jitni applications aayi hain unhe fetch karo
    $applications = \App\Models\Application::whereIn('job_id', $jobIds)->with('job')->get();

    return response()->json([
        'applications' => $applications
    ]);
}
public function updateStatus(Request $request, $id)
{
    // 1. Validate request (sirf in 3 me se koi ek status chalega)
    $request->validate([
        'status' => 'required|in:pending,accepted,rejected',
    ]);

    // 2. Application find karein
    $application = \App\Models\Application::with('job')->find($id);

    if (!$application) {
        return response()->json(['message' => 'Application not found'], 404);
    }

    // 3. Authorization Check: Kya ye application ishi employer ki job par aayi hai?
    if ($application->job->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'Unauthorized. You cannot modify applications for other employers.'
        ], 403);
    }

    // 4. Status update aur save
    $application->status = $request->status;
    $application->save();

    return response()->json([
        'message' => 'Application status updated successfully!',
        'application' => $application
    ]);
}
public function seekerApplications(Request $request)
{
    $user = $request->user();

    // Check karein ki login user seeker hai ya nahi
    if ($user->role !== 'seeker') {
        return response()->json([
            'message' => 'Unauthorized. Only job seekers can view their applied applications.'
        ], 403);
    }

    // Login seeker ki sari applications unki job details ke sath fetch karein
    $applications = \App\Models\Application::where('user_id', $user->id)
                        ->with('job')
                        ->get();

    return response()->json([
        'applications' => $applications
    ]);
}
}