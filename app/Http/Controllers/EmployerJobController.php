<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class EmployerJobController extends Controller
{
    // Employer ke khud ke posted jobs list karna
    public function index(Request $request)
    {
        $jobs = Job::where('user_id', $request->user()->id)->get();
        return response()->json(['success' => true, 'data' => $jobs]);
    }

    // Employer naya job post karega
    public function store(Request $request)
    {
       $validated = $request->validate([
    'title' => 'required|string|max:255',
    'company' => 'required|string|max:255', // Ise add kar dein
    'type' => 'required|string',
    'location' => 'required|string',
    'salary' => 'nullable|string',
    'description' => 'nullable|string',
     ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'Live';

        $job = Job::create($validated);

        return response()->json([
            'success' => true, 
            'message' => 'Job posted successfully', 
            'data' => $job
        ], 201);
    }

    // Job status update (Live / Paused / Closed)
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Live,Paused,Closed']);
        
        $job = Job::where('user_id', $request->user()->id)->findOrFail($id);
        $job->update(['status' => $request->status]);

        return response()->json([
            'success' => true, 
            'message' => 'Job status updated successfully', 
            'data' => $job
        ]);
    }
}