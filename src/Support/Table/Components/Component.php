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

namespace Catcher\Support\Table\Components;

class Component
{
    /**
     * @var array
     */
    protected $attributes = [];

    protected $el;

    public function __construct()
    {
        $this->attributes['el'] = $this->el;
    }

    /**
     * 魔术方法
     *
     * @time 2021年03月21日
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params): Component
    {
        $this->attributes[$method] = $params[0];

        return $this;
    }

    /**
     * 输出
     *
     * @time 2021年03月23日
     * @return array
     */
    public function __invoke(): array
    {
        return $this->attributes;
    }
}
