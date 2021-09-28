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

use Catcher\Facade\Module;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CatchCommand extends Command
{
    /**
     * @var string
     */
    protected $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:publish:views {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish views to vue project';


   public function __construct()
   {
       parent::__construct();

       if (! property_exists($this, 'signature')
           && property_exists($this, 'name')
           && $this->name
       ) {
            $this->signature = $this->name . ' {module}';
       }
   }

    /**
     * init
     * @time 2021年08月02日
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument('module')
         && ! in_array($input->getArgument('module'), array_column(Module::all(), 'name'))
        ) {
            $this->error(sprintf('Module [%s] Not Found', $input->getArgument('module')));
            exit;
        }
    }
}
