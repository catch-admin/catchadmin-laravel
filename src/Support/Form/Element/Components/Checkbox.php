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
 * 复选框组件
 * Class Checkbox
 *
 * @method $this size(string $size) 多选框组尺寸，仅对按钮形式的 Checkbox 或带有边框的 Checkbox 有效, 可选值: medium / small / mini
 * @method $this disabled(bool $disabled = true) 是否禁用, 默认值: false
 * @method $this min(float $min) 可被勾选的 checkbox 的最小数量
 * @method $this max(float $max) 可被勾选的 checkbox 的最大数量
 * @method $this textColor(string $textColor) 按钮形式的 Checkbox 激活时的文本颜色, 默认值: #ffffff
 * @method $this fill(string $fill) 按钮形式的 Checkbox 激活时的填充色和边框色, 默认值: #409EFF
 * @method $this checked(bool $checked = true) 当前是否勾选(只有在checkbox-button时有效), 默认值: false
 */
class Checkbox extends FormOptionsComponent
{
    const TYPE_BUTTON = 'button';

    const TYPE_GROUP = 'group';

    protected $defaultValue = [];

    protected $selectComponent = true;

    protected static $propsRule = [
        'size' => 'string',
        'disabled' => 'bool',
        'min' => 'float',
        'max' => 'float',
        'textColor' => 'string',
        'fill' => 'string',
        'checked' => 'bool',
    ];

    /**
     * @param array $value
     * @return $this
     */
    public function value(array $value): Checkbox
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function button(bool $bool = true): Checkbox
    {
        if ($bool) {
            $this->props['type'] = 'button';
        } else {
            unset($this->props['type']);
        }

        return $this;
    }

    /**
     *
     * @time 2021年08月09日
     * @return Validate
     */
    public function createValidate(): Validate
    {
        return ValidateFactory::validateArr();
    }
}
