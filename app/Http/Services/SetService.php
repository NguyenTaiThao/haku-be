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

            $newCards = array_filter($cards, function ($card) {
                return !isset($card['id']);
            });

            $oldCards = array_filter($cards, function ($card) {
                return isset($card['id']);
            });

            if ($oldCards && count($oldCards) > 0) {
                foreach ($oldCards as $card) {
                    $cardModel = Card::find($card['id']);
                    $cardModel->update($card);
                }
                Card::where('set_id', $id)->whereNotIn('id', array_column($oldCards, 'id'))->delete();
            }

            if ($newCards && count($newCards) > 0) {
                $models = array_map(function ($newCards) {
                    return new Card($newCards);
                }, $newCards);
                $set->cards()->saveMany($models);
            }


            DB::commit();
            return $this->createSuccessResponse($set, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function quizGame(Request $request, Set $set)
    {
        $questionNum = $request->question_num;

        $cards = $set->cards()->get();

        $fronts = $cards->pluck('front_content')->toArray();
        $backs = $cards->pluck('back_content')->toArray();

        $front2BackQuestions = $this->genQuiz($fronts, $backs, $questionNum);
        $back2FrontQuestions = $this->genQuiz($backs, $fronts, $questionNum);

        $questions = array_merge($front2BackQuestions, $back2FrontQuestions);

        $questions = $this->shuffleAndSlice($questions, $questionNum);

        return $this->createSuccessResponse($questions);
    }

    private function shuffleAndSlice($array, $num)
    {
        shuffle($array);
        $slicedArr = array_slice($array, 0, $num);
        return $slicedArr;
    }

    private function genQuiz($questions, $answers)
    {
        $quizzes = [];

        for ($i = 0; $i < count($questions); $i++) {
            $quizQuestion = $questions[$i];

            $filteredBacks = array_filter($answers, function ($key) use ($i) {
                return $key != $i;
            }, ARRAY_FILTER_USE_KEY);

            $quizAnswers = $this->getUniqueRandom($filteredBacks, 3);

            $correctIndex = rand(0, 3);

            if ($correctIndex == 3) {
                $quizAnswers[] = $answers[$i];
            } else {
                $quizAnswers[] = $quizAnswers[$correctIndex];
                $quizAnswers[$correctIndex] = $answers[$i];
            }

            $quizzes[] = [
                'question' => $quizQuestion,
                'answers' => $quizAnswers,
                'correct_index' => $correctIndex
            ];
        }

        return $quizzes;
    }

    private function getUniqueRandom($arr, $n)
    {
        $indexes = [];
        do {
            $indexes = array_rand($arr, $n);
            $indexes = array_unique($indexes);
        } while (count($indexes) < $n);

        $resultCards = array_map(function ($index) use ($arr) {
            return $arr[$index];
        }, $indexes);

        $results = array_map(function ($resultCard) {
            return $resultCard;
        }, $resultCards);
        return $results;
    }
}
