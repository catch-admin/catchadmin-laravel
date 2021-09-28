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

class Modules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:modules';

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
        $modules = [];

        foreach (Module::all() as $module) {
            $module['enable'] = $module['enable'] ? '启用' : '禁用';

            $modules[] = $module;
        }

        $this->table([
            '名称', '标题', '描述', '关键字', '服务', '版本', '状态'
        ], $modules);
    }
}
