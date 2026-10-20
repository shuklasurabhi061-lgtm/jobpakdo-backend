<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class EmployerCompanyController extends Controller
{
    // Upload Company Logo
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $company = Company::where('user_id', $request->user()->id)->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found'
            ], 404);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company/logos', 'public');
            $company->logo_url = asset('storage/' . $path);
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully',
                'data' => [
                    'logo_url' => $company->logo_url
                ]
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed'], 400);
    }

    // Upload Company Cover
    public function uploadCover(Request $request)
    {
        $request->validate([
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $company = Company::where('user_id', $request->user()->id)->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company profile not found'
            ], 404);
        }

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('company/covers', 'public');
            $company->cover_url = asset('storage/' . $path);
            $company->save();

            return response()->json([
                'success' => true,
                'message' => 'Cover image uploaded successfully',
                'data' => [
                    'cover_url' => $company->cover_url
                ]
            ], 200);
        }

        return response()->json(['success' => false, 'message' => 'File upload failed'], 400);
    }
}
