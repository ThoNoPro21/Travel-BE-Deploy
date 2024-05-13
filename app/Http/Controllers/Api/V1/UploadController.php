<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Review;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    //Lấy trung bình cộng review
    public function averageRating()
    {
        $latestUserId = DB::table('reviews')->latest('created_at')->value('user_id');
        $latestReviewCreatedAt = DB::table('reviews')->latest('created_at')->value('created_at');

        $users = DB::table('users')
            ->join('reviews', 'reviews.user_id', 'users.users_id')
            ->where('reviews.user_id', $latestUserId)
            ->where('reviews.created_at', $latestReviewCreatedAt)
            ->get();

        $reviews = DB::table('reviews')
            ->where('created_at', $latestReviewCreatedAt)
            ->select('rating')
            ->get();

        $totalStars = $reviews->sum('rating');
        $totalUsers = $users->count();
        $averageRating = $totalUsers > 0 ? $totalStars / $totalUsers : 0;

        $oneStarCount = $reviews->where('rating', 1)->count();
        $twoStarCount = $reviews->where('rating', 2)->count();
        $threeStarCount = $reviews->where('rating', 3)->count();
        $fourStarCount = $reviews->where('rating', 4)->count();
        $fiveStarCount = $reviews->where('rating', 5)->count();

        $totalReviews = $reviews->count();

        $oneStarPercentage = ($oneStarCount / $totalReviews) * 100;
        $twoStarPercentage = ($twoStarCount / $totalReviews) * 100;
        $threeStarPercentage = ($threeStarCount / $totalReviews) * 100;
        $fourStarPercentage = ($fourStarCount / $totalReviews) * 100;
        $fiveStarPercentage = ($fiveStarCount / $totalReviews) * 100;
        return response()->json([
            'success' => true, 'code' => 200, 'message' => 'Thành công!',
            'data' => [
                'average' => $averageRating,
                'oneStar' => $oneStarPercentage,
                'twoStar' => $twoStarPercentage,
                'threeStar' => $threeStarPercentage,
                'fourStar' => $fourStarPercentage,
                'fiveStar' => $fiveStarPercentage
            ]
        ]);
    }
    // Thêm review web
    public function addReview(Request $request)
    {
        $review = new Review();
        if (intval($request['rating'])) {
            $review->rating = $request['rating'];
            $review->content = $request['content'];
            $review->user_id = $request->user()->users_id;
            $review->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Lỗi!'], 404);
    }
    // Lấy tất cả carousel có trạng thái hiển thị là 1
    public function showAll()
    {
        $carousels = Carousel::where('status', 1)->get();
        if (!$carousels->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $carousels]);
        }
        return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu!',]);
    }
    //Cập nhật status
    public function updateStatus(Request $request, $id)
    {
        $carousel = Carousel::where('carousels_id', $id)->first();
        if ($carousel) {
            if ($request->has('status')) {
                $carousel->status = intval($request['status']);
                $carousel->save();
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Cập nhật thành công!',]);
            }
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy!',], 404);
        }
    }
    //Xóa carousel 
    public function delete($id)
    {
        try {
            $data = Carousel::where('carousels_id', $id)->first();
            if ($data) {
                Cloudinary::destroy($data->publicId);
                $data->delete();
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Xóa thành công!',]);
            } else {
                return "Không tìm thấy carousel có ID: $id";
            }
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return $e->getMessage();
        }
    }
    // Lấy tất cả carousel 
    public function show()
    {
        $data = Carousel::paginate(10);
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $data]);
    }
    // Thêm ảnh trong carousel 
    public function add(Request $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $img = cloudinary()->upload($image->getRealPath(), ['folder' => 'travel/upload']);
                $pathImage = $img->getSecurePath();
                $id = $img->getPublicId();
                $carousel = new Carousel();
                $carousel->image =  $pathImage;
                $carousel->publicId =  $id;
                $carousel->save();
            };
            return response()->json(['success' => true, 'message' => 'Tải lên thành công!']);
        }
        return response()->json(['success' => false, 'message' => 'Thất bại!'], 404);
    }
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $image = cloudinary()->upload($request->file('upload')->getRealPath(), ['folder' => 'travel/upload']);
            $pathImage = $image->getSecurePath();
            return response()->json(['uploaded' => true, 'message' => 'Thành công!', 'url' => $pathImage]);
        }
        return response()->json(['uploaded' => false, 'message' => 'Thất bại!'], 422);
    }
    public function uploadPreview(Request $request)
    {
        return $request->all();
    }
}
