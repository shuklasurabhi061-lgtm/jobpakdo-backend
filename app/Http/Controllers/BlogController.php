<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    // Public List: Active/Published Blogs
    public function index()
    {
        $blogs = Blog::where('status', 'published')->latest()->paginate(10);
        return response()->json(['success' => true, 'data' => $blogs]);
    }

    // Public Single Blog Detail
    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $blog]);
    }

    // Admin: Store Blog
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string',
            'tag' => 'nullable|string',
            'read_time' => 'nullable|string',
            'status' => 'nullable|in:published,draft',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('blogs', 'public');
            $validated['cover_image'] = asset('storage/' . $path);
        }

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'data' => $blog
        ], 201);
    }

    // Admin: Update Blog
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'excerpt' => 'nullable|string',
            'author' => 'nullable|string',
            'tag' => 'nullable|string',
            'read_time' => 'nullable|string',
            'status' => 'nullable|in:published,draft',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('blogs', 'public');
            $validated['cover_image'] = asset('storage/' . $path);
        }

        $blog->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog
        ]);
    }

    // Admin: Delete Blog
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found'], 404);
        }

        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Blog deleted successfully']);
    }
}
