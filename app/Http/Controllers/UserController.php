<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->orderBy('id', 'desc');

        //Tìm kiếm theo tên, email hoặc username
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%");
            });
        }

        //Lọc theo vai trò (roles)
        if ($request->has('roles') && $request->roles !== '') {
            $query->where('roles', $request->roles);
        }

        //Lọc theo trạng thái (status)
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        //Phân trang
        $users = $query->paginate(10);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Tạo validator thủ công để tùy chỉnh kiểm tra
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username',
            'email' => 'required|email|unique:user,email',
            'phone' => [
                'required',
                'string',
                'max:20',
                // Regex: chỉ cho phép số, có thể có dấu + ở đầu
                'regex:/^\+?[0-9]{8,20}$/'
            ],
            'password' => 'required|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'phone.regex' => 'Số điện thoại không hợp lệ. Chỉ được nhập số, từ 8-20 ký tự, có thể có dấu + ở đầu.',
            'email.unique' => 'Email này đã tồn tại.',
            'username.unique' => 'Tên đăng nhập này đã tồn tại.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            // Xử lý upload ảnh (nếu có)
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('user', 'public');
                $validated['avatar'] = 'storage/' . $path;
            }

            // Tạo token xác thực
            $token = Str::random(64);

            // Tạo user
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'roles' => 'user',
                'status' => 1,
                'created_by' => 0,
                'updated_by' => 0,
                'avatar' => $validated['avatar'] ?? null,
                'verification_token' => $token,
            ]);

            // Gửi email xác thực
            $verificationUrl = url('/verify-email?token=' . $user->verification_token);
            Mail::to($user->email)->send(new VerifyEmailMail($user, $verificationUrl));

            return response()->json([
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đăng ký thất bại!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng!'], 404);
        }

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email,' . $id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (auth()->check() && auth()->user()->roles === 'admin') {
            $request->validate([
                'roles' => 'required|in:user,admin',
            ]);

            $validated['roles'] = $request->roles;
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {

            if ($user->avatar) {
                Storage::disk('public')->delete(
                    str_replace('storage/', '', $user->avatar)
                );
            }

            $path = $request->file('avatar')->store('user', 'public');
            $validated['avatar'] = 'storage/' . $path;
        }

        // Password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (isset($validated['status'])) {
            $validated['status'] = (int) $validated['status'];
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Cập nhật người dùng thành công!',
            'user'    => $user->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh tài khoản thành công']);
    }
   
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Mã xác nhận không hợp lệ!'], 400);
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return response()->json(['message' => 'Email đã được xác nhận thành công!']);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $loginInput = $request->login;

        $user = User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Sai tài khoản hoặc mật khẩu'], 401);
        }

        if ($user->status == 0) {
            return response()->json(['message' => 'Tài khoản chưa kích hoạt'], 403);
        }

        // PHÁT JWT TOKEN
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->roles,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout thất bại'
            ], 500);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mật khẩu hiện tại không đúng!'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Đổi mật khẩu thành công!']);
    }
}
