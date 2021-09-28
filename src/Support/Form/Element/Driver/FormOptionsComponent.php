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

namespace Catcher\Support\Form\Element\Driver;


use Catcher\Support\Form\Element\Rule\OptionsRule;

abstract class FormOptionsComponent extends FormComponent
{
    use OptionsRule;

    public function getRule(): array
    {
        $rule = parent::getRule();

        return array_merge($rule, $this->parseOptionsRule());
    }
}
