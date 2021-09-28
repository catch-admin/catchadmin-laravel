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

trait Events
{
    /**
     * 表单事件
     *
     * @var array
     */
    protected $events = [];

    /**
     * 表单事件
     *
     * @time 2021年03月21日
     * @param array $events
     * @return $this
     */
    public function events(array $events): self
    {
        $this->events = $events;

        return $this;
    }

    /**
     * 表格选择事件
     *
     * @time 2021年03月29日
     * @return mixed
     */
    public function selectionChange(): self
    {
        $this->appendEvents([
            'selection-change' => 'handleSelectMulti'
        ]);

        return $this;
    }

    /**
     * 追加 events
     *
     * @time 2021年03月28日
     * @param array $events
     * @return $this
     */
    public function appendEvents(array $events): self
    {
        $this->events = array_merge($this->events, $events);

        return $this;
    }


    /**
     * 获取事件
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getEvents(): array
    {
        return $this->events;
    }

}
