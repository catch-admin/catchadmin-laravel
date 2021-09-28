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

use Catcher\Support\Form\Element\Components\Input;

class Text extends Input
{
    /**
     * make
     *
     * @time 2021年08月26日
     * @param string $name
     * @param string $title
     * @return Text
     */
    public static function make(string $name, string $title): Text
    {
        $text = new self($name, $title);

        return $text->type(Input::TYPE_TEXT)->clearable();
    }
}
