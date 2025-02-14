<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoginGooleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();

    }
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => Str::random(12),
                    'is_admin' => 0,
                    'image' => $googleUser->getAvatar(),
                ]
            );

            $token = Auth::login($user);
            return redirect()->to("http://localhost:5173/auth/callback?access_token=$token&user=" . urlencode(json_encode($user)));
            // return $this->createNewToken($token);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Đăng nhập Google thất bại'], 500);
        }
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => Auth::user()
        ]);
    }
}
