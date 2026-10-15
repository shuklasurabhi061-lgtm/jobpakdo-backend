namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyFollow;

class CompanyFollowController extends Controller
{
    public function follow(Request $request, $id)
    {
        $user = $request->user();

        CompanyFollow::firstOrCreate([
            'user_id' => $user->id,
            'company_id' => $id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company followed successfully'
        ], 201);
    }

    public function unfollow(Request $request, $id)
    {
        $user = $request->user();

        CompanyFollow::where('user_id', $user->id)
            ->where('company_id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company unfollowed successfully'
        ]);
    }
}
