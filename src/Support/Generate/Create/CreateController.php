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

namespace Catcher\Support\Generate\Create;

use Catcher\Base\CatchController;
use Catcher\Base\CatchResponse;
use Catcher\CatchAdmin;
use Catcher\Support\CatchBuilder;
use Catcher\Support\Table\CatchTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JaguarJack\Generate\Build\MethodCall;
use JaguarJack\Generate\Build\Property;
use JaguarJack\Generate\Build\Value;
use JaguarJack\Generate\Generator;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\ClassMethod;
use JaguarJack\Generate\Build\Params;
use JaguarJack\Generate\Define;
use Catcher\Exceptions\FailedException;

class CreateController extends Creator
{

    protected $model;

    protected $build = true;

    protected $uses = [
        Request::class,
        CatchResponse::class,
    ];

    /**
     *
     * @param string $name
     * @return string
     * @author CatchAdmin
     * @time 2021年07月26日
     */
    public function generate(string $name): string
    {
        if (! $name) {
            throw new FailedException('未填写控制器名称');
        }

        $controller = Str::contains($name, 'Controller') ? $name : $name . 'Controller';

        $asModel = lcfirst(Str::contains($this->model, 'Model') ? $this->model : $this->model . 'Model');
        $model = sprintf('%s as %s',  CatchAdmin::getModuleModelNamespace($this->module) . $this->model, ucfirst($asModel));

        $this->uses[] = $model;

        if ($this->build) {
            return $this->controllerOfBuild($controller, $model);
        }

        try {
            Generator::namespace(trim(CatchAdmin::getModuleControllerNamespace($this->module), '\\'))
                ->class($controller, function (Class_ $class, Generator $generator) use ($asModel) {
                    $generator->property($asModel, function ($property) {
                        return $property->makeProtected();
                    });

                    // construct 方法
                    $generator->method('__construct', function (ClassMethod $method) use ($asModel) {
                        return $method->addParam(
                            Params::name($asModel, ucfirst($asModel))
                        )->body([
                            Define::variable(Define::propertyDefineIdentifier($asModel), Define::variable($asModel))
                        ])->makePublic();
                    });

                    // index 方法
                    $generator->method('index', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method->body([
                            $generator->call('paginate', [
                                $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'getList'], [])
                            ], 'CatchResponse')->call()
                        ])->makePublic()->return()->setReturnType('array');
                    });

                    // store 方法
                    $generator->method('store', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('request')->setType('Request')
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'storeBy'], [
                                        $generator->methodCall(['request', 'post'], [])
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('array');
                    });

                    // show 方法
                    $generator->method('show', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'firstBy'], [
                                        Define::variable('id'),
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('array');
                    });


                    // update 方法
                    $generator->method('update', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                                Params::name('request')->setType('Request')
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'updateBy'], [
                                        Define::variable('id'),
                                        $generator->methodCall(['request', 'post'], [])
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('array');
                    });

                    // destroy 方法
                    $generator->method('destroy', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'deleteBy'], [
                                        Define::variable('id'),
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('array');
                    });

                })->uses($this->uses)->file($controller, CatchAdmin::getModuleControllerPath($this->module));

                if ( ! File::exists(CatchAdmin::getModuleControllerPath($this->module). $controller . '.php')) {
                    throw new FailedException(sprintf('Created Controller [%s] Failed', $controller));
                }

        } catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }

        return CatchAdmin::getModuleControllerPath($this->module). $controller . '.php';
    }

    public function setModel(string $model): CreateController
    {
        $this->model = $model;

        return $this;
    }

    /**
     * build pattern controller
     *
     * @time 2021年08月13日
     * @param $controller
     * @return string
     */
    public function controllerOfBuild($controller, $model): string
    {
        $builder = str_replace('Controller', '', $controller);

        $this->createBuild($builder, $model);

        try {
            Generator::namespace(trim(CatchAdmin::getModuleControllerNamespace($this->module), '\\'))
                ->class($controller, function (Class_ $class, Generator $generator) use ($builder) {
                    $class->extend('CatchController');

                    // construct 方法
                    $generator->method('__construct', function (ClassMethod $method) use ($builder) {
                        return $method->addParam(
                            Params::name('builder', ucfirst($builder))
                        )->body([
                            Define::variable(Define::propertyDefineIdentifier('builder'), Define::variable('builder')),

                            MethodCall::staticCall('parent', '__construct')
                        ])->makePublic();
                    });

                })->uses([
                    CatchController::class,
                    CatchAdmin::getBuildNamespace($this->module) . $builder
                ])->file($controller, CatchAdmin::getModuleControllerPath($this->module));

            if ( ! File::exists(CatchAdmin::getModuleControllerPath($this->module). $controller . '.php')) {
                throw new FailedException(sprintf('Created Controller [%s] Failed', $controller));
            }

        } catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }

        return CatchAdmin::getModuleControllerPath($this->module). $controller . '.php';
    }

    /**
     * set build
     *
     * @time 2021年08月13日
     * @param bool $type
     * @return $this
     */
    public function setBuild(bool $type = false): CreateController
    {
        $this->build = $type;

        return $this;
    }

    protected function createBuild($builder, $model)
    {
        try {
            Generator::namespace(trim(CatchAdmin::getBuildNamespace($this->module), '\\'))
                ->class($builder, function (Class_ $class, Generator $generator) use ($builder, $model) {
                    $class->extend('CatchBuilder');

                    $generator->property('model', function (Property $property) use ($model) {
                        return $property->makeProtected()->setDefault(Define::classConstFetch(explode('as', $model)[1]));
                    });

                    // table 方法
                    $generator->method('table', function (ClassMethod $method, $generator) use ($model){
                        return $method->makePublic()
                                    ->addParam( Params::name('table', ucfirst('Table')));
                    });

                    // fields 方法
                    $generator->method('fields', function (ClassMethod $method, $generator) use ($model){
                        return $method
                            ->body([
                                Value::fetch([])
                            ])->makePublic()->return('array');
                    });
                })->uses([
                    CatchTable::class . ' as Table',
                    CatchBuilder::class,
                    $model
                ])->file($builder, CatchAdmin::getBuildPath($this->module));

            if ( ! File::exists(CatchAdmin::getBuildPath($this->module). $builder . '.php')) {
                throw new FailedException(sprintf('Created Controller [%s] Failed', $builder));
            }

        } catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }
    }
}
