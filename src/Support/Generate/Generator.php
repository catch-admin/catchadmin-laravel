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


namespace Catcher\Support\Generate;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Generate\Adapter\Controller;
use Catcher\Support\Generate\Adapter\Model;
use Catcher\Support\Generate\Adapter\Schema;
use Catcher\Support\Generate\Create\CreateController;
use Catcher\Support\Generate\Create\CreateMigration;
use Catcher\Support\Generate\Create\CreateModel;
use Catcher\Support\Generate\Create\CreateRoute;
use Catcher\Support\Generate\Create\CreateSchema;

class Generator
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * generate
     *
     * @return bool
     * @throws \Exception
     * @author CatchAdmin
     * @time 2021年07月27日
     */
    public function generate(): bool
    {
        try {
            $moduleName = $this->params['controller']['module'];

            if ($this->params['create_controller']) {
                $this->files[] = (new CreateController)->setModule($moduleName)->generate($this->getControllerName());
            }
            if ($this->params['create_table']) {
                (new CreateSchema)->setParams($this->getSchemaParams())->generate($this->getSchemaName());
            }

            if ($this->params['create_model']) {
                $this->files[] = (new CreateModel)->setModule($moduleName)->generate($this->getModelName());
            }

            if ($this->params['create_migration']) {
                $this->files[] = (new CreateMigration)->setModule($moduleName)->generate($this->getSchemaName());
            }

            // 创建对应路由
            (new CreateController)->setModule($moduleName)->generate($this->getControllerName());
        } catch (\Exception $e) {
            $this->rollback();
            throw new FailedException($e->getMessage());
        }

        return true;
    }

    /**
     * 回滚
     *
     * @return void
     * @throws \Exception
     * @author CatchAdmin
     * @time 2021年07月27日
     */
    protected function rollback()
    {
        // delete controller & model & migration file
        foreach ($this->files as $file) {
            unlink($file);
        }

        // drop table
        \Illuminate\Support\Facades\Schema::drop($this->getSchemaName());

        // drop migration record
        $migration = new class extends \Illuminate\Database\Eloquent\Model {
            protected $table = 'migrations';
        };
        $migration::query()->orderByDesc('id')->first()->delete();

        // drop route
        (new CreateRoute)->setModule($this->params['controller']['module'])->removeRoute();
    }

    /**
     * set params
     *
     * @param string $params
     * @return $this
     * @author CatchAdmin
     * @time 2021年07月27日
     */
    public function setParams(string $params): Generator
    {
        $this->params = \json_decode($params, true);

        return $this;
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月27日
     * @return mixed
     */
    protected function getControllerName()
    {
        $controller = new Controller();

        [$namespace, $controllerName] = $controller->setOrigin($this->params['controller']['controller'])->toLaravel();

        return $controllerName;
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月27日
     * @return mixed
     */
    protected function getModelName()
    {
        $model = new Model();

        [$namespace, $modelName] = $model->setOrigin($this->params['controller']['controller'])->toLaravel();

        return $modelName;
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月27日
     * @return mixed
     */
    protected function getSchemaName()
    {
        return $this->params['controller']['table'];
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月27日
     * @return array
     */
    protected function getSchemaParams(): array
    {
        $schema = new Schema();

        return $schema->setOrigin(\json_encode($this->params))->toLaravel();
    }
}
