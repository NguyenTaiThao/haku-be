<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function statistics(Request $request)
    {
        return $this->service->statistics($request);
    }
}
