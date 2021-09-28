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

use Catcher\Support\Form\Element\Components\Switches;

class Boolean extends Switches
{
    /**
     *
     * @time 2021年09月28日
     * @param string $name
     * @param string $title
     * @return Switches
     */
    public static function make(string $name, string $title): Switches
    {
        $self = new self($name, $title);

        return $self->true('')->false('');
    }

    /**
     *
     * @time 2021年08月04日
     * @param string $text
     * @param int $value
     * @return $this
     */
    public function true(string $text = '开启', int $value = 1): self
    {
        $this->activeText($text)->activeValue($value);

        return $this;
    }

    /**
     *
     * @time 2021年08月04日
     * @param string $text
     * @param int $value
     * @return $this
     */
    public function false(string $text = '关闭', int $value = 2): self
    {
        $this->inactiveText($text)->inactiveValue($value);

        return $this;
    }

}
