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

class Button extends Component
{
    protected $el = 'button';

    /**
     * icon
     *
     * @time 2021年05月07日
     * @param string $icon
     * @return $this
     */
    public function icon(string $icon): Button
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    /**
     * 文字
     *
     * @time 2021年05月07日
     * @param string $text
     * @return $this
     */
    public function text(string $text): Button
    {
        $this->attributes['label'] = $text;

        return $this;
    }

    /**
     * 样式
     *
     * @time 2021年05月07日
     * @param string $style
     * @return $this
     */
    public function style(string $style): Button
    {
        $this->attributes['class'] = $style;

        return $this;
    }

    /**
     * 点击事件
     *
     * @time 2021年05月07日
     * @param string $click
     * @return $this
     */
    public function click(string $click): Button
    {
        $this->attributes['click'] = $click;

        return $this;
    }

    /**
     * 权限 action 指令
     *
     * @time 2021年05月07日
     * @param string $permission
     * @return $this
     */
    public function permission(string $permission): Button
    {
        $this->attributes['permission'] = $permission;

        return $this;
    }

    /**
     * 支持路由跳转
     *
     * @time 2021年04月28日
     * @param string $route
     * @return $this
     */
    public function to(string $route): Button
    {
        $this->attributes['route'] = $route;

        return $this;
    }
}
