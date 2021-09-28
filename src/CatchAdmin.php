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

declare (strict_types = 1);

namespace Catcher;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CatchAdmin
{
    const VERSION = '0.1.0';

    /**
     * version
     *
     * @time 2021年08月13日
     * @return string
     */
    public static function version(): string
    {
        return static::VERSION;
    }

    /**
     * module root path
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return string
     */
    public static function moduleRootPath(): string
    {
        return config('catch.module.root') . DIRECTORY_SEPARATOR;
    }

    /**
     * make dir
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $dir
     * @return string
     */
    public static function makeDir(string $dir): string
    {
        if (! File::isDirectory($dir) && ! File::makeDirectory($dir, 0777, true)) {
            throw new \RuntimeException(sprintf('Directory %s created Failed', $dir));
        }

        return $dir;
    }

    /**
     * module dir
     *
     * @param string $module
     * @param bool $make
     * @return string
     * @author CatchAdmin
     * @time 2021年07月24日
     */
    public static function getModulePath(string $module, bool $make = true): string
    {
        if ($make) {
            return self::makeDir(self::moduleRootPath() . ucfirst($module) . DIRECTORY_SEPARATOR);
        }

        return self::moduleRootPath() . ucfirst($module) . DIRECTORY_SEPARATOR;
    }

    /**
     * module path exists
     *
     * @time 2021年07月31日
     * @param string $module
     * @return bool
     */
    public static function isModulePathExist(string $module): bool
    {
        return File::isDirectory(self::moduleRootPath() . ucfirst($module) . DIRECTORY_SEPARATOR);
    }

    /**
     * module migration dir
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $module
     * @return string
     */
    public static function getModuleMigrationPath(string $module): string
    {
        return self::getModulePath($module) . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;
    }

    /**
     * module seeder dir
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $module
     * @return string
     */
    public static function getModuleSeederPath(string $module): string
    {
        return self::getModulePath($module) . 'database'. DIRECTORY_SEPARATOR . 'seeder' . DIRECTORY_SEPARATOR;
    }

    /**
     * get modules dir
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return array
     */
    public static function getModulesPath(): array
    {
        return File::directories(self::moduleRootPath());
    }

    /**
     * get module root namespace
     *
     * @param $moduleName
     * @return string
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    public static function getModuleNamespace($moduleName): string
    {
        return config('catch.module.namespace') . '\\' . ucfirst($moduleName) . '\\';
    }

    /**
     * model namespace
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param $moduleName
     * @return string
     */
    public static function getModuleModelNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName) . 'Models\\';
    }

    /**
     * controller namespace
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param $moduleName
     * @return string
     */
    public static function getModuleControllerNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName) . 'Http\\Controllers\\';
    }

    /**
     * module model dir
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $module
     * @return string
     */
    public static function getModuleModelPath(string $module): string
    {
        return self::getModulePath($module) . 'Models' . DIRECTORY_SEPARATOR;
    }

    /**
     * module controller dir
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param string $module
     * @return string
     */
    public static function getModuleControllerPath(string $module): string
    {
        return self::getModulePath($module) . 'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;
    }

    /**
     * commands path
     *
     * @time 2021年07月29日
     * @param string $module
     * @return string
     */
    public static function getCommandsPath(string $module): string
    {
        return self::getModulePath($module) . 'Commands' . DIRECTORY_SEPARATOR;
    }

    /**
     * commands namespace
     *
     * @time 2021年07月31日
     * @param string $module
     * @return string
     */
    public static function getCommandsNamespace(string $module): string
    {
        return self::getModuleNamespace($module) . 'Commands\\';
    }

    /**
     * get build namespace
     *
     * @time 2021年08月13日
     * @param string $module
     * @return string
     */
    public static function getBuildNamespace(string $module): string
    {
        return self::getModuleNamespace($module) . 'Build\\';
    }

    /**
     * get build path
     *
     * @time 2021年08月13日
     * @param string $module
     * @return string
     */
    public static function getBuildPath(string $module): string
    {
        return self::getModulePath($module) . 'Build' . DIRECTORY_SEPARATOR;
    }

    /**
     * module route
     *
     * @time 2021年07月30日
     * @param string $module
     * @return string
     */
    public static function getModuleRoutePath(string $module): string
    {
        return self::getModulePath($module) . 'route.php';
    }

    /**
     * module route.php exists
     *
     * @time 2021年07月30日
     * @param string $module
     * @return bool
     */
    public static function isModuleRouteExists(string $module): bool
    {
        return File::exists(self::getModuleRoutePath($module));
    }

    /**
     * module views path
     *
     * @time 2021年07月31日
     * @param string $module
     * @return string
     */
    public static function getModuleViewsPath(string $module): string
    {
        return self::getModulePath($module) . 'views' . DIRECTORY_SEPARATOR;
    }

    /**
     * relative path
     *
     * @time 2021年07月31日
     * @param $path
     * @return string
     */
    public static function getModuleRelativePath($path): string
    {
        return Str::replace(dirname(config('catch.module.root')), '.', $path);
    }

    /**
     * get module json path
     *
     * @time 2021年08月06日
     * @param string $module
     * @return string
     */
    public static function getModuleJsonPath(string $module): string
    {
        return self::getModulePath($module) . 'module.json';
    }
}
