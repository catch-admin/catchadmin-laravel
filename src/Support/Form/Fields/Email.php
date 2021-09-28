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

class Email extends Input
{
    public static function make(string $name = 'email', string $title = '邮箱'): Email
    {
        $email = new self($name, $title);

        return $email->type(Input::TYPE_EMAIL)
                    ->clearable()
                    ->validate('email');
    }
}
