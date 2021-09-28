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

namespace Catcher\Support;

use Catcher\CatchAdmin;
use Catcher\Contracts\ModuleRepositoryInterface;
use Catcher\Events\ModuleEvent;
use Catcher\Exceptions\FailedException;
use Illuminate\Support\Facades\File;

/**
 * Class ModuleRepository
 * @package Catcher\Support
 * @author CatchAdmin
 * @time 2021年07月24日
 */
class ModuleRepository implements ModuleRepositoryInterface
{
    protected $moduleJson;

    public function __construct()
    {
        $this->moduleJson = storage_path('app' . DIRECTORY_SEPARATOR . 'module.json');
    }

    /**
     * all
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @return array|mixed
     */
    public function all()
    {
        if (! File::exists($this->moduleJson)) {
            return [];
        }

        return \json_decode(File::get($this->moduleJson), true) ? : [];
    }

    /**
     * create module json
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param array $module
     * @throws \Exception
     * @return bool|int
     */
    public function create(array $module)
    {
        $modules = $this->all();

        $this->hasSameNameModule($module['name'], $modules);

        $module['service'] = sprintf('\\%s%s', CatchAdmin::getModuleNamespace($module['name']), ucfirst($module['name']) . 'ServiceProvider');
        $module['version'] = '1.0.0';
        $module['enable'] = true;

        $modules[] = $module;

        event(new ModuleEvent($module, ModuleEvent::CREATE));

        return $this->putModuleToJson($modules);
    }

    /**
     * module info
     *
     * @author CatchAdmin
     * @time 2021年08月01日
     * @param string $name
     * @return false|mixed
     */
    public function show(string $name)
    {
        // TODO: Implement show() method.
        foreach ($this->all() as $module) {
            if ($module['name'] == $name) {
                return $module;
            }
        }

        return false;
    }

    /**
     * update module json
     *
     * @param array $module
     * @return bool|int
     * @author CatchAdmin
     * @time 2021年07月24日
     */
    public function update(array $module)
    {
        $modules = $this->all();

        foreach ($modules as &$m) {
            if ($m['name'] == $module['name']) {
                $m['title'] = $module['title'];
                $m['description'] = $module['description'];
                $m['keywords'] = $module['keywords'];
                $m['enable'] = $module['enable'];
                event(new ModuleEvent($module, ModuleEvent::UPDATE));
                break;
            }
        }

        return $this->putModuleToJson($modules);
    }

    /**
     * delete module json
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $name
     * @return bool|int
     */
    public function delete(string $name)
    {
        $modules = $this->all();

        foreach ($modules as $k => $module) {
            if ($module['name'] == $name) {
                unset($modules[$k]);
                event(new ModuleEvent($module, ModuleEvent::DELETE));
            }
        }

        return $this->putModuleToJson($modules);
    }

    /**
     * disable or enable
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param $name
     * @return bool|int
     */
    public function disOrEnable($name)
    {
        $modules = $this->all();

        foreach ($modules as &$module) {
            if ($module['name'] == $name) {
                $module['enable'] = !$module['enable'];
                event(new ModuleEvent($module, ModuleEvent::DIS_OR_ENABLE));
            }
        }

        return $this->putModuleToJson($modules);
    }

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param string $name
     * @param array $modules
     * @throws \Exception
     * @return void
     */
    protected function hasSameNameModule(string $name, array $modules)
    {
        if (in_array($name, array_column($modules, 'name'))) {
            throw new FailedException(sprintf('Module [%s] has been created', $name));
        }
    }

    /**
     * put module to json
     *
     * @author CatchAdmin
     * @time 2021年07月24日
     * @param array $module
     * @return int
     */
    public function putModuleToJson(array $module): int
    {
        return File::put($this->moduleJson, \json_encode($module, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
