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

namespace Catcher\Macros;

use ReflectionClass;

/**
 * Class Macros
 * @package Catcher\Macros
 * @author CatchAdmin
 * @time 2021年07月25日
 */
class Macros
{
    protected static $macrosClasses = [
        Builder::class,
        Collection::class,
        Blueprint::class,
    ];

    /**
     * register
     *
     * @return void
     * @throws \ReflectionException
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    public static function register()
    {
        foreach (static::$macrosClasses as $macrosClass) {
            $macrosClass = new $macrosClass;

            $reflectClass = new ReflectionClass($macrosClass);

            $methods = $reflectClass->getMethods();

            foreach ($methods as $method) {
                if ($method->isPublic()) {
                    $macrosClass->{$method->getName()}();
                }
            }
        }
    }
}
