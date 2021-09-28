<?php
namespace Catcher\Macros;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as LaravelBuilder;

class Builder
{
    /**
     * where like
     *
     * @time 2021年08月06日
     * @return void
     */
    public  function whereLike()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
             return $this->where($filed, 'like', "%$value%");
        });
    }

    /**
     * where left like
     *
     * @time 2021年08月13日
     * @return void
     */
    public function whereStartLike()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, 'like', "%$value");
        });
    }

    /**
     * where right like
     *
     * @time 2021年08月13日
     * @return void
     */
    public  function whereEndLike()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, 'like', "$value%");
        });
    }

    /**
     * where greater than
     *
     * @time 2021年08月06日
     * @return void
     */
    public  function whereGt()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, '>', $value);
        });
    }


    /**
     * where less than
     *
     * @time 2021年08月06日
     * @return void
     */
    public  function whereLt()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, '<', $value);
        });
    }

    /**
     * where greater than or equal
     *
     * @time 2021年08月06日
     * @return void
     */
    public  function whereGe()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, '<', $value);
        });
    }

    /**
     * where less than or equal
     *
     * @time 2021年08月06日
     * @return void
     */
    public function whereLe()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value){
            return $this->where($filed, '<', $value);
        });
    }

    /**
     * quick search
     *
     * @time 2021年08月14日
     * @return void
     */
    public function quickSearch()
    {
        LaravelBuilder::macro(__FUNCTION__, function ($params = null){
            $params = $params ? : request()->all();

            $fillAble = $this->model->getFillable();

            // filter null & empty string & limit&page
            foreach ($params as $k => $param) {
                if (is_null($param)) {
                    unset($params[$k]);
                }

                if (is_string($param) && !Str::length($param)) {
                    unset($param[$k]);
                }
            }

            // foreach params
            foreach ($params as $param => $value) {
                if (Str::contains($param, '@')) {
                    [$param, $operate] = explode('@', $param);
                    if (in_array($param, $fillAble)) {
                        // 如果是 in 操作，value 转换成 array
                        $this->{'where'.ucfirst($operate)}($param, $operate == 'in' ? explode(',', $value) : $value);
                    }
                } else {
                    if (in_array($param, $fillAble)) {
                        $this->where($param, $value);
                    }
                }
            }

            return $this;
        });
    }

    /**
     * where like
     *
     * @time 2021年08月06日
     * @return void
     */
    public  function tree()
    {
        LaravelBuilder::macro(__FUNCTION__, function (string $id, string $parentId, ...$fields){
            $fields = array_merge([$id, $parentId], $fields);

            return $this->get($fields)->toTree(0, $parentId);
        });
    }
}
