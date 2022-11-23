<?php

namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Models\Card;
use Illuminate\Http\Request;

class CardService extends BaseService
{
    public function setModel()
    {
        $this->model = new Card();
    }

    public function toggleRemember(Request $request, Card $card)
    {
        $card->is_remembered = !$card->is_remembered;
        $card->save();
        return $this->updateSuccessResponse($card);
    }
}
