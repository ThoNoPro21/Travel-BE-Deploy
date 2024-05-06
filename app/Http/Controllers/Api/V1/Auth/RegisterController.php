<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // $request->validate(
        //     [
        //         'name' => ['required', 'min:3', 'max:25'],
        //         'email' => ['required', 'email', 'unique:users,email'],
        //         'password' => ['required', Password::min(6)->mixedCase()->numbers(), 'confirmed'],
        //         'password_confirmation' => ['required'],
        //     ],
        //     [
        //         "name.required" => "Vui lòng nhập tên !",
        //         "name.min" => "Tối thiểu 3 - 25 kí tự !",
        //         "name.max" => "Tối thiểu 3 - 25 kí tự !",

        //         "email.required" => "Vui lòng nhập email !",
        //         "email.email" => "Định dạng phải là email !",
        //         "email.unique" => "Email đã được sử dụng !",

        //         "password.required" => "Vui lòng nhập mật khẩu !",
        //         "password.confirmed" => "Mật khẩu không khớp !",

        //         "password_confirmation.required" => "Vui lòng nhập mật khẩu !",
        //     ]
        // );

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->role = $request['role'] ?? 3;
        $user->save();
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Đăng ký thành công!',
        ]);
    }
}
