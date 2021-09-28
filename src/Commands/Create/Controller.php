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
use Catcher\Support\Generate\Create\CreateController;
use Catcher\Support\Generate\Create\CreateRoute;

class Controller extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:controller {module : module name}
                                                  {controller : controller name}
                                                  {model : model name}
                                                  {--b : created builder}
                                                  {--r : created route}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create catch controller';

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

            $controller = $this->argument('controller');

            (new CreateController)
                ->setModel(ucfirst($this->argument('model')))
                ->setBuild(! $this->option('b'))
                ->setModule($module)
                ->generate($controller);

            if ($this->option('r')) {
                (new CreateRoute)->setModule($module)->generate(lcfirst($controller));
            }

            $this->info('Generate Controller Successful');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
