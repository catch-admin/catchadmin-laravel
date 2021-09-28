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
use Catcher\Support\Form\Element\Driver\FormComponent;
use Catcher\Support\Form\Element\Rule\ValidateFactory;

/**
 * 数组组件
 *
 * Class Group
 * @method $this disabled(bool $bool = true) 是否禁用
 */
class Group extends FormComponent
{
    protected $defaultValue = [];

    protected static $propsRule = [
        'min' => 'string',
        'max' => 'string',
        'disabled' => 'int',
    ];

    /**
     * @param array|CustomComponent $rule
     * @return $this
     */
    public function rule($rule): Group
    {
        $this->props['rule'] = $this->tidyRule([$rule])[0];

        return $this;
    }

    /**
     * @param array $rules
     * @return array
     */
    protected function tidyRule(array $rules): array
    {
        foreach ($rules as $k => $rule) {
            if (method_exists($rule, 'build')) {
                $rules[$k] = $rule->build();
            }
        }
        return $rules;
    }

    public function rules(array $rules): Group
    {
        $this->props['rules'] = $this->tidyRule($rules);

        return $this;
    }


    public function min(int $min)
    {
        $this->props['min'] = $min;

        return $this;
    }


    public function max(int $max)
    {
        $this->props['max'] = $max;

        return $this;
    }

    /**
     * @return mixed
     */
    public function createValidate()
    {
        return ValidateFactory::validateArr();
    }
}
