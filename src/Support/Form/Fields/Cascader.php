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

namespace Catcher\Support\Form\Fields;

use Catcher\Support\Form\Element\Components\Cascader as CascaderComponent;

class Cascader extends CascaderComponent
{
    /**
     * make
     *
     * @time 2021年08月17日
     * @param string $name
     * @param string $title
     * @param null $options
     * @return Cascader
     */
    public static function make(string $name, string $title, $options = null): self
    {
        $component = new self($name, $title);

        if (! $options) {
            $component = $component->options($options);
        }

        return $component->clearable();
    }

    /**
     *
     * @time 2021年08月14日
     * @param $label
     * @param $value
     * @return $this
     */
    public function label($label, $value): self
    {
        $this->props([
            'props' => [
                'label' => $label,
                'value' => $value
            ]
        ]);

        return $this;
    }

    /**
     *
     * @time 2021年08月19日
     * @return $this
     */
    public function checkStrictly(): self
    {
        $props = $this->getProps()['props'] ?? [];

        $props['checkStrictly'] = true;

        $this->props([
            'props' => $props
        ]);

        return $this;
    }
}
