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

namespace Catcher\Support\Table;

use Catcher\Support\Table\Traits\Components;

/**
 *
 * @time 2021年08月12日
 */
class HeaderItem
{
    use Components;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * HeaderItem constructor.
     * @param string $label
     */
    public function __construct(string $label)
    {
        $this->attributes['label'] = $label;

        $this->attributes['show'] = true;

        return $this;
    }

    /**
     * make
     *
     * @time 2021年08月13日
     * @param string $prop
     * @param string $label
     * @return HeaderItem|static
     */
    public static function make(string $prop, string $label = ''): HeaderItem
    {
        return static::label($label)->prop($prop);
    }

    /**
     * label
     *
     * @time 2021年05月15日
     * @param string $label
     * @return HeaderItem
     */
    public static function label(string $label = ''): HeaderItem
    {
        return new self($label);
    }

    /**
     * prop
     *
     * @time 2021年05月15日
     * @param string $prop
     * @return $this
     */
    public function prop(string $prop): HeaderItem
    {
        $this->attributes['prop'] = $prop;

        return $this;
    }

    /**
     * as field
     *
     * @time 2021年08月13日
     * @param string $as
     * @return $this
     */
    public function as(string $as): HeaderItem
    {
        $this->attributes['as'] = $as;

        return $this;
    }

    /**
     * 宽度
     *
     * @time 2021年05月15日
     * @param string $width
     * @return $this
     */
    public function width(string $width): HeaderItem
    {
        $this->attributes['width'] = $width;

        return $this;
    }

    /**
     * align 宽度
     *
     * @time 2021年05月15日
     * @param string $align
     * @return $this
     */
    public function align(string $align): HeaderItem
    {
        $this->attributes['align'] = $align;

        return $this;
    }

    /**
     * 居中
     *
     * @time 2021年05月15日
     * @return $this
     */
    public function alignCenter(): HeaderItem
    {
        $this->attributes['align'] = 'center';

        return $this;
    }

    /**
     * 居右
     *
     * @time 2021年05月15日
     * @return $this
     */
    public function alignRight(): HeaderItem
    {
        $this->attributes['align'] = 'right';

        return $this;
    }

    /**
     * 操作
     *
     * @time 2021年05月15日
     * @param array $actions
     * @return $this
     */
    public function actions(array $actions): HeaderItem
    {
        foreach ($actions as $action) {
            $this->attributes['action'][] = $action();
        }

        return $this;
    }

    /**
     * 冒泡点击
     *
     * @time 2021年05月15日
     * @param false $bubble
     * @return $this
     */
    public function isBubble(bool $bubble = false): HeaderItem
    {
        $this->attributes['isBubble'] = $bubble;

        return $this;
    }

    /**
     * 可排序
     *
     * @param string $type // 'sortable' || 'custom'
     * @time 2021年03月31日
     * @return $this
     */
    public function sortable(string $type = 'sortable'): HeaderItem
    {
        $this->attributes[$type] = true;

        return $this;
    }

    /**
     * selection
     *
     * @time 2021年03月29日
     * @return mixed
     */
    public function selection()
    {
        return $this->width(50)->type('selection');
    }

    /**
     * 展开行
     *
     * @time 2021年05月07日
     * @return mixed
     */
    public function expand()
    {
        return $this->type('expand');
    }


    /**
     * export
     *
     * @time 2021年09月18日
     * @param \Closure $callable
     * @return $this
     */
    public function export(\Closure $callable): HeaderItem
    {
        $this->attributes['export'] = $callable;

        return $this;
    }


    /**
     * 固定列
     *
     * @time 2021年05月07日
     * @param bool|string $fixed
     * @return HeaderItem
     */
    public function fixed($fixed = true): HeaderItem
    {
        $this->attributes['fixed'] = $fixed;

        return $this;
    }

    /**
     * 隐藏
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function hidden(): HeaderItem
    {
        $this->attributes['show'] = false;

        return $this;
    }

    /**
     * dont export
     *
     * @time 2021年04月22日
     * @return $this
     */
    public function dontExport(): HeaderItem
    {
        $this->attributes['export'] = false;

        return $this;
    }

    /**
     * dont import
     *
     * @time 2021年04月22日
     * @return $this
     */
    public function dontImport(): HeaderItem
    {
        $this->attributes['import'] = false;

        return $this;
    }

    /**
     * get attribute
     *
     * @time 2021年08月13日
     * @return array
     */
    public function __invoke(): array
    {
        // TODO: Implement __toString() method.
        return $this->attributes;
    }

    /**
     * 关联关系
     *
     * @time 2021年08月13日
     * @param array|\Closure $fields
     * @return $this
     */
    public function relations($fields = null): HeaderItem
    {
        $this->attributes['relations'] = $fields;

        return $this;
    }

    /**
     * ignore fields
     *
     * @time 2021年08月26日
     * @return $this
     */
    public function ignore(): HeaderItem
    {
        $this->attributes['ignore'] = true;

        return $this;
    }

    /**
     * 动态访问
     *
     * @time 2021年03月24日
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params): HeaderItem
    {
        $this->attributes[$method] = $params[0];

        return $this;
    }

    public function __get($key)
    {
        return $this->{$key};
    }

    /**
     * when
     *
     * @time 2021年09月27日
     * @param $condition
     * @param $callback
     * @return $this
     */
    public function when($condition, $callback): HeaderItem
    {
        if ($condition) {
            $callback($this);
        }

        return $this;
    }
}
