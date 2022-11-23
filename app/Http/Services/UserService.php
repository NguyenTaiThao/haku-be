<?php

namespace App\Http\Services;

use App\Models\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserService
{
    public function statistics(Request $request)
    {
        $userId = $request->user()->id;
        $setNum = Set::where('creator_id', $userId)->count();
        $ongoingSetNum = Set::where('creator_id', $userId)->whereHas('cards', function (Builder $query) {
            $query->where([['is_remembered', true]]);
        })->whereHas('cards', function (Builder $query) {
            $query->where([['is_remembered', false]]);
        })->count();

        $unlearnedSetNum = Set::where('creator_id', $userId)->whereDoesntHave('cards', function (Builder $query) {
            $query->where([['is_remembered', true]]);
        })->count();

        $learnedSetNum = Set::where('creator_id', $userId)->whereDoesntHave('cards', function (Builder $query) {
            $query->where([['is_remembered', false]]);
        })->count();
        return [
            'set_num' => $setNum,
            'ongoing_set_num' => $ongoingSetNum,
            'unlearned_set_num' => $unlearnedSetNum,
            'learned_set_num' => $learnedSetNum,
        ];
    }
}
