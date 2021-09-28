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

namespace Catcher\Providers;

use Catcher\Commands\Migrate\MigrateRun;
use Catcher\Commands\Migrate\SeedRun;
use Catcher\Contracts\ModuleRepositoryInterface;
use Catcher\Events\ModuleEvent;
use Catcher\Exceptions\Handler;
use Catcher\Facade\Module;
use Catcher\Listeners\ModuleListener;
use Catcher\Macros\Macros;
use Catcher\Middleware\JsonResponse;
use Catcher\Support\DB\Query;
use Catcher\Support\DB\SoftDelete;
use Catcher\Support\ModuleRepository;
use Catcher\Support\Utils;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Maatwebsite\Excel\Facades\Excel;

class CatchAdminServiceProvider extends ServiceProvider
{
    /**
     * boot
     *
     * @time 2021年07月26日
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
    }

    /**
     * register
     *
     * @return void
     * @throws BindingResolutionException|\ReflectionException
     * @author CatchAdmin
     * @time 2021年07月24日
     */
    public function register()
    {
        $this->registerMarcos();

        $this->bindModuleRepository();

        $this->mergeAuthConfig();

        $this->registerCommands();

        $this->registerModulesService();

        $this->registerExcelService();

        $this->registerModuleRouterService();

        $this->registerExceptionHandler();

        $this->registerMiddleware();

        $this->registerListeners();

        $this->registerQueryLog();
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年08月12日
     * @return void
     */
    protected function registerQueryLog()
    {
        // 在Laravel生命周期程序结束之后调用
        $this->app->terminating(function (){
            Query::log();
        });
    }

    /**
     * register listeners
     *
     * @time 2021年08月06日
     * @return void
     */
    protected function registerListeners()
    {
        Event::listen(ModuleEvent::class, ModuleListener::class);
    }

    /**
     * @time 2021年07月31日
     * @return void
     */
    protected function bindModuleRepository()
    {
        $this->app->singleton(ModuleRepositoryInterface::class, ModuleRepository::class);

        $this->app->alias(ModuleRepositoryInterface::class, 'catchModuleRepository');
    }

    /**
     * register middleware
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @throws BindingResolutionException
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app->make(Kernel::class)->prependMiddleware(JsonResponse::class);
    }

    /**
     * register exception handler
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton(
            ExceptionHandler::class,
            Handler::class
        );
    }

    /**
     * register router service
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    protected function registerModuleRouterService()
    {
        $this->app->register(CatchRouteServiceProvider::class);
    }

    /**
     * register marcos
     *
     * @return void
     * @throws \ReflectionException
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    protected function registerMarcos()
    {
        Macros::register();
    }

    /**
     * register module's service provider
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return void
     */
    protected function registerModulesService()
    {
        $modules = Module::all();

        if (is_array($modules) && count($modules)) {
            foreach ($modules as $module) {
                if ($module['enable'] && class_exists($module['service'])) {
                    $this->app->register($module['service']);
                }
            }
        }
    }

    /**
     * register commands
     *
     * @return void
     * @throws \ReflectionException
     * @author CatchAdmin
     * @time 2021年07月24日
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            Utils::loadCommands(
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Commands',
                'Catcher\\'
            );
        } else {
            // support not in console
            $this->commands([
                MigrateRun::class,
                SeedRun::class
            ]);
        }
    }

    /**
     * register laravel excel provider
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return void
     */
    protected function registerExcelService()
    {
        $this->app->register(ExcelServiceProvider::class);

        $this->app->alias('Excel', Excel::class);
    }

    /**
     * publish config
     *
     * @time 2021年07月26日
     * @return void
     */
    protected function publishConfig()
    {
        $catchConfigPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'catch.php';

        $this->publishes([$catchConfigPath => $this->app->configPath('catch.php')], 'catch-config');
    }

    /**
     * merge auth config
     *
     * @time 2021年07月31日
     * @throws BindingResolutionException
     * @return void
     */
    protected function mergeAuthConfig()
    {
        if (! $this->app->configurationIsCached()) {
            $config = $this->app->make('config');

            $config->set('auth', array_merge_recursive(
                $config->get('catch.auth', []), $config->get('auth', [])
            ));
        }
    }
}
