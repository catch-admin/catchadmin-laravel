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

use Catcher\Support\Form\Element\Driver\FormOptionsComponent;
use Catcher\Support\Form\Element\Rule\Validate;
use Catcher\Support\Form\Element\Rule\ValidateFactory;

/**
 * 单选框组件
 * Class Radio
 *
 * @method $this size(string $size) 单选框组尺寸，仅对按钮形式的 Radio 或带有边框的 Radio 有效, 可选值: medium / small / mini
 * @method $this disabled(bool $disabled = true) 是否禁用, 默认值: false
 * @method $this textColor(string $textColor) 按钮形式的 Radio 激活时的文本颜色, 默认值: #ffffff
 * @method $this fill(string $fill) 按钮形式的 Radio 激活时的填充色和边框色, 默认值: #409EFF
 */
class Radio extends FormOptionsComponent
{
    protected $selectComponent = true;

    protected static $propsRule = [
        'size' => 'string',
        'disabled' => 'bool',
        'textColor' => 'string',
        'fill' => 'string',
    ];

    public function createValidate(): Validate
    {
        return ValidateFactory::validateStr();
    }

    public function createValidateNum(): Validate
    {
        return ValidateFactory::validateNum();
    }

    public function requiredNum($message = ''): Radio
    {
        if (is_null($message)) {
            $message = $this->getPlaceHolder();
        }

        return $this->appendValidate($this->createValidateNum()->message($message)->required());
    }

    /**
     * 按钮样式
     *
     * @param bool $button
     * @return $this
     */
    public function button(bool $button = true): Radio
    {
        if ($button) {
            $this->props['type'] = 'button';
        } else {
            unset($this->props['type']);
        }

        return $this;
    }
}
