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

namespace Catcher\Commands\Tools;

use Catcher\CatchAdmin;
use Catcher\Support\ModuleRepository;
use Illuminate\Console\Command;
use Catcher\Facade\Module;
use Illuminate\Support\Facades\File;

class CacheModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:cache:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catch make menu';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $moduleRepository = new ModuleRepository();

        $modules = [];

        foreach (CatchAdmin::getModulesPath() as $modulePath) {
            $modules[] = \json_decode(File::get($modulePath . DIRECTORY_SEPARATOR . 'module.json'), true);
        }

        $moduleRepository->putModuleToJson($modules);

        $this->info('Cache Modules Successful');
    }
}
