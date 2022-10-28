<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login(LoginRequest $request)
    {
        return $this->service->login($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->service->register($request);
    }

    public function logout(Request $request)
    {
        return $this->service->logout($request);
    }

    public function me(Request $request)
    {
        return $this->service->me($request);
    }
}
