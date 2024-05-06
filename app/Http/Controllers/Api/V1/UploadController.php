<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
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
