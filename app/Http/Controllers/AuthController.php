<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

// use Validator;


class AuthController extends Controller
{

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {

        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'image' => 'required'
        ]);

        $image = request()->file('image');
        $imageName = 'userr' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('userr', $imageName, 'public');
        $imagePath = 'storage/userr/' . $imageName;

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->phone_number = request()->phone_number;
        $user->password = bcrypt(request()->password);
        $user->image = $imagePath;
        $user->save();

        return response()->json($user, 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->is_admin,
                'image' => $user->image
            ]
        ]);
    }

    public function updateUser(UserUpdateRequest $request)
    {
        $data = $request->validated();
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Kiểm tra nếu có ảnh mới được gửi lên
        if ($request->hasFile('image')) {
            $oldImagePath = $user->image;

            $image = $request->file('image');
            $imageName = 'userr' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'userr/' . $imageName;

            // Lưu ảnh vào thư mục storage/app/public/userr/
            Storage::disk('public')->put($imagePath, file_get_contents($image));

            // Cập nhật đường dẫn ảnh mới
            $data['image'] = 'storage/' . $imagePath;

            // Xóa ảnh cũ nếu tồn tại
            if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
            }
        }

        // Cập nhật thông tin user
        $user->fill($data)->save();

        return response()->json([
            'status' => true,
            'data' => $user,
        ]);
    }

    public function getUserDashBoard()
    {
        $countUser = User::where('is_admin', 0)->count();
        return response()->json([
            'status' => true,
            'data' => $countUser,
        ]);
    }

    public function changePasswordByEmail(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $newPassword = Str::random(10);
        $user->password = bcrypt($newPassword);
        $user->save();

        Mail::raw("Mật khẩu mới của bạn là: $newPassword", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Mật khẩu mới từ hệ thống');
        });

        return response()->json([
            'status' => true,
            'message' => 'Mật khẩu mới đã được gửi đến email.',
        ]);
    }

    public function updatePasswordManually(Request $request)
    {
        Log::info("111");
        // $request->validate([
        //     'email' => 'required|email|exists:users,email',
        //     'temporary_password' => 'required',
        //     'new_password' => 'required|confirmed|min:8',
        // ]);

        $user = User::where('email', $request->email)->first();
        Log::info($user);


        // Kiểm tra mật khẩu tạm thời
        if (!Hash::check($request->temporary_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Temporary password is incorrect.',
            ], 400);
        }
        Log::info("Temporary password is correct.");
        // Cập nhật mật khẩu mới
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password has been updated successfully.',
        ]);
    }
}
