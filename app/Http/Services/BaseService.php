<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

abstract class BaseService
{
    protected $model;
    protected $query;

    function __construct()
    {
        $this->request = request();
        $this->setModel();
        $this->setQuery();
    }

    abstract public function setModel();

    public function setQuery()
    {
        $this->query = $this->model->query();
    }

    public function createSuccessResponse($data = null, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function updateSuccessResponse($data = null, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function deleteSuccessResponse($data = null, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function errorResponse($message = 'System error', $code = 500)
    {
        return response()->json(['errors' => $message], $code);
    }

    public function successResponse($data = null, $message = 'ok')
    {
        return response()->json(['message' => $message, 'data' => $data]);
    }

    protected function addDefaultFilter()
    {
        $data = $this->request->all();
        $table = $this->model->getTable();
        $fields = ['*'];

        foreach ($data as $key => $value) {
            if ($value || $value === '0') {
                try {
                    if (strpos($key, ':') !== false) {
                        $field = str_replace(':', '.', $key);
                        $query = $this->query;
                        if (preg_match('/(.*)_like$/', $field, $matches)) {
                            $relations = explode('.', $matches[1]);
                            $query->whereHas($relations[0], function ($query) use ($relations, $value) {
                                $query->where($relations[1], 'like', "%$value%");
                            });
                        }

                        if (preg_match('/(.*)_equal$/', $field, $matches)) {
                            $relations = explode('.', $matches[1]);
                            $query->whereHas($relations[0], function ($query) use ($relations, $value) {
                                $query->where($relations[1], '=', $value);
                            });
                        }

                        if (preg_match('/(.*)_notEqual$/', $field, $matches)) {
                            $relations = explode('.', $matches[1]);
                            $query->whereHas($relations[0], function ($query) use ($relations, $value) {
                                $query->where($relations[1], '!=', $value);
                            });
                        }
                    } else {
                        if (preg_match('/(.*)_like$/', $key, $matches)) {
                            if (config('database.default') === 'sqlsrv') {
                                //								$value = $this->convert_vi_to_en($value);
                                $this->query->where($table . '.' . $matches[1], 'like', "%$value%");
                            } else {
                                $this->query->where($table . '.' . $matches[1], 'like', '%' . $value . '%');
                            }
                        }
                        if (preg_match('/(.*)_equal$/', $key, $matches)) {
                            $value = explode(',', $value);
                            if (sizeof($value) === 1) {
                                $this->query->where($table . '.' . $matches[1], $value);
                            } else {
                                $this->query->whereIn($table . '.' . $matches[1], $value);
                            }
                        }

                        if (preg_match('/(.*)_notEqual$/', $key, $matches)) {
                            $value = explode(',', $value);
                            if (sizeof($value) === 1) {
                                $this->query->where($table . '.' . $matches[1], "!=", $value);
                            } else {
                                $this->query->whereNotIn($table . '.' . $matches[1], $value);
                            }
                        }

                        if (preg_match('/(.*)_between$/', $key, $matches)) {
                            $this->query->whereBetween($table . '.' . $matches[1], $value);
                        }
                        if (preg_match('/(.*)_isnull$/', $key, $matches)) {
                            if ($value == 1) {
                                $this->query->whereNull($table . '.' . $matches[1]);
                            }
                            if ($value == 0) {
                                $this->query->whereNotNull($table . '.' . $matches[1]);
                            }
                        }
                    }
                    if (preg_match('/^has_(.*)/', $key, $matches)) {
                        if ($value) {
                            $this->query->whereHas($matches[1]);
                        } else {
                            $this->query->whereDoesntHave($matches[1]);
                        }
                    }
                    if ($key == 'only_trashed' && $value) {
                        $this->query->onlyTrashed();
                    }
                    if ($key == 'with_trashed' && $value) {
                        $this->query->withTrashed();
                    }

                    if ($key == 'select' && $value) {
                        $this->query->select($value);
                    }

                    if ($key == 'sort' && $value) {
                        $sorts = explode(',', $value);
                        $this->query->getQuery()->orders = null;
                        foreach ($sorts as $sort) {
                            $sortParams = explode('|', $sort);
                            if (strpos($sortParams[0], '.') !== false) {
                                $this->query->orderByJoin($sortParams[0], isset($sortParams[1]) ? $sortParams[1] : 'asc');
                            } else {
                                $this->query->orderBy($table . '.' . $sortParams[0], isset($sortParams[1]) ? $sortParams[1] : 'asc');
                            }
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        if ($this->request->get_all) {
            return $this->query->get();
        }
        return $this->query->paginate($this->request->per_page ?? 50, $fields);
    }

    public function index(Request $request)
    {
        if (method_exists($this, 'addFilter')) {
            $this->addFilter();
        }
        $data = $this->addDefaultFilter();

        return $data;
    }

    public function show(Request $request, $id)
    {
        if (method_exists($this, 'addAppend')) {
            $this->addAppend();
        }
        $data = $this->query->findOrFail($id);
        return $data;
    }

    /**
     * Store the specified resource in storage.
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request, $message = '')
    {
        $data = $request->only($this->model->getFillable());
        $this->_insert($data);
        return $this->createSuccessResponse($this->model, $message);
    }

    public function _insert($data)
    {
        $this->model->fill($data);
        $this->model->save();
        return $this->model;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id, $message = '')
    {
        $data = $request->all();
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $item = $this->query->where('id', $id)->first();
        if (!$item) {
            return response()->json(null, 404);
        }
        $item->fill($data);
        $result = $item->update();
        return $this->updateSuccessResponse($item, $message);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $item = $this->query->findOrFail($id);
        $result = $item->delete();
        return $this->deleteSuccessResponse($result, 'Delete successfully');
    }
}