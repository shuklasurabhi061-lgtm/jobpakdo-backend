namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostComment;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('user')->latest()->paginate(20);

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

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes_count');

        return response()->json([
            'success' => true,
            'message' => 'Post liked'
        ]);
    }

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
            'data' => $comment
        ], 201);
    }
}
