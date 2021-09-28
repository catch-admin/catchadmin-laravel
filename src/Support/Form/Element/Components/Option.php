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

class Option
{
    /**
     * @var array
     */
    protected $rule;

    /**
     * Option constructor.
     *
     * @param string $value
     * @param string $label
     * @param bool|null $disabled
     */
    public function __construct(string $value, string $label = '', bool $disabled = null)
    {
        $this->rule = compact('label', 'value');

        if (! is_null($disabled)) {
            $this->disabled($disabled);
        }
    }

    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled(bool $disabled = true): Option
    {
        $this->rule['disabled'] = $disabled;

        return $this;
    }

    /**
     * @return array
     */
    public function getOption(): array
    {
        return $this->rule;
    }
}
