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

namespace Catcher\Support\Form\Fields\Traits;


use Catcher\Support\Form\Fields\Relation\BelongsTo;
use Catcher\Support\Form\Fields\Relation\BelongsToMany;
use Catcher\Support\Form\Fields\Relation\BelongsToManyTree;
use Catcher\Support\Form\Fields\Relation\HasMany;
use Catcher\Support\Form\Fields\Relation\HasOne;
use Illuminate\Contracts\Container\BindingResolutionException;

trait RelationsTrait
{
    protected $attrs;

    /**
     *
     * @time 2021年09月26日
     * @param $field
     * @throws BindingResolutionException
     * @return mixed
     */
    public function parseRelate($field)
    {
        $this->attrs = $field->getAttrs();

        if ($field instanceof HasOne) {
            return $this->hasOne($field);
        } elseif ($field instanceof HasMany) {
            return $this->hasMany($field);
        } elseif ($field instanceof BelongsTo) {
            return $this->belongsTo($field);
        } elseif ($field instanceof BelongsToMany) {
            return $this->belongsToMany($field);
        } elseif ($field instanceof BelongsToManyTree){
            return $this->belongsToManyTree($field);
        } else {
            return $field;
        }

    }

    /**
     * relate has many
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    public function hasMany($field)
    {
        $field->options($this->getRelateData($field));

        return $this->asSet($field);
    }

    /**
     * relate has one
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    public function hasOne($field)
    {
        $field->options($this->getRelateData($field));

        return $this->asSet($field);
    }

    /**
     * relate belongs to
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    public function belongsTo($field)
    {
        $field->options($this->getRelateData($field));

        return $this->asSet($field);
    }

    /**
     * relate belongs to
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    public function belongsToMany($field)
    {
        $field->options($this->getRelateData($field));

        return $this->asSet($field);
    }


    /**
     * relate belongs to
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    public function belongsToManyTree($field)
    {
        if (! count($this->attrs)) {
            $this->attrs = $field->getProps()['props'];
        }

        $field->data($this->getRelateData($field));

        return $this->asSet($field);
    }

    /**
     * set as
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     */
    protected function asSet($field)
    {
        $as = $this->attrs['as'] ?? null;

        if ($as) {
            $field->attr('as', $field->getField());

            return $field->field($as);
        }

        return $field;
    }

    /**
     * get relate data
     *
     * @time 2021年08月20日
     * @param $field
     * @return mixed
     * @throws BindingResolutionException
     */
    protected function getRelateData($field)
    {
        // $relation = $this->attrs['as'] ?? $field->getField();

        $relateModel = $this->getModel()->{ $field->getField() }()->getRelated();

        return $relateModel->when($this->attrs['label'] ?? false, function ($query) use ($field){

            $fields = [$this->attrs['label'], $this->attrs['value']];

            if ($field instanceof BelongsToManyTree) {
                $fields[] = $field->getParentId();
            }

            $query->select($fields);
        })->get();
    }
}
