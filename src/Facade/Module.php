<?php
declare(strict_types=1);

namespace Catcher\Facade;

use Catcher\Support\ModuleRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static all()
 * @method static create(array $module)
 * @method static update(array $module)
 * @method static delete(string $name)
 * @method static disOrEnable(string $name)
 *
 * @see ModuleRepository
 * Class Module
 * @package Catcher\Facade
 * @author CatchAdmin
 * @time 2021年07月24日
 */
class Module extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'catchModuleRepository';
    }
}
