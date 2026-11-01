<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    // --- 1. Available Subscription Plans (Purane code se) ---
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

    // --- 2. Get User Active Subscription (GET /api/subscription) ---
    public function index(Request $request)
    {
        $subscription = $request->user()->subscription;

        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }

    // --- 3. Checkout Session (POST /api/subscription/checkout) ---
    public function checkout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|string'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout session created successfully',
            'checkout_url' => 'https://payment-gateway.url/checkout-session-xyz'
        ]);
    }

    // --- 4. Verify Payment (POST /api/subscription/payment/verify) ---
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string',
            'plan_id' => 'required|string'
        ]);

        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Payment verified and subscription activated successfully'
        ]);
    }

    // --- 5. Webhook Listener (POST /api/subscription/webhook) ---
    public function webhook(Request $request)
    {
        $payload = $request->all();

        return response()->json([
            'status' => 'received'
        ]);
    }

    // --- 6. Cancel Subscription (POST /api/subscription/cancel) ---
    public function cancel(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription;

        if ($subscription) {
            $subscription->update(['status' => 'cancelled']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully'
        ]);
    }

    // --- 7. Invoices (Purane code se - GET /api/subscription/invoices) ---
    public function invoices(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}