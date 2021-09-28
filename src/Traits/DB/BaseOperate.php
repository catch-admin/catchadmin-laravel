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

declare(strict_types=1);

namespace Catcher\Traits\DB;

use Catcher\Support\Form\CatchForm;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

trait BaseOperate
{
    /**
     *
     * @time 2021年09月27日
     * @param string[] $columns
     * @return LengthAwarePaginator
     */
    public function getList(array $columns = ['*']): LengthAwarePaginator
    {
        return self::query()->select($columns)
                    ->paginate(Request::has('limit') ?? $this->perPage);
    }

    /**
     * save
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param array | CatchForm $data
     * @return false|mixed
     */
    public function storeBy($data)
    {
        if ($data instanceof CatchForm) {
            $data = $data();
        }

        if ($this->fill($this->filterData($data))->save()) {
            return $this->getKey();
        }

        return false;
    }

    /**
     * create
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param array | CatchForm $data
     * @return false|mixed
     */
    public function createBy($data)
    {
        if ($data instanceof CatchForm) {
            $data = $data();
        }

        $model = $this->newInstance();

        if ($model->fill($this->filterData($data))->save()) {
            return $model->getKey();
        }

        return false;
    }

    /**
     * update
     *
     * @param $id
     * @param array | CatchForm $data
     * @return bool
     *@author CatchAdmin
     * @time 2021年07月24日
     */
    public function updateBy($id, $data): bool
    {
        if ($data instanceof CatchForm) {
            $data = $data();
        }

        return $this->where($this->getKeyName(), $id)->update($this->filterData($data));
    }

    /**
     * filter data/ remove null && empty string
     *
     * @author CatchAdmin
     * @time 2021年08月25日
     * @param $data
     * @return mixed
     */
    protected function filterData($data)
    {
        foreach ($data as $k => $val) {
            if (is_null($val) || (is_string($val) && ! $val)) {
                unset($data[$k]);
            }
        }

        return $data;
    }

    /**
     * get first by ID
     *
     * @param $id
     * @param string[] $columns
     * @return Builder|Model|object|null
     *@author CatchAdmin
     * @time 2021年07月24日
     */
    public function firstBy($id, array $columns = ['*'])
    {
        return self::query()->where($this->getKeyName(), $id)->first($columns);
    }

    /**
     * delete model
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param $id
     * @param bool $force
     * @return bool|mixed|null
     */
    public function deleteBy($id, bool $force = false)
    {
        $model = self::firstBy($id);

        if ($force) {
            return $model->forceDelete();
        }

        return $model->delete();
    }

    /**
     * disable or enable
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param $id
     * @param string $field
     * @return bool
     */
    public function disOrEnable($id, string $field = 'status'): bool
    {
        $model = self::firstBy($id);

        $model->{$field} = $model->{$field} == self::DISABLE ? self::ENABLE : self::DISABLE;

        return $model->save();
    }

    /**
     * alias field
     *
     * @time 2021年09月27日
     * @param $fields
     * @return mixed|string
     */
    public function aliasField($fields)
    {
        $table = $this->getTable();

        if (is_string($fields)) {
            return "{$table}.{$fields}";
        }


        foreach ($fields as &$field) {
            $field = "{$table}.{$field}";
        }

        return $fields;
    }
}
