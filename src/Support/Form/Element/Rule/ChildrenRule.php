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

trait ChildrenRule
{
    /**
     * 组件的插槽名称,如果组件是其它组件的子组件，需为插槽指定名称
     *
     * @var
     */
    protected $slot;

    /**
     * 设置父级组件的插槽,默认为default.可配合 slot 配置项使用
     *
     * @var
     */
    protected $children = [];

    /**
     * @param $slot
     * @return $this
     */
    public function slot($slot)
    {
        $this->slot = (string)$slot;

        return $this;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function children(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @param string|array|self $child
     * @return $this
     */
    public function appendChild($child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function appendChildren($children)
    {
        $this->children = array_merge($this->children, $children);

        return $this;
    }

    public function getSlot()
    {
        return $this->slot;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return array
     */
    public function parseChildrenRule()
    {
        if (!count($this->children)) {
            return [];
        }

        $children = [];

        foreach ($this->children as $child) {
            if (method_exists($child, 'build')) {
                $children[] = $child->build();
            } else {
                $children[] = $child;
            }
        }

        return compact('children');
    }
}
