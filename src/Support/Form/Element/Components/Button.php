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
 * Class Button
 *
 * @method $this size(string $size) 尺寸, 可选值: medium / small / mini
 * @method $this type(string $type) 类型, 可选值: primary / success / warning / danger / info / text
 * @method $this plain(bool $plain = true) 是否朴素按钮, 默认值: false
 * @method $this round(bool $round = true) 是否圆角按钮, 默认值: false
 * @method $this circle(bool $circle = true) 是否圆形按钮, 默认值: false
 * @method $this loading(bool $loading = true) 是否加载中状态, 默认值: false
 * @method $this disabled(bool $disabled = true) 是否禁用状态, 默认值: false
 * @method $this icon(string $icon) 图标类名
 * @method $this autofocus(bool $autofocus = true) 是否默认聚焦, 默认值: false
 * @method $this nativeType(string $nativeType) 原生 type 属性, 可选值: button / submit / reset, 默认值: button
 *
 * @method $this show(bool $show) 是否显示, 默认显示
 * @method $this innerText(string $innerText) 按钮文字提示
 */
class Button extends CustomComponent
{
    protected static $propsRule = [
        'size' => 'string',
        'type' => 'string',
        'plain' => 'bool',
        'round' => 'bool',
        'circle' => 'bool',
        'loading' => 'bool',
        'disabled' => 'bool',
        'icon' => 'string',
        'autofocus' => 'bool',
        'nativeType' => 'string',
        'innerText' => 'string',
        'show' => 'bool'
    ];
}
