<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SetService;
use App\Models\Set;
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

    public function quizGame(Request $request, Set $set)
    {
        return $this->service->quizGame($request, $set);
    }
}
