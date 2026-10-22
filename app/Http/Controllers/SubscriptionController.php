<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // List available subscription plans (GET /api/plans)
    public function plans()
    {
        $plans = [
            [
                'id' => 'basic',
                'name' => 'Basic Employer',
                'price' => 0,
                'job_limit' => 3,
                'features' => ['3 Active Job Posts', 'Standard Applicant Search']
            ],
            [
                'id' => 'pro',
                'name' => 'Pro Recruiter',
                'price' => 49,
                'job_limit' => 20,
                'features' => ['20 Active Job Posts', 'Advanced Candidate Search', 'Direct Messaging']
            ],
            [
                'id' => 'enterprise',
                'name' => 'Enterprise',
                'price' => 199,
                'job_limit' => 100,
                'features' => ['Unlimited Job Posts', 'Dedicated Support', 'Featured Company Badge']
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    // Get current user billing/invoices (GET /api/subscription/invoices)
    public function invoices(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}
