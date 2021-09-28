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

namespace Catcher\Support\Generate\Create;

use Catcher\CatchAdmin;
use Catcher\Exceptions\FailedException;
use Catcher\Facade\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use JaguarJack\Generate\Build\Property;
use JaguarJack\Generate\Exceptions\GenerateFailedExceptions;
use JaguarJack\Generate\Exceptions\TypeNotFoundException;
use JaguarJack\Generate\Generator;
use Catcher\Providers\CatchModuleServiceProvider;

class CreateModule extends Creator
{

    /**
     * @var string
     */
    protected $moduleDir;

    /**
     * generate
     *
     * @param string $name
     * @return bool
     * @author CatchAdmin
     * @time 2021年07月25日
     */
    public function generate(string $name = ''): bool
    {
        try {
            $this->moduleDir = CatchAdmin::getModulePath($this->module);

            if (Module::create([
                'name' => $this->module,

                'title' => $this->params['title'],

                'description' => $this->params['description'],

                'keywords' => $this->params['keywords']
            ])) {

                $this->createDefaultDirs();

                $this->generateServiceProvider();

                $this->generateRoute();
            }
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }

        return true;
    }


    /**
     * default generate dirs
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    protected function createDefaultDirs()
    {
        foreach (config('catch.module.default_dirs') as $dir) {
            File::makeDirectory($this->moduleDir . $dir);
        }
    }

    /**
     * generate service provider
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @throws GenerateFailedExceptions
     * @throws TypeNotFoundException
     * @return bool
     */
    protected function generateServiceProvider(): bool
    {
        $serviceProvider = ucfirst($this->module) . 'ServiceProvider';

        return Generator::namespace(trim(CatchAdmin::getModuleNamespace($this->module), '\\'))
                ->uses([
                    CatchModuleServiceProvider::class
                ])
                ->class($serviceProvider, function ($class, Generator $generator){
                        $class->extend('CatchModuleServiceProvider');

                        $generator->property('name', function (Property $property){
                            return $property->makeProtected()->setDefault($this->module);
                        });

                        $generator->method('boot', function ($method, $generator){
                            return $method->makePublic();
                        });

                    $generator->method('register', function ($method, $generator){
                        return $method->makePublic();
                    });
                })->file($serviceProvider, $this->moduleDir);
    }

    /**
     * generate route
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @throws \Exception
     * @return void
     */
    protected function generateRoute(): void
    {
        (new CreateRoute)->setModule($this->module)->generate();
    }
}
