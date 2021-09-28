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

use Catcher\Support\Form\Element\Components\Hidden as Component;

class Hidden extends Component
{
    /**
     * make
     *
     * @time 2021年08月25日
     * @param string $name
     * @param mixed $value
     * @return Hidden
     */
    public static function make(string $name, $value = ''): Hidden
    {
        return new self($name, $value);
    }
}
