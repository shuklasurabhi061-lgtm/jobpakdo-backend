<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    // GET /api/companies (Directory search)
    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('industry', 'like', '%' . $request->search . '%');
        }

        $companies = $query->paginate(10);
        return response()->json(['success' => true, 'data' => $companies]);
    }

    // GET /api/companies/{id} (Public company profile)
    public function show($id)
    {
        $company = Company::with(['employer', 'jobs' => function($q) {
            $q->where('status', 'active');
        }])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $company]);
    }

    // GET /api/companies/{id}/jobs
    public function jobs($id)
    {
        $company = Company::findOrFail($id);
        $jobs = $company->jobs()->where('status', 'active')->paginate(10);
        return response()->json(['success' => true, 'data' => $jobs]);
    }

    // GET /api/companies/{id}/posts
    public function posts($id)
    {
        $company = Company::findOrFail($id);
        $posts = $company->posts()->latest()->paginate(10);
        return response()->json(['success' => true, 'data' => $posts]);
    }

    // GET /api/companies/{id}/employees
    public function employees($id)
    {
        $company = Company::findOrFail($id);
        // Assuming an employees relationship or team members table
        $employees = $company->employees()->paginate(10);
        return response()->json(['success' => true, 'data' => $employees]);
    }
}