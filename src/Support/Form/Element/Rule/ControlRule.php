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

use Catcher\Support\Form\Element\Driver\CustomComponent;

trait ControlRule
{
    /**
     * 根据组件的值显示不同的组件
     *
     * @var
     */
    protected $control = [];


    /**
     * @param array $control
     * @return $this
     */
    public function control(array $control): self
    {
        $this->control = $control;

        return $this;
    }

    /**
     * @param string|int|float $value
     * @param array $rule
     * @return $this
     */
    public function appendControl($value, array $rule): self
    {
        $this->control[] = compact('value', 'rule');

        return $this;
    }


    /**
     * when
     *
     * @time 2021年09月22日
     * @param $value
     * @param array $rule
     * @return CustomComponent|ControlRule
     */
    public function when($value, array $rule)
    {
        return $this->appendControl($value, $rule);
    }

    /**
     * @param array $controls
     * @return $this
     */
    public function appendControls(array $controls): self
    {
        $this->control = array_merge($this->control, $controls);

        return $this;
    }

    /**
     *
     * @time 2021年09月22日
     * @return array
     */
    public function getControl(): array
    {
        return $this->control;
    }

    /**
     * @return array
     */
    public function parseControlRule(): array
    {
        if (!count($this->control)) {
            return [];
        }

        $control = [];
        foreach ($this->control as $child) {
            foreach ($child['rule'] as $k => $rule) {
                if (method_exists($rule, 'build')) {
                    $child['rule'][$k] = $rule->build();
                } else {
                    $child['rule'][$k] = $rule;
                }
            }
            $control[] = $child;
        }

        return compact('control');
    }
}
