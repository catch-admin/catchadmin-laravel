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

namespace Catcher\Commands\Create;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Generate\Create\CreateModule;
use Illuminate\Console\Command;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create catch module';

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
        try {
            $name = $this->argument('name');

            if (! preg_match('/[a-zA-Z]+/', $name)) {
                throw new FailedException('名称只支持英文字母');
            }

            $title = $this->ask('请输入模块中文名称');

            $description = $this->ask('请输入模块描述');

            $keywords = $this->ask('请输入模块关键字');

            (new CreateModule)->setModule($name)->setParams([
                    'title' => $title,
                    'description' => $description,
                    'keywords' => $keywords
            ])->generate();

            $this->info(sprintf('Created module [%s] successful', $name));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
