<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Lấy thông tin User
    public function getMe(Request $request)
    {
        return response()->json(['success' => true, 'code' => 200, 'message' => 'Thành công!', 'data' => $request->user()]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required',],
            'password' => 'required'
        ], [
            "email.required" => "Vui lòng nhập ID !",
            "password.required" => "Vui lòng nhập mật khẩu !"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác !'], 401);
        }
        $token = $user->createToken('token_name')->plainTextToken;
        return response()->json([
            'code' => 200,
            'success' => true,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "Đăng xuất thành công !",
            "success" => true,
            "code" => 200
        ]);
    }
}
