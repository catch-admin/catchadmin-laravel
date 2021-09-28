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
use Catcher\Facade\Module;
use Catcher\Support\Utils;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\RouteFileRegistrar;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class CatchRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // if routes are cached, will not register route
        if (! $this->routesAreCached()) {
            Route::prefix('api')
                ->middleware(config('catch.middleware_group'))
                ->group(function ($router) {
                    $routerRegister = new RouteFileRegistrar($router);
                    foreach (Module::all() as $module) {
                        if ($module['enable'] ?? false) {
                            if (CatchAdmin::isModulePathExist($module['name']) && File::exists(CatchAdmin::getModuleRoutePath($module['name']))) {
                                $routerRegister->register(CatchAdmin::getModuleRoutePath($module['name']));
                            }
                        }
                    }
                });
        }
    }
}
