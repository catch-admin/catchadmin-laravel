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

use Catcher\Commands\CatchCommand;
use Catcher\Support\Generate\Create\CreateModel;
use Illuminate\Support\Str;

class Model extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:model {module} {model} {--f : force delete} {--t= : the model of table name}';

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
            $module = $this->argument('module');

            $model = $this->argument('model');

            $table = $this->option('t');

            if (!$table) {
                $table = Str::snake($model);
            }

            (new CreateModel)->setTable($table)
                ->setSoftDelete(! $this->option('f'))
                ->setModule($module)
                ->generate($model);

            $this->info('Generate Model Successful');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
