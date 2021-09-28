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

use Catcher\Support\Form\Element\Driver\FormComponent;
use Catcher\Support\Form\Element\Rule\Validate;
use Catcher\Support\Form\Element\Rule\ValidateFactory;

/**
 * 开关组件
 * Class Switches
 *
 * @method $this disabled(bool $disabled = true) 是否禁用, 默认值: false
 * @method $this width(float $width) switch 的宽度（像素）, 默认值: 40
 * @method $this activeIconClass(string $activeIconClass) switch 打开时所显示图标的类名，设置此项会忽略 active-text
 * @method $this inactiveIconClass(string $inactiveIconClass) switch 关闭时所显示图标的类名，设置此项会忽略 inactive-text
 * @method $this activeText(string $activeText) switch 打开时的文字描述
 * @method $this inactiveText(string $inactiveText) switch 关闭时的文字描述
 * @method $this activeValue(mixed $activeValue) switch 打开时的值, 默认值: true
 * @method $this inactiveValue(mixed $inactiveValue) switch 关闭时的值, 默认值: false
 * @method $this activeColor(string $activeColor) switch 打开时的背景色, 默认值: #409EFF
 * @method $this inactiveColor(string $inactiveColor) switch 关闭时的背景色, 默认值: #C0CCDA
 * @method $this name(string $name) switch 对应的 name 属性
 * @method $this validateEvent(bool $validateEvent) 改变 switch 状态时是否触发表单的校验, 默认值: true
 */
class Switches extends FormComponent
{
    protected $selectComponent = true;

    protected $defaultProps = [
        'activeValue' => '1',
        'inactiveValue' => '0',
    ];

    protected static $propsRule = [
        'disabled' => 'bool',
        'width' => 'float',
        'activeIconClass' => 'string',
        'inactiveIconClass' => 'string',
        'activeText' => 'string',
        'inactiveText' => 'string',
        'activeValue' => '',
        'inactiveValue' => '',
        'activeColor' => 'string',
        'inactiveColor' => 'string',
        'name' => 'string',
        'validateEvent' => 'bool',
    ];

    /**
     *
     * @time 2021年08月04日
     * @return string
     */
    public function getComponentName(): string
    {
        return 'switch';
    }

    public function createValidate()
    {
        return ValidateFactory::validateStr();
    }

    public function createValidateNum(): Validate
    {
        return ValidateFactory::validateNum();
    }
}
