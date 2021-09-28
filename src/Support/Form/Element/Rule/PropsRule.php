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


trait PropsRule
{
    /**
     * 组件的props
     *
     * @var array
     */
    protected $props = [];

    /**
     * 组件普通的 HTML 特性
     *
     * @var array
     */
    protected $attrs = [];

    /**
     * 组件的样式
     * @var string|array
     */
    protected $style;

    /**
     * 组件的 DOM 属性
     *
     * @var array
     */
    protected $domProps = [];

    /**
     * 设置组件的 style 属性
     * @param string|array $style
     * @return $this
     */
    public function style($style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getStyle()
    {
        return $this->style;
    }

    public function prop($name, $value): self
    {
        $this->props[$name] = $value;

        return $this;
    }

    public function props(array $props): self
    {
        $this->props = array_merge($this->props, $props);

        return $this;
    }

    public function attr($name, $value): self
    {
        $this->attrs[$name] = $value;

        return $this;
    }

    public function attrs(array $attrs): self
    {
        $this->attrs = array_merge($this->attrs, $attrs);

        return $this;
    }

    public function domProp($name, $value): self
    {
        $this->domProps[$name] = $value;

        return $this;
    }

    public function domProps(array $domProps): self
    {
        $this->domProps = array_merge($this->domProps, $domProps);

        return $this;
    }

    public function getProps(): array
    {
        return $this->props;
    }

    public function getProp($name)
    {
        return $this->props[$name] ?? null;
    }

    public function getAttrs(): array
    {
        return $this->attrs;
    }

    public function getDomProps(): array
    {
        return $this->domProps;
    }

    public function parsePropsRule(): array
    {
        $rule = ['props' => (object)$this->props];

        if (count($this->attrs)) {
            $rule['attrs'] = $this->attrs;
        }

        if (count($this->domProps)) {
            $rule['domProps'] = $this->domProps;
        }

        if (!is_null($this->style)) {
            $rule['style'] = $this->style;
        }

        return $rule;
    }
}
