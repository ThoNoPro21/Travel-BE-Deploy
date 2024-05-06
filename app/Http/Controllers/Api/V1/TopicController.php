<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function show()
    {
        $data = Topic::all();
        if ($data->isEmpty()) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Không có dữ liệu', 'data' => $data]);
        } else {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $data]);
        }
    }
    public function add(Request $request)
    {
        $data = Topic::firstOrCreate([
            'name' => $request['name']
        ]);
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
    }

    public function showById($id)
    {
        $data = Topic::where('topics_id', $id)->first();
        if ($data) {
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'code' => 404, 'message' => 'Không tìm thấy dữ liệu', 'data' => $data]);
        }
    }
}
