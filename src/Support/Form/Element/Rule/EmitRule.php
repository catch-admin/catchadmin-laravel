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


trait EmitRule
{
    /**
     * 组件模式下配置使用emit方式触发的事件名
     * @var array
     */
    protected $emit = [];

    /**
     * 自定义组件emit事件的前缀
     * @var
     */
    protected $emitPrefix;

    public function emit(array $emits)
    {
        $this->emit = array_merge($this->emit, array_map('strval', $emits));

        return $this;
    }

    public function appendEmit($emit)
    {
        $this->emit[] = (string)$emit;

        return $this;
    }

    public function emitPrefix($prefix)
    {
        $this->emitPrefix = (string)$prefix;

        return $prefix;
    }

    public function getEmit()
    {
        return $this->emit;
    }

    public function getEmitPrefix()
    {
        return $this->emitPrefix;
    }

    public function parseEmitRule()
    {
        $rule = [];

        if (count($this->emit)) {
            $rule['emit'] = $this->emit;
        }

        if (!is_null($this->emitPrefix)) {
            $rule['emitPrefix'] = $this->emitPrefix;
        }

        return $rule;
    }
}
