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

namespace Catcher\Support;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use ReflectionClass;

class Utils
{

    /**
     *
     * @time 2021年07月30日
     * @param $paths
     * @param $namespace
     * @param null $searchPath
     * @return void
     * @throws \ReflectionException
     */
    public static function loadCommands($paths, $namespace, $searchPath = null)
    {
        if (! $searchPath) {
            $searchPath = dirname($paths) . DIRECTORY_SEPARATOR;
        }

        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        foreach ((new Finder)->in($paths)->files() as $command) {
            $command = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($command->getRealPath(), $searchPath)
                );

            if (is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }

    /**
     * table name with prefix
     *
     * @author CatchAdmin
     * @time 2021年08月10日
     * @param string $table
     * @return string
     */
    public static function withTablePrefix(string $table): string
    {
        return DB::connection()->getTablePrefix() . $table;
    }
}
