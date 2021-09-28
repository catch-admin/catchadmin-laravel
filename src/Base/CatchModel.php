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

namespace Catcher\Base;

use Catcher\Support\DB\SoftDelete;
use Catcher\Traits\DB\BaseOperate;
use Catcher\Traits\DB\ScopeTrait;
use Catcher\Traits\DB\Trans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @mixin \Illuminate\Database\Query\Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * Class CatchModel
 * @package Catcher\Base
 * @author CatchAdmin
 * @time 2021年07月24日
 */
abstract class CatchModel extends Model
{
    use BaseOperate, Trans, SoftDeletes, ScopeTrait;

    /**
     * unix timestamp
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * paginate limit
     */
    protected $perPage = 10;

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',

        'updated_at' => 'datetime:Y-m-d H:i:s',

        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * enable status
     */
    public const ENABLE = 1;

    /**
     * disable status
     */
    public const DISABLE = 2;

    /**
     * soft delete
     *
     * @time 2021年08月09日
     * @return void
     */
    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new SoftDelete);
    }
}
