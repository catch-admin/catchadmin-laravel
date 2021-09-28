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

use Illuminate\Console\Command;
use Catcher\Facade\Module;
use Illuminate\Support\Arr;

class ControlModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:control:module';

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
     * @return void
     */
    public function handle()
    {
        $moduleName = $this->choice('Choose which module control', Arr::pluck(Module::all(), 'name'));

        $module = null;

        foreach (Module::all() as $m) {
            if ($m['name'] == $moduleName) {
                $module = $m;
                break;
            }
        }

        $yesOrNo = $this->ask(sprintf('Do You want to %s %s?', $module['enable'] ? 'Disable' : 'Enable', $module['name']), 'Y');

        if ('Y' == strtoupper($yesOrNo)) {
            Module::disOrEnable($module['name']);

            $this->info(sprintf('%s %s successful', $module['enable'] ? 'Disable' : 'Enable', $module['name']));
        }
    }
}
