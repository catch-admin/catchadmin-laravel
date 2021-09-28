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


use Catcher\Support\Form\Element\Driver\CustomComponent;

/**
 * Class Tooltip
 *
 * @method $this effect(string $effect) 默认提供的主题, 可选值: dark/light, 默认值: dark
 * @method $this placement(string $placement) Tooltip 的出现位置, 可选值: top/top-start/top-end/bottom/bottom-start/bottom-end/left/left-start/left-end/right/right-start/right-end, 默认值: bottom
 * @method $this disabled(bool $disabled = true) Tooltip 是否可用, 默认值: false
 * @method $this offset(float $offset) 出现位置的偏移量
 * @method $this transition(string $transition) 定义渐变动画, 默认值: el-fade-in-linear
 * @method $this visibleArrow(bool $visibleArrow = true) 是否显示 Tooltip 箭头，更多参数可见Vue-popper, 默认值: true
 * @method $this popperOptions(array $popperOptions) popper.js 的参数, 可选值: 参考 popper.js 文档, 默认值: {boundariesElement: 'body', gpuAcceleration: false}
 * @method $this openDelay(float $openDelay) 延迟出现，单位毫秒
 * @method $this manual(bool $manual = true) 手动控制模式，设置为 true 后，mouseenter 和 mouseleave 事件将不会生效, 默认值: false
 * @method $this popperClass(string $popperClass) 为 Tooltip 的 popper 添加类名
 * @method $this enterable(bool $enterable = true) 鼠标是否可进入到 tooltip 中, 默认值: true
 * @method $this hideAfter(float $hideAfter) Tooltip 出现后自动隐藏延时，单位毫秒，为 0 则不会自动隐藏
 * @method $this tabindex(float $tabindex) Tooltip 组件的 tabindex
 */
class Tooltip extends CustomComponent
{
    protected static $propsRule = [
        'effect' => 'string',
        'content' => 'string',
        'placement' => 'string',
        'value / vModel' => 'bool',
        'disabled' => 'bool',
        'offset' => 'float',
        'transition' => 'string',
        'visibleArrow' => 'bool',
        'popperOptions' => 'array',
        'openDelay' => 'float',
        'manual' => 'bool',
        'popperClass' => 'string',
        'enterable' => 'bool',
        'hideAfter' => 'float',
        'tabindex' => 'float',
    ];
}
