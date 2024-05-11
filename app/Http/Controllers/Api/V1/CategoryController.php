<?php

namespace App\Http\Controllers\api\v1\CategoryController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show()
    {
        $data = Category::all();
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công', 'data' => $data]);
    }
    public function add(Request $request)
    {
        $data = Category::firstOrCreate([
            'name' => $request['name']
        ]);
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!']);
    }
}
