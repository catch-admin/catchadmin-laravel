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

namespace Catcher\Support\Form\Element\Components;

use Catcher\Support\Form\Element\Rule\CallPropsRule;

/**
 * Class TreeData
 *
 * @method $this id(string $id) Id, 必须唯一
 * @method $this title(string $title) 标题
 * @method $this expand(bool $bool = true) 是否展开直子节点, 默认为false
 * @method $this disabled(bool $bool = true) 禁掉响应, 默认为false
 * @method $this disableCheckbox(bool $bool = true) 禁掉 checkbox
 * @method $this selected(bool $bool = true) 是否选中子节点
 * @method $this checked(bool $bool = true) 是否勾选(如果勾选，子节点也会全部勾选)
 */
class TreeData
{
    use CallPropsRule;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var array
     */
    protected $props = [];

    /**
     * @var array
     */
    protected static $propsRule = [
        'id' => 'string',
        'title' => 'string',
        'expand' => 'bool',
        'disabled' => 'bool',
        'disableCheckbox' => 'bool',
        'selected' => 'bool',
        'checked' => 'bool',
    ];

    /**
     * TreeData constructor.
     *
     * @param       $id
     * @param       $title
     * @param array $children
     */
    public function __construct($id, $title, array $children = [])
    {
        $this->props['id'] = $id;
        $this->props['title'] = $title;
        $this->children = $children;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function children(array $children)
    {
        $this->children = array_merge($this->children, $children);
        return $this;
    }

    /**
     * @param TreeData $child
     * @return $this
     */
    public function child(TreeData $child): TreeData
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return array
     */
    public function getOption(): array
    {
        $children = [];

        foreach ($this->children as $child) {
            $children[] = $child instanceof TreeData
                ? $child->getOption()
                : $child;
        }

        $this->props['children'] = $children;

        return $this->props;
    }

}
