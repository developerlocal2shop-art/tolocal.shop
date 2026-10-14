<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\Review;

class ReviewController extends Controller
{

    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $pageType = $request->input('page_type');

            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'reviews';
            $data['columns'] = ['user_id', 'page'];
            //$review = Helper::masterSearch($data);
            $review = DB::table('reviews')
                ->when($searchQuery, function ($q) use ($searchQuery) {
                    $q->where('content', 'like', '%' . $searchQuery . '%')->orWhere('page', 'like', '%' . $searchQuery . '%');
                })
                ->when($pageType, function ($q) use ($pageType) {
                    $q->where('page', $pageType);
                })
                //->where('directory_id', Auth::user()->directory_id)
                ->orderByDesc('id')->paginate(10);

            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.review.index', ['review' => $review, 'data' => $data, 'query' => $query]);
        } else {
            $review = DB::table('reviews')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.review.index', ['review' => $review, 'data' => $data]);
        }
    }

    public function create()
    {
        return view('admin.review.create');
    }

    public function submitReview(Request $request)
    {
        $this->validateInput($request);
        $review = new Review();
        $review->page                      = $request->page;
        $review->content                   = $request->content;
        $review->save();
        return redirect('admin/review')->with('msg', 'Review Added Successfully!');
    }

    private function validateInput($request)
    {
        $this->validate($request, [
            'page' => 'required',
            'content' => 'required',
        ]);
    }

    public function changeReviewStatus(Request $request)
    {
        $review = Review::find($request->id);
        $review->status = $request->status;
        $review->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function editReview($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $reviewData = Review::with('photos')->findOrFail($id);
        return view('admin.review.edit', compact('reviewData'));
    }

    public function updateReview(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'page'  => 'required',
            'content' => 'required'
        ]);
        Review::whereId($id)->update($updateData);
        return redirect('admin/review')->with('msg', 'Review has been updated');
    }

    public function destroy($id)
    {
        Review::find($id)->delete();
        return redirect('admin/review')->with('msg', 'Review deleted successfully');
    }
}
