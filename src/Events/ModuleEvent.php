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

namespace Catcher\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const CREATE = 'create';

    const UPDATE = 'update';

    const DELETE = 'delete';

    const DIS_OR_ENABLE = 'disOrEnable';

    /**
     * @var array
     */
    public $module;

    /**
     * @var string
     */
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($module, $type)
    {
        //
        $this->module = $module;

        $this->type = $type;
    }
}
