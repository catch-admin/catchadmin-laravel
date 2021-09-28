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

namespace Catcher\Providers;

use Catcher\CatchAdmin;
use Catcher\Support\Utils;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ReflectionException;

abstract class CatchModuleServiceProvider extends ServiceProvider
{
    /**
     * the name of module
     *
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @var array
     */
    protected $subscribe = [];

    /**
     * commands run not in console
     *
     * @var array
     */
    protected $notInConsole = [];
    /**
     *
     * @time 2021年07月31日
     * @return void
     * @throws ReflectionException
     */
    public function register()
    {
        $this->registerListeners();

        $this->loadConfig();

        $this->registerCommands();

        $this->publishViews();

        $this->registerMiddlewares();
    }

    /**
     * register middlewares
     *
     * @time 2021年08月14日
     * @return void
     */
    protected function registerMiddlewares()
    {}

    /**
     * 注册事件监听
     *
     * @time 2021年07月30日
     * @return void
     */
    protected function registerListeners()
    {
        foreach ($this->listeners as $event => $listeners) {
            if (is_string($listeners)) {
                Event::listen($event, $listeners);
            } else {
                foreach (array_unique($listeners) as $listener) {
                    Event::listen($event, $listener);
                }
            }
        }

        foreach ($this->subscribe as $subscriber) {
            Event::subscribe($subscriber);
        }
    }


    /**
     * load config
     *
     * @time 2021年07月31日
     * @return void
     */
    protected function loadConfig()
    {
        if (method_exists($this, 'loadConfigFrom')) {
            $this->mergeConfigFrom($this->loadConfigFrom(), 'catch');
        }
    }

    /**
     * register commands
     *
     * @time 2021年07月31日
     * @throws ReflectionException
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole() && CatchAdmin::isModulePathExist($this->name)) {
            if (File::isDirectory(CatchAdmin::getCommandsPath($this->name))) {
                Utils::loadCommands(
                    CatchAdmin::getCommandsPath($this->name),
                    CatchAdmin::getModuleNamespace($this->name),
                    CatchAdmin::getModulePath($this->name)
                );
            }
        } else {
            $this->commands($this->notInConsole);
        }
    }


    /**
     * publish views
     *
     * @time 2021年07月31日
     * @return void
     */
    public function publishViews()
    {
        if (config('catch.front_views_path') && File::isDirectory(CatchAdmin::getModuleViewsPath($this->name))) {
            $this->publishes([
                CatchAdmin::getModuleViewsPath($this->name) => config('catch.front_views_path')
            ], sprintf('catch-%s-views', lcfirst($this->name)));
        }
    }
}
