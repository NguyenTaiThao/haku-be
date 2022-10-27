<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\SetService;
use Illuminate\Http\Request;

class SetController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new SetService();
    }

    public function index(Request $req)
    {
        return $this->service->index($req);
    }

    public function store(Request $req)
    {
        return $this->service->store($req);
    }

    public function show(Request $req, $id)
    {
        return $this->service->show($req, $id);
    }

    public function update(Request $req, $id)
    {
        return $this->service->update($req, $id);
    }

    public function destroy(Request $req, $id)
    {
        return $this->service->destroy($req, $id);
    }
}
