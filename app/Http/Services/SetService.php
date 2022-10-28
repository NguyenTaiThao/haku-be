<?php
namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Models\Card;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetService extends BaseService
{
    public function setModel()
    {
        $this->model = new Set();
    }

    public function addFilter()
    {
        $this->query->where('creator_id', $this->request->user()->id);
    }

    public function store(Request $request, $message = '')
    {
        DB::beginTransaction();
        try {
            $setData = $request->only($this->model->getFillable());
            $setData['creator_id'] = auth()->user()->id;
            $set = parent::_insert($setData);

            $cards = $request->cards;
            if ($cards && count($cards) > 0) {
                $set->cards()->createMany($cards);
            }

            DB::commit();
            return $this->createSuccessResponse($set, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(Request $request, $id, $message = '')
    {
        DB::beginTransaction();
        try {
            $setData = $request->only($this->model->getFillable());
            $set = parent::_update($id, $setData);

            $cards = $request->cards;
            if ($cards && count($cards) > 0) {
                $models = array_map(function ($card) {
                    return new Card($card);
                }, $cards);
                $set->cards()->saveMany($models);
            }

            DB::commit();
            return $this->createSuccessResponse($set, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
