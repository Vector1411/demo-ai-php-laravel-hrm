<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\RevokedToken;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Số lần sai tối đa trước khi lockout
    protected $maxAttempts = 5;
    protected $lockoutMinutes = 15;

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $username = $request->username;
        $ip = $request->ip();
        $cacheKey = "login_attempts:{$username}:{$ip}";

        if (Cache::has("lockout:{$username}:{$ip}")) {
            return response()->json(['message' => 'Account locked. Try again later.'], 401);
        }

        $user = User::where('username', $username)->first();
        if (!$user || !Hash::check($request->password, $user->password) || !$user->is_active) {
            $attempts = Cache::increment($cacheKey);
            if ($attempts == 1) {
                Cache::put($cacheKey, 1, $this->lockoutMinutes * 60);
            }
            if ($attempts >= $this->maxAttempts) {
                Cache::put("lockout:{$username}:{$ip}", true, $this->lockoutMinutes * 60);
            }
            return response()->json(['message' => 'Invalid credentials or account locked'], 401);
        }

        Cache::forget($cacheKey);
        Cache::forget("lockout:{$username}:{$ip}");

        // Issue JWT (giả lập, cần dùng package JWT thực tế)
        $accessToken = Str::random(32);
        $refreshToken = Str::random(64);
        $expiresIn = config('jwt.ttl', 15) * 60;

        // Gắn jti vào refresh token để revoke
        // Lưu ý: thực tế dùng JWT package sẽ sinh jti và lưu vào DB
        // RevokedToken::create(['jti' => $refreshJti, 'revoked_at' => now()]);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);
        $refreshToken = $request->refresh_token;

        // Giả lập kiểm tra revoke
        $revoked = RevokedToken::where('jti', $refreshToken)->exists();
        if ($revoked) {
            return response()->json(['message' => 'Refresh token revoked'], 401);
        }

        // Issue new access token
        $accessToken = Str::random(32);
        $expiresIn = config('jwt.ttl', 15) * 60;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in' => $expiresIn
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8'
        ]);
        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Old password incorrect'], 400);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Revoke all tokens (giả lập)
        // RevokedToken::create(['jti' => $oldJti, 'revoked_at' => now()]);

        return response()->json(['message' => 'Password changed']);
    }
}
