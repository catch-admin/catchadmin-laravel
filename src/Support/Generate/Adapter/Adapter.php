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

abstract class Adapter
{
    use ParseTrait;

    protected $origin;

    /**
     * change to laravel
     *
     * @time 2021年07月27日
     * @return array
     */
    abstract public function toLaravel(): array;

    /**
     * set origin
     *
     * @time 2021年07月27日
     * @param string $origin
     * @return $this
     */
    public function setOrigin(string $origin): Adapter
    {
        $this->origin = $origin;

        return $this;
    }
}
