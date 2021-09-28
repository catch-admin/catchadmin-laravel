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

use Catcher\Exceptions\FailedException;
use Catcher\Support\Form\Element\Driver\FormComponent;

/**
 * hidden组件
 * Class Hidden
 *
 */
class Hidden extends FormComponent
{
    /**
     * Hidden constructor.
     *
     * @param string $field
     * @param string $value
     */
    public function __construct(string $field, string $value)
    {
        parent::__construct($field, '', $value);
    }

    /**
     * @return array
     */
    public function getRule(): array
    {
        return [
            'type' => $this->type,
            'field' => $this->field,
            'value' => $this->value
        ];
    }

    /**
     * @return void
     * @throws FailedException
     */
    public function createValidate()
    {
        throw new FailedException('hidden 组件不支持 createValidate 方法');
    }
}
