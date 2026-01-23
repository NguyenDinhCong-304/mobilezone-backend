<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * =========================
     * LOGIN (USER + ADMIN)
     * =========================
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Tìm user theo email (chưa bị xóa)
        $user = User::where('email', $request->email)
            ->whereNull('deleted_at')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // Kiểm tra trạng thái
        if ($user->status == 0) {
            return response()->json([
                'message' => 'Tài khoản đã bị khóa'
            ], 403);
        }

        // Kiểm tra xác thực email
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Vui lòng xác thực email'
            ], 403);
        }

        // Tạo token
        $token = $user->createToken($user->roles . '-token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'token'   => $token,
            'role'    => $user->roles,
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'avatar'   => $user->avatar ? asset($user->avatar) : null,
            ]
        ]);
    }

    /**
     * =========================
     * GET CURRENT USER
     * =========================
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    /**
     * =========================
     * LOGOUT
     * =========================
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
}
