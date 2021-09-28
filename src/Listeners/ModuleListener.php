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

namespace Catcher\Listeners;

use Catcher\CatchAdmin;
use Catcher\Events\ModuleEvent;
use Catcher\Exceptions\FailedException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ModuleListener
{
    public function handle(ModuleEvent $event)
    {
        $moduleJson = CatchAdmin::getModuleJsonPath($event->module['name']);

        switch ($event->type) {
            case ModuleEvent::CREATE:
            case ModuleEvent::UPDATE:
            case ModuleEvent::DIS_OR_ENABLE:
                File::put($moduleJson, \json_encode($event->module, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
                break;
            case ModuleEvent::DELETE:
                $this->delete($event);
                break;
            default:
                break;
        }
    }

    /**
     * delete module
     *
     * @time 2021年09月22日
     * @param $event
     * @return void
     */
    protected function delete($event)
    {
        if (File::deleteDirectory($event->module['name'])) {
            Artisan::call('catch:migrate:rollback', [
                'module' => ucfirst($event->module['name'])
            ]);
        } else {
            throw new FailedException("Delete Module [{$event->module['name']}] Failed");
        }
    }
}
