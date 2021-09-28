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

namespace Catcher\Support\Generate\Create;


abstract class Creator
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $module;


    /**
     *
     * @param string $name
     * @return mixed
     * @author CatchAdmin
     * @time 2021年07月26日
     */
    abstract public function generate(string $name);

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月26日
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }


    /**
     *
     * @author CatchAdmin
     * @time 2021年07月26日
     * @param string $moduleName
     * @return $this
     */
    public function setModule(string $moduleName): self
    {
        $this->module = $moduleName;

        return $this;
    }
}
