<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostComment; // Agar aapne Comment model use kiya hai, ya PostComment

class FeedController extends Controller
{
    // --- 1. Feed List ---
    public function index(Request $request)
    {
        $posts = Post::with(['user'])->withCount(['likes', 'comments'])->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'meta' => [
                'page' => $posts->currentPage(),
                'limit' => $posts->perPage(),
                'total' => $posts->total(),
                'total_pages' => $posts->lastPage()
            ]
        ]);
    }

    // --- 2. Create Post ---
    public function store(Request $request)
    {
        $fields = $request->validate([
            'text' => 'required|string',
            'image_url' => 'nullable|string'
        ]);

        $post = $request->user()->posts()->create($fields);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    // --- 3. Like Post ---
    public function like($id, Request $request)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes_count');

        return response()->json([
            'success' => true,
            'message' => 'Post liked'
        ]);
    }

    // --- 4. Add Comment ---
    public function addComment(Request $request, $id)
    {
        $fields = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = PostComment::create([
            'post_id' => $id,
            'user_id' => $request->user()->id,
            'comment' => $fields['comment']
        ]);

        $post = Post::findOrFail($id);
        $post->increment('comments_count');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    // --- 5. Trending Feed ---
    public function trending(Request $request)
    {
        $posts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->orderBy('likes_count', 'desc')
            ->latest()
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $posts]);
    }

    // --- 6. Single Post Detail ---
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $post]);
    }

    // --- 7. Update Post ---
    public function update($id, Request $request)
    {
        $request->validate(['text' => 'required|string']);

        $post = Post::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $post->update(['text' => $request->text]);

        return response()->json(['success' => true, 'message' => 'Post updated successfully', 'data' => $post]);
    }

    // --- 8. Delete Post ---
    public function destroy($id, Request $request)
    {
        $post = Post::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $post->delete();

        return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
    }

    // --- 9. Unlike Post ---
    public function unlike($id, Request $request)
    {
        $post = Post::findOrFail($id);
        if ($post->likes_count > 0) {
            $post->decrement('likes_count');
        }

        return response()->json(['success' => true, 'message' => 'Post unliked successfully']);
    }

    // --- 10. Get Comments for a Post ---
    public function comments($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments()->with('user')->latest()->paginate(10);

        return response()->json(['success' => true, 'data' => $comments]);
    }

    // --- 11. Delete Comment ---
    public function destroyComment($id, Request $request)
    {
        $comment = Comment::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $comment->delete();

        return response()->json(['success' => true, 'message' => 'Comment deleted successfully']);
    }

    // --- 12. Share Post ---
    public function share($id, Request $request)
    {
        $post = Post::findOrFail($id);
        $post->increment('shares_count');

        return response()->json(['success' => true, 'message' => 'Post shared successfully']);
    }
}