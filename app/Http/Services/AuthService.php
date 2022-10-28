<?php

namespace App\Http\Services;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Services\BaseAuthService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseAuthService
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => trans('auth.failed')
            ], 401);
        }
        $scopes = ['admin'];
        $token = $user->createToken('Personal access tokens', $scopes)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::create($credentials);
        $scopes = ['user'];
        $token = $user->createToken('Personal access tokens', $scopes)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::create($credentials);
        $scopes = ['user'];
        $token = $user->createToken('Personal access tokens', $scopes)->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }
}
