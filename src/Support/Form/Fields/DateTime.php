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

class DateTime extends DatePicker
{
    /**
     * make datetime
     *
     * @time 2021年08月10日
     * @param string $name
     * @param string $title
     * @return DateTime
     */
    public static function make(string $name, string $title): DateTime
    {
        $datetime = new self($name, $title);

        return $datetime->type(DatePicker::TYPE_DATE_TIME);
    }


    /**
     * 日期范围选择
     *
     * @time 2021年08月10日
     * @return DateTime
     */
    public function range(): DateTime
    {
        return $this->type(DatePicker::TYPE_DATE_TIME_RANGE);
    }
}
