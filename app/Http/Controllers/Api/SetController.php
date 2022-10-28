<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Services\AuthService;
use App\Http\Services\SetService;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new SetService();
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show(Request $request, $id)
    {
        return $this->service->show($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->service->destroy($request, $id);
    }
}
