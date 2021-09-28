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

use Catcher\Exceptions\FailedException;
use Catcher\Support\Form\Element\Components\InputNumber;

class Number extends InputNumber
{
    public static function make(string $name, string $title, $value = ''): Number
    {
        return new self($name, $title, $value);
    }

    /**
     * range
     *
     * @time 2021年08月26日
     * @param $min
     * @param $max
     * @return $this
     */
    public function range($min, $max): Number
    {
        if ($min >= $max) {
            throw new FailedException('Range [min] must less than [max]');
        }

        $this->min($min)->max($max);

        return $this;
    }
}
