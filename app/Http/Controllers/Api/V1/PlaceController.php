<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Place;
use App\Models\PlaceComment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PlaceController extends Controller
{
    //Lấy địa danh theo địa điểm
    public function showPlaceByLocation($id, Request $request)
    {
        if (intval($id) === 0) {
            $data = Place::with('location')->where('name', 'like', '%' . $request->input('searchParam') . '%')->paginate(10);
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        }
        $count = Place::with('location')->where('location_id', $id)->count();
        if ($count > 0) {
            $data = Place::with('location')->where('location_id', $id)->where('name', 'like', '%' . $request->input('searchParam') . '%')->paginate(10);
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy địa danh nào', 'data' => []], 201);
    }
    //Lấy sản phẩm theo địa danh
    public function showProductByPlace($id)
    {
        $place = Place::where('places_id', $id)->with('location')->first();
        if ($place) {
            $products = $place->location->products()->paginate(6);

            if ($products->total() > 0) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $products]);
            }
            return response()->json(['success' => false, 'message' => 'Không tìm sản phẩm'], 201);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy địa danh'], 404);
    }
    //Lấy festival theo địa danh
    public function showFestivalByPlace($id)
    {
        $place = Place::where('places_id', $id)->with('location')->first();
        if ($place) {
            $festivals = $place->location->festivals()->with('location')->paginate(4);
            if ($festivals->total() > 0) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $festivals]);
            }
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lễ hội'], 201);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy địa danh'], 404);
    }
    //Lấy bài viết theo địa danh
    public function showArticleByPlace($id)
    {
        $place = Place::where('places_id', $id)->with('location')->first();
        if ($place) {
            $articles = $place->location->articles()->with('user')->paginate(4);

            if ($articles->total() > 0) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $articles]);
            }
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bài viết'], 201);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy địa danh'], 404);
    }
    //Lấy comment theo địa danh
    public function showCommentByPlace($id)
    {
        $place = Place::where('places_id', $id)->with('location')->first();
        if ($place) {

            $comments = $place->comments()->with('user')->paginate(10);
            if ($comments->total() > 0) {
                return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $comments]);
            }
            return response()->json(['success' => false, 'message' => 'Không tìm thấy comments'], 201);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy địa danh'], 404);
    }
    //Thêm comment
    public function addComment(Request $request)
    {
        $comment = new PlaceComment();
        $comment->content = $request['content'];
        $comment->user_id = $request->user()->users_id;
        $comment->place_id = $request['place_id'];
        $comment->save();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
    }
    // Lấy tất cả địa danh
    public function show()
    {
        $data = DB::table('places')->join('locations', 'places.location_id', 'locations_id')->select('places.*', 'locations.name as location_name')->get();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
    }
    //Thêm địa danh
    public function add(Request $request)
    {
        $place = new Place();
        $place->name = $request['name'];
        $place->address = $request['address'];
        $place->description = json_encode($request['description']);
        $place->longitude = ($request['longitude'] == "undefined") ? null : $request['longitude'];
        $place->latitude = ($request['latitude']  == "undefined") ? null : $request['latitude'];
        if (intval($request['location']) == 0) {
            $location = Location::firstOrCreate(['name' => $request['location']]);
            $place->location_id = $location->locations_id;
        } else {
            $place->location_id = intval($request['location']);
        }
        $subDescription = [];
        if (is_array($request['subDescription'])) {
            foreach ($request['subDescription'] as $item) {
                $subDescription[] = json_decode($item, true);
            };
        }

        $imageDetails = [];
        $publicId = [];
        $uploadedAvatar = null;
        if ($request->hasFile('avatar')) {
            $uploadedAvatar = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'travel/place'])->getSecurePath();
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $image = cloudinary()->upload($image->getRealPath(), ['folder' => 'travel/place/details']);
                $images = $image->getSecurePath();
                $id = $image->getPublicId();
                $imageDetails[] = $images;
                $publicId[] = $id;
            };
        }

        if ($uploadedAvatar !== null && !empty($imageDetails) && !empty($publicId)) {
            $place->images = (['avatar' => $uploadedAvatar, 'imageDetails' => $imageDetails, 'publicId' => $publicId, 'subDescription' => $subDescription]);
            $place->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
        }
        return response()->json(['success' => false, 'code' => 422, 'message' => 'Đăng tải thất bại'], 422);
    }

    // Lấy chi tiết theo Id
    public function showById($id)
    {
        $data = Place::with('location')->where('places_id', $id)->get();

        if (!$data->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy địa điểm'], 404);
        }
    }
}
