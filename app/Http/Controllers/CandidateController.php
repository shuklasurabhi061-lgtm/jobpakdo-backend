namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'seeker');

        // Search by keyword/skills
        if ($request->has('q')) {
            $search = $request->query('q');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $candidates->items(),
            'meta' => [
                'page' => $candidates->currentPage(),
                'total' => $candidates->total()
            ]
        ]);
    }
}
