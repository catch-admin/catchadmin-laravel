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
use Catcher\Support\Form\Element\Rule\ValidateFactory;

/**
 * 颜色选择器组件
 * Class ColorPicker
 *
 * @method $this disabled(bool $disabled = true) 是否禁用, 默认值: false
 * @method $this size(string $size) 尺寸, 默认值: medium / small / mini
 * @method $this showAlpha(bool $showAlpha = true) 是否支持透明度选择, 默认值: false
 * @method $this colorFormat(string $colorFormat) 的格式, 可选值: hsl / hsv / hex / rgb, 默认值: hex（show-alpha 为 false）/ rgb（show-alpha 为 true）
 * @method $this popperClass(string $popperClass) ColorPicker 下拉框的类名
 * @method $this predefine(array $predefine) 预定义颜色
 */
class ColorPicker extends FormComponent
{
    protected $selectComponent = true;

    protected static $propsRule = [
        'disabled' => 'bool',
        'size' => 'string',
        'showAlpha' => 'bool',
        'colorFormat' => 'string',
        'popperClass' => 'string',
        'predefine' => 'array',
    ];

    public function createValidate()
    {
        return ValidateFactory::validateStr();
    }
}
