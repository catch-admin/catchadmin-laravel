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
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Catcher\Facade\Zipper;

class ModuleZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:module:zip {module} {--path=} {--unzip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catch module to zip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $moduleName = $this->argument('module');

        $path = $this->option('path');

        $unzip = $this->option('unzip');

        if ($unzip) {
            // 待处理
        } else {
            $modulePath = $path ? $path . DIRECTORY_SEPARATOR . $moduleName : CatchAdmin::getModulePath($moduleName);

            $zipper =  Zipper::make(storage_path() . DIRECTORY_SEPARATOR . $moduleName . '.zip');

            foreach (File::allFiles($modulePath) as $file) {
                if ($file->getRelativePath()) {
                    $zipper->folder($file->getRelativePath())->add($file->getPathname());
                } else {
                    $zipper->folder('')->add($file->getPathname());
                }
            }

            $zipper->close();
        }
    }
}
