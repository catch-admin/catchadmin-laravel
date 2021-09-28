<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Catcher\Support\Table\Traits;

use Closure;

trait Search
{
    /**
     * @var $search
     */
    protected $search;

    /**
     * @var array
     */
    protected $defaultQueryParams = [];

    /**
     * @var array
     */
    protected $filterParams;

    /**
     * @var Closure
     */
    protected $listCallback;

    /**
     * @var null|Closure
     */
    protected $useModel = null;

    /**
     * @var null | Closure
     */
    protected $sort = null;

    /**
     * @var null | Closure
     */
    protected $where = null;

    /**
     * @var bool
     */
    protected $dataRange = false;

    /**
     * search
     *
     * @time 2021年08月13日
     * @param array $search
     * @param Closure|null $closure
     * @return $this
     */
    public function search(array $search, Closure $closure = null): self
    {
        $this->search = $search;

        if ($closure) {
            $this->model = call_user_func($closure, $this->getModel());
        }

        return $this;
    }

    /**
     * default params
     *
     * @time 2021年08月13日
     * @param array $params
     * @return $this
     */
    public function defaultQueryParams(array $params): self
    {
        $this->defaultQueryParams = $params;

        return $this;
    }


    /**
     * filter params
     *
     * @time 2021年08月13日
     * @param array $filterParams
     * @return $this
     */
    public function filterParams(array $filterParams): self
    {
        $this->filterParams = $filterParams;

        return $this;
    }

    /**
     *
     * @time 2021年09月27日
     * @return $this
     */
    public function useDataRange(): self
    {
        $this->dataRange = true;

        return $this;
    }

    /**
     * append filter params
     *
     * @time 2021年08月13日
     * @param array $params
     * @return $this
     */
    public function appendFilterParams(array $params): self
    {
        $this->filterParams = array_merge($this->filterParams, $params);

        return $this;
    }

    /**
     * append default query params
     *
     * @time 2021年08月13日
     * @param string $param
     * @return $this
     */
    public function appendDefaultQueryParams(string $param): self
    {
        $this->defaultQueryParams[] = $param;

        return $this;
    }

    /**
     * get query params
     *
     * @time 2021年08月13日
     * @return array
     */
    public function getDefaultQueryParams(): array
    {
        return $this->defaultQueryParams;
    }

    /**
     * get filter params
     *
     * @time 2021年03月30日
     * @return array
     */
    public function getFilterParams(): array
    {
        return $this->filterParams;
    }

    /**
     * index
     *
     * @time 2021年08月13日
     * @return mixed
     */
    public function index()
    {
        // if use model method，here will return this called
        if ($this->useModel) {
            return call_user_func($this->useModel);
        }

        [$fields, $relations] = $this->getFields();

        $isTree = count($this->tree);

        $model = $this->dealToQuery($fields, $relations);
        // list
        $list = $isTree ? $model->get() : $model->paginate();

        // if relations exists
        $relationColumnPluck = [];
        if (count($relations)) {
            foreach ($relations as $relation => $columns) {
                if (is_string($columns)) {
                    $relationColumnPluck[$relation] = $columns;
                }
            }
        }

        $list->transform(function ($item) use ($relationColumnPluck){
            $item = $item->toArray();
            foreach ($relationColumnPluck as $relation => $column) {
                $item[$relation] = array_column($item[$relation], $column);
            }
            return $item;
        });

        // list callback
        if ($this->listCallback) {
            $list = call_user_func($this->listCallback, $list);
        }

        return $isTree ? $list->toTree() : $list;
    }

    /**
     * list 回调处理
     *
     * @time 2021年08月13日
     * @param Closure $closure
     * @return $this
     */
    public function dealWithList(Closure $closure): self
    {
        $this->listCallback = $closure;

        return $this;
    }

    /**
     * sort
     *
     * @time 2021年08月18日
     * @param Closure $sort
     * @return $this
     */
    public function sort(Closure $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * where
     *
     * @time 2021年08月26日
     * @param Closure $where
     * @return $this
     */
    public function where(Closure $where): self
    {
        $this->where = $where;

        return $this;
    }

    /**
     * use model
     *
     * @time 2021年08月18日
     * @param Closure $closure
     * @return $this
     */
    public function useModelCallback(Closure $closure): self
    {
        $this->useModel = $closure;

        return $this;
    }

    /**
     * deal to Query
     *
     * @time 2021年09月18日
     * @param $fields
     * @param $relations
     * @return false|mixed
     */
    protected function dealToQuery($fields, $relations)
    {
        $hasCreatorField = array_search('creator', $fields);

        $model = $this->getModel()
                      ->quickSearch()
                      ->select($fields)
                      ->when($hasCreatorField !== false, function ($query) use ($fields, $hasCreatorField){
                            unset($fields[$hasCreatorField]);

                            $userModel = app(config('auth.providers.admin_users.model'));

                            $query->select($fields)
                                ->addSelect([
                                'creator' => $userModel::whereColumn(
                                    $this->getModel()->getTable() .'.creator_id',
                                    $userModel->getTable() . '.id')
                                    ->select('username')
                                    ->take(1),
                            ]);
                      })
                      // 数据权限
                      ->when($this->dataRange, function ($query){
                          $query->datarange();
                      })
                      // where 条件查询
                      ->when($this->where, function ($query){
                          call_user_func($this->where, $query);
                      })
                      // 排序
                      ->when($this->sort, function ($query){
                          call_user_func($this->sort, $query);
                      });

        // deal with relations
        foreach ($relations as $relation => $columns) {
            if ($columns instanceof Closure) {
                $model->with([
                    $relation => $columns
                ]);
            } elseif (is_array($columns)) {
                $model->with($relation . ($columns ? ':' . implode(',', $columns) : ''));
            } else {
                $model->with(sprintf('%s:%s', $relation, $columns));
            }
        }

        return $model;
    }

    /**
     * export all
     *
     * @time 2021年09月18日
     * @return mixed
     */
    public function exportData()
    {
        [$fields, $relations] = $this->getFields();

        return $this->dealToQuery($fields, $relations)->get();
    }
}
