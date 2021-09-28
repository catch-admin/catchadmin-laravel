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


namespace Catcher\Support\Form\Fields\Relation;

use Catcher\Support\Form\Element\Components\Group;

class HasMany extends Group
{
    /**
     *
     * @time 2021年08月20日
     * @param string $name
     * @param string $title
     * @return HasMany
     */
    public static function make(string $name, string $title): HasMany
    {
        $hasMany = new self($name, $title);

        return $hasMany->attr('relation', 'hasMany');
    }

    /**
     * as
     *
     * @time 2021年08月11日
     * @param string $field
     * @return HasMany
     */
    public function as(string $field): HasMany
    {
        $this->attr('as', $field);

        return $this;
    }


    /**
     *  label
     * @time 2021年08月20日
     * @param $label
     * @param $value
     * @return HasMany
     */
    public function label($label, $value): HasMany
    {
        $this->attr('label', $label);

        $this->attr('value', $value);

        return $this;
    }

    /**
     * fields
     *
     * @time 2021年08月23日
     * @param $fields
     * @return $this
     */
    public function fields($fields): HasMany
    {
        if ($fields instanceof \Closure) {
            $fields = call_user_func($fields);
        }

        if (is_object($fields)) {
            $fields = $fields();
        }

        $this->rule($fields)->defaultValue($fields);

        return $this;
    }

    /**
     * default value
     *
     * @time 2021年08月23日
     * @param $fields
     * @return $this
     */
    public function defaultValue($fields): HasMany
    {
        $default = [];

        foreach ($fields as $field) {
            $default[$field->getField()] = '';
        }

        $this->default([$default]);

        return $this;
    }
}
