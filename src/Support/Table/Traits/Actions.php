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

namespace Catcher\Support\Table\Traits;

use Catcher\Support\Table\Actions as ButtonAction;
use Catcher\Support\Table\Components\Button;

/**
 * actions trait
 */
trait Actions
{
    /**
     * table 操作
     *
     * @var array
     */
    protected $actions = [];

    /**
     * 设置 actions
     *
     * @time 2021年03月21日
     * @param array|\Closure $actions
     * @return $this
     */
    public function actions($actions): self
    {
        if (is_array($actions)) {
            $emptyActions = [];

            foreach ($actions as $action) {
                $emptyActions[] = $action();
            }

            $this->actions = $emptyActions;
        }

        if (is_callable($actions)) {
            $actions($this);
        }

        return $this;
    }

    /**
     * delete
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function delete(): self
    {
        $this->actions[] = ButtonAction::delete()();

        return $this;
    }

    /**
     * update
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function update():self
    {
        $this->actions[] = ButtonAction::update()();

        return $this;
    }

    /**
     * create
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function store(): self
    {
        $this->actions[] = ButtonAction::store()();

        return $this;
    }

    /**
     * create
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function export(string $route): self
    {
        $this->actions[] = ButtonAction::export()();

        return $this->exportRoute('export/' . ltrim($route, '/'));
    }

    /**
     * view
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function view(): self
    {
        $this->actions[] = ButtonAction::view()();

        return $this;
    }

    /**
     * append actions
     *
     * @time 2021年03月28日
     * @param Button $action
     * @return $this
     */
    public function appendAction(Button $action): self
    {
        $this->actions[] = $action();

        return $this;
    }

    /**
     * get actions
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}
