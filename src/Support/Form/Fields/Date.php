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

use Catcher\Support\Form\Element\Components\DatePicker;

class Date extends DatePicker
{
    /**
     * make date
     *
     * @time 2021年08月10日
     * @param string $name
     * @param string $title
     * @return Date
     */
    public static function make(string $name, string $title): Date
    {
        $date = new self($name, $title);

        return $date->type(DatePicker::TYPE_DATE);
    }


    /**
     * date range
     *
     * @time 2021年08月10日
     * @return Date
     */
    public function range(): Date
    {
        return $this->type(DatePicker::TYPE_DATE_RANGE);
    }
}
