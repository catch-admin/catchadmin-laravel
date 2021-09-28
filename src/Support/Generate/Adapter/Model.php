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

declare(strict_types=1);

namespace Catcher\Support\Generate\Adapter;

class Model extends Adapter
{
    public function toLaravel(): array
    {
        $this->origin = explode('\\', $this->origin);

        return [
            $this->getNamespace() . '\\' .

            $this->getModule() . '\\Models',

            $this->getSelfName()
        ];
    }
}
