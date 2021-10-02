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


use Catcher\Support\Table\Actions;
use Catcher\Support\Table\HeaderItem;

trait Header
{
    /**
     * 头信息
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 设置头信息
     *
     * @time 2021年03月21日
     * @param array $items
     * @param mixed ...$hiddenHeadersProp
     * @return $this
     */
    public function headers(array $items, ...$hiddenHeadersProp): self
    {
        // 在 fpm 的情况下，无需判断 Headers 是否为空的情况
        // 但是如果使用 Octane 的情况，会重复执行，添加到数组内
        // 会导致内存泄漏
        // 这里初始化化一个空数组代替直接使用属性$headers

        $headers = [];

        foreach ($items as $item) {
            $headers[] = $item();
        }
        foreach ($hiddenHeadersProp as $prop) {
            $headers[] = $this->hidden('', $prop)();
        }

        $this->headers = $headers;

        return $this;
    }

    /**
     * table header
     *
     * @time 2021年08月18日
     * @param string $prop
     * @param string $label
     * @return HeaderItem
     */
    public function header(string $label, string $prop = ''): HeaderItem
    {
        if (! $prop) {
            return HeaderItem::label($label);
        }

        return HeaderItem::make($prop, $label);
    }

    /**
     * header hidden
     *
     * @time 2021年08月26日
     * @param string $prop
     * @param string $label
     * @return HeaderItem
     */
    public function hidden(string $label, string $prop): HeaderItem
    {
        return HeaderItem::make($prop, $label)->hidden();
    }

    /**
     * ID
     *
     * @time 2021年09月27日
     * @param false $selection
     * @param string $field
     * @return HeaderItem
     */
    public function id(bool $selection = true, string $field = ''): HeaderItem
    {
        return HeaderItem::make('id', $field)
            ->when($selection, function ($item){
                $item->selection();
            });
    }

    /**
     * created_at
     *
     * @time 2021年09月27日
     * @return HeaderItem
     */
    public function createdAt(): HeaderItem
    {
        return HeaderItem::make('created_at', '创建时间');
    }


    /**
     * updated at
     *
     * @time 2021年09月27日
     * @return HeaderItem
     */
    public function updatedAt(): HeaderItem
    {
        return HeaderItem::make('updated_at', '更新时间');
    }


    /**
     *
     * @time 2021年09月27日
     * @param string $field
     * @param false $switch
     * @return HeaderItem
     */
    public function status(bool $switch = true, string $field = 'status'): HeaderItem
    {
        return HeaderItem::make($field, '状态')
            ->when($switch, function ($headerItem){
                $headerItem->switch();
            });
    }

    /**
     *
     * @time 2021年09月27日
     * @param string $field
     * @param bool $edit
     * @return HeaderItem
     */
    public function sorts(bool $edit = false, string $field = 'sort'): HeaderItem
    {
        return HeaderItem::make($field, '排序')
            ->when($edit, function ($headerItem){
                $headerItem->editNumber();
            });
    }

    /**
     * 操作
     *
     * @time 2021年09月27日
     * @param array $actions
     * @return HeaderItem
     */
    public function operations(array $actions = []): HeaderItem
    {
        $actions = array_merge([
            Actions::update(), Actions::delete()
        ], $actions);

        return HeaderItem::make('', '操作')->actions($actions)->dontExport();
    }

    /**
     * 创建人
     *
     * @time 2021年09月27日
     * @return HeaderItem
     */
    public function creator(): HeaderItem
    {
        return HeaderItem::make('creator', '创建人');
    }
}
