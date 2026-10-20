<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Public: List all published FAQs
    public function index()
    {
        $faqs = Faq::where('status', 'published')->get();
        return response()->json(['success' => true, 'data' => $faqs]);
    }

    // Admin: Store FAQ
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'status' => 'nullable|in:published,draft',
        ]);

        $faq = Faq::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'FAQ created successfully',
            'data' => $faq
        ], 201);
    }

    // Admin: Update FAQ
    public function update(Request $request, $id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }

        $validated = $request->validate([
            'question' => 'sometimes|required|string|max:255',
            'answer' => 'sometimes|required|string',
            'status' => 'nullable|in:published,draft',
        ]);

        $faq->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'FAQ updated successfully',
            'data' => $faq
        ]);
    }

    // Admin: Delete FAQ
    public function destroy($id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
        }

        $faq->delete();

        return response()->json(['success' => true, 'message' => 'FAQ deleted successfully']);
    }
}