<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class BaseAuthService
{
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true
        ]);
    }
}