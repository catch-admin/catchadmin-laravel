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

namespace Catcher\Commands\Migrate;

use Catcher\CatchAdmin;
use Catcher\Commands\CatchCommand;
use Illuminate\Support\Facades\Artisan;

class CreateMigration extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:migration {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create module migration';

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
        $module = $this->argument('module');

        if (CatchAdmin::isModulePathExist($module)) {

            $migrationPath = CatchAdmin::makeDir(CatchAdmin::getModuleMigrationPath($module));

            Artisan::call('make:migration', [
                'name' => $this->argument('name'),

                '--path' => CatchAdmin::getModuleRelativePath($migrationPath)
            ]);

            $this->info('Create Migration {'.$this->argument('name').'} successful');
        } else {
            $this->error('Module {'.$module.'} not exists');
        }
    }
}
