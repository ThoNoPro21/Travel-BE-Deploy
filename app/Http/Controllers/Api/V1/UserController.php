<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($request->has('name')) {
                $user->name = $request['name'];
            }

            if ($request->has('story')) {
                $user->story = $request['story'];
            }

            if ($request->hasFile('avatar')) {
                $user->avatar = cloudinary()->upload($request->file('avatar')->getRealPath(), ['folder' => 'travel/user'])->getSecurePath();
            }
            $user->save();
            return response()->json(['success' => true, 'code' => 200, 'message' => 'Cập nhật thành công']);
        }
    }
}
