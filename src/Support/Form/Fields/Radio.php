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

class Radio extends \Catcher\Support\Form\Element\Components\Radio
{
    /**
     * radio make
     *
     * @time 2021年08月26日
     * @param string $name
     * @param string $title
     * @return Radio
     */
    public static function make(string $name, string $title): Radio
    {
        return new self($name, $title);
    }

    /**
     * button style
     *
     * @time 2021年08月26日
     * @return $this
     */
    public function asButton(): Radio
    {
        $this->button();

        return $this;
    }
}
