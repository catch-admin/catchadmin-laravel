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

namespace Catcher\Commands;

use Illuminate\Support\Facades\Artisan;

class PublishViews extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'catch:publish:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish views to vue project';


    public function handle()
    {
        Artisan::call('vendor:publish', [
            '--force' => true,

            '--tag' => sprintf('catch-%s', lcfirst($this->argument('module')))
        ]);
    }
}
