<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\CardService;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new CardService();
    }

    public function toggleRemember(Request $request, Card $card)
    {
        return $this->service->toggleRemember($request, $card);
    }
}
