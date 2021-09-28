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

trait BaseRule
{

    /**
     * 组件类型
     *
     * @var string
     */
    protected $type;

    /**
     * 组件字段名
     *
     * @var string
     */
    protected $field;

    /**
     * 字段昵称
     *
     * @var string
     */
    protected $title;

    /**
     * 组件名称
     *
     * @var string
     */
    protected $name;

    /**
     * 组件的提示信息
     *
     * @var string
     */
    protected $info;

    /**
     * 组件 class
     *
     * @var string
     */
    protected $className;

    /**
     * 是否原样生成组件,不嵌套的FormItem中
     *
     * @var bool
     */
    protected $native;

    /**
     * 事件注入时的自定义数据
     *
     * @var mixed
     */
    protected $inject;

    /**
     * 组件布局规则
     *
     * @var array
     */
    protected $col;

    /**
     * 组件的值
     *
     * @var mixed
     */
    protected $value = '';

    /**
     * 组件显示状态
     *
     * @var bool
     */
    protected $hidden;

    /**
     * 组件显示状态
     *
     * @var bool
     */
    protected $visibility;

    /**
     * @var array
     */
    protected $effect;

    /**
     * 组件显示状态
     *
     * @param bool $hidden
     * @return $this
     */
    public function hiddenStatus(bool $hidden = true): self
    {
        $this->hidden = !!$hidden;

        return $this;
    }

    /**
     * 组件显示状态
     *
     * @param bool $visibility
     * @return $this
     */
    public function visibilityStatus(bool $visibility = true): self
    {
        $this->visibility = !!$visibility;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    protected function setRuleType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function field(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function className(string $className): self
    {
        $this->className =  $className;

        return $this;
    }

    /**
     * @param bool $native
     * @return $this
     */
    public function native(bool $native): self
    {
        $this->native = !!$native;
        return $this;
    }

    /**
     * @param mixed $inject
     * @return $this
     */
    public function inject($inject): self
    {
        $this->inject = $inject;

        return $this;
    }

    /**
     * @param string $info
     * @return $this
     */
    public function info(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @param int $col
     * @return $this
     */
    public function col(int $col): self
    {
        $this->col = [
            'span' => $col
        ];

        return $this;
    }

    public function effect(array $effect): self
    {
        $this->effect = $effect;

        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function value($value)
    {
        if (is_null($value)) {
            $value = '';
        }

        $this->value = $value;

        return $this;
    }

    public function getHiddenStatus(): bool
    {
        return $this->hidden;
    }

    public function getVisibilityStatus(): bool
    {
        return $this->visibility;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function getNative(): bool
    {
        return $this->native;
    }

    public function getInject()
    {
        return $this->inject;
    }

    public function getCol(): array
    {
        return $this->col;
    }

    public function getEffect(): array
    {
        return $this->effect;
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function parseCol($col)
    {
        if (! is_object($col)) {
            return $col;
        }

        return $col->getCol();
    }

    protected function parseBaseRule(): array
    {
        $rule = [
            'type' => $this->type
        ];

        if (!is_null($this->field)) {
            $rule['field'] = $this->field;
        }

        if (!is_null($this->value)) {
            $rule['value'] = $this->value;
        }

        if (!is_null($this->title)) {
            $rule['title'] = $this->title;
        }

        if (!is_null($this->className)) {
            $rule['className'] = $this->className;
        }

        if (!is_null($this->name)) {
            $rule['name'] = $this->name;
        }

        if (!is_null($this->native)) {
            $rule['native'] = $this->native;
        }

        if (!is_null($this->info)) {
            $rule['info'] = $this->info;
        }

        if (!is_null($this->effect)) {
            $rule['effect'] = $this->effect;
        }

        if (!is_null($this->inject)) {
            $rule['inject'] = $this->inject;
        }

        if (!is_null($this->hidden)) {
            $rule['hidden'] = $this->hidden;
        }

        if (!is_null($this->visibility)) {
            $rule['visibility'] = $this->visibility;
        }

        if (!is_null($this->col)) {
            $rule['col'] = $this->parseCol($this->col);
        }

        return $rule;
    }

}
