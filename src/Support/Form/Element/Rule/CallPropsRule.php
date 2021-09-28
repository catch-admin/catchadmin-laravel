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

namespace Catcher\Support\Form\Element\Rule;


use Catcher\Exceptions\FailedException;

trait CallPropsRule
{
    /**
     * 设置组件属性
     *
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (isset(static::$propsRule[$name])) {
            // 如果是 bool 类型，则默认为 true
            if (static::$propsRule[$name] === 'bool') {
                if (! isset($arguments[0])) {
                    $arguments[0] = true;
                }
            }

            if (!isset($arguments[0])) {
                return $this->props[$name] ?? null;
            }

            $value = $arguments[0];
            if (is_array(static::$propsRule[$name])) {
                settype($value, static::$propsRule[$name][0]);
                $name = static::$propsRule[$name][1];
            } else if (static::$propsRule[$name]) {
                settype($value, static::$propsRule[$name]);
            }

            $this->props[$name] = $value;

            return $this;
        } else {
            throw new FailedException($name . '方法不存在');
        }
    }
}
