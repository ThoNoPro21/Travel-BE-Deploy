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
        $latestReviews = Review::select('reviews.user_id', DB::raw('MAX(reviews.created_at) as latest_review_date'))
            ->groupBy('reviews.user_id');

        // Lấy tổng số sao từ lượt đánh giá cuối cùng của mỗi người dùng
        $userRatings = Review::select('reviews.user_id', DB::raw('reviews.rating'))
            ->joinSub($latestReviews, 'latest_reviews', function ($join) {
                $join->on('reviews.user_id', '=', 'latest_reviews.user_id')
                    ->on('reviews.created_at', '=', 'latest_reviews.latest_review_date');
            })
            ->groupBy('reviews.user_id');

        // Lấy tỷ lệ sao trung bình
        $totalStars = $userRatings->sum('reviews.rating');
        $totalUsers = $userRatings->count();
        $totalStars = $userRatings->sum(DB::raw('SUM(reviews.rating)'));
        $averageRating = $totalStars / $totalUsers;
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $averageRating]);
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
