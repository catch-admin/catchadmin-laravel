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
use Illuminate\Support\Facades\File;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\ClassMethod;
use JaguarJack\Generate\Generator;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

class CreateSeeder extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:seeder {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catch make seeder';


    /**
     *
     * @author CatchAdmin
     * @time 2021年08月01日
     * @throws \Exception
     * @return void
     */
    public function handle()
    {
        $module = $this->argument('module');
        if (CatchAdmin::isModulePathExist($module)) {

            $seeder = (
                    File::isDirectory(CatchAdmin::getModuleSeederPath($module)) ?
                    CatchAdmin::getModuleSeederPath($module) :
                    CatchAdmin::makeDir(CatchAdmin::getModuleSeederPath($module))
                ) . $this->getSeederName() . '.php';

            if (File::exists($seeder)) {
                $this->error('Seeder has been created');
                exit;
            }

            File::put($seeder, $this->seederContent($this->getSeederName()));

            $this->info('Create Migration {'.$this->getSeederName().'} successful');
        } else {
            $this->error('Module {'.$module.'} not exists');
        }
    }

    /**
     * seeder content
     *
     * @author CatchAdmin
     * @time 2021年08月01日
     * @param $name
     * @throws \Exception
     * @return string
     */
    protected function seederContent($name): string
    {
        $generator = new Generator();

        return '<?php' . PHP_EOL . PHP_EOL . $generator->getContent([
                new Use_([new UseUse(new Name('Illuminate\Database\Seeder'))]),

                (new Class_($name))->extend('Seeder')
                    ->setDocComment(PHP_EOL)
                    ->useMethod((new ClassMethod('run'))->makePublic())
                    ->fetch()
            ]);

    }

    /**
     * seeder name
     *
     * @time 2021年07月31日
     * @return string
     */
    protected function getSeederName(): string
    {
        return ucfirst($this->argument('name')) . 'Seeder';
    }
}
