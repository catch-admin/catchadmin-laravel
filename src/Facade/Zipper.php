<?php
declare(strict_types=1);

namespace Catcher\Facade;

use Catcher\Support\ModuleRepository;
use Illuminate\Support\Facades\Facade;
use Catcher\Support\Zip\Zipper as Zip;

/**
 * @method static Zip make(string $pathToFile)
 * @method static Zip zip(string $pathToFile)
 * @method static Zip phar(string $pathToFile)
 *
 * @see ModuleRepository
 * Class Module
 * @package Catcher\Facade
 * @author CatchAdmin
 * @time 2021年07月24日
 */
class Zipper extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \Catcher\Support\Zip\Zipper::class;
    }
}
