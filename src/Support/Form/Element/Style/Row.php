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

namespace Catcher\Support\Form\Element\Style;

/**
 * row栅格规则
 *
 * Class Row
 */
class Row
{
    protected $rule;

    public function __construct(array $rule = [])
    {
        $this->rule = $rule;
    }

    /**
     * 栅格间距，单位 px，左右平分
     *
     * @param int $gutter
     */
    public function gutter(int $gutter)
    {
        $this->rule['gutter'] = (float)$gutter;
    }

    /**
     * 布局模式，可选值为flex或不选，在现代浏览器下有效
     *
     * @param string $type
     */
    public function type(string $type)
    {
        $this->rule['type'] = $type;
    }

    /**
     * flex 布局下的垂直对齐方式，可选值为top、middle、bottom
     *
     * @param string $align
     */
    public function align(string $align)
    {
        $this->rule['align'] = $align;
    }

    /**
     * flex 布局下的水平排列方式，可选值为start、end、center、space-around、space-between
     *
     * @param string $justify
     */
    public function justify(string $justify)
    {
        $this->rule['justify'] = $justify;
    }

    /**
     * 自定义元素标签
     *
     * @param string $tag
     */
    public function tag(string $tag)
    {
        $this->rule['tag'] = $tag;
    }

    /**
     * @return object
     */
    public function getStyle(): object
    {
        return (object)$this->rule;
    }

}
