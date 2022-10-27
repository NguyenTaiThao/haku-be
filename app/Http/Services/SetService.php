<?php

namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Models\Set;

class SetService extends BaseService
{
    public function setModel()
    {
        $this->model = new Set();
    }
}
