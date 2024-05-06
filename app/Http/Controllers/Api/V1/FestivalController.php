<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use App\Models\Location;
use Illuminate\Http\Request;

class FestivalController extends Controller
{
    //Lấy festival theo tháng
    public function showPagination($month)
    {
        $startDate = date('Y') . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
        $endDate = date('Y-m-t', strtotime($startDate)) . ' 23:59:59';

        // Thực hiện lọc dữ liệu
        $festival = Festival::whereBetween('created_at', [$startDate, $endDate])->with('location')->paginate(10);
        if (!$festival->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $festival]);
        }
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $festival], 404);
    }
    public function show()
    {
        $data = Festival::with('location')->get();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
    }
    //Thêm festival
    public function add(Request $request)
    {
        $place = new Festival();
        $place->name = $request['name'];
        $place->address = $request['address'];
        $place->description = json_encode($request['description']);
        $place->start_date = $request['start_date'];
        $place->end_date = $request['end_date'];
        $place->price = $request['price'];

        if (intval($request['location']) == 0) {
            $location = Location::firstOrCreate(['name' => $request['location']]);
            $place->location_id = $location->locations_id;
        } else {
            $place->location_id = intval($request['location']);
        }
        $imageDetails = [];
        $publicId = [];
        $uploadedAvatar = null;
        if ($request->hasFile('avatar')) {
            $uploadedAvatar = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'travel/festival'])->getSecurePath();
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $image = cloudinary()->upload($image->getRealPath(), ['folder' => 'travel/festival/details']);
                $images = $image->getSecurePath();
                $id = $image->getPublicId();
                $imageDetails[] = $images;
                $publicId[] = $id;
            };
        }
        if ($uploadedAvatar !== null && !empty($imageDetails) && !empty($publicId)) {
            $place->images = json_encode(['avatar' => $uploadedAvatar, 'imageDetails' => $imageDetails, 'publicId' => $publicId], true);
            $place->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công']);
        }
        return response()->json(['success' => false, 'code' => 422, 'message' => 'Đăng tải thất bại'], 422);
    }
    // Lâys chi tiết festival
    public function showById($id)
    {
        $data = Festival::with('location.articles')->where('festivals_id', $id)->first();

        if ($data) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu', 'data' => $data]);
        }
    }
}
