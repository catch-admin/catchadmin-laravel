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

namespace Catcher\Macros;

use Catcher\Base\CatchExport;
use Catcher\Support\Tree;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;

class Collection
{
    /**
     * collection to tree
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    public function toTree()
    {
        LaravelCollection::macro(__FUNCTION__, function (int $pid = 0, string $pidField = 'parent_id', string $child = 'children'){
            return Tree::done($this->all(), $pid, $pidField, $child);
        });
    }


    /**
     * export collection
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    public function export()
    {
        LaravelCollection::macro(__FUNCTION__, function (array $headers, bool $csv = false) {
            return (new class($headers, $this->all(), $csv) extends CatchExport
            {
                protected $headers;

                protected $columns;

                public function __construct($headers, $columns, $csv)
                {
                    $this->headers = $headers;

                    $this->columns = $columns;

                    $this->toCsv = $csv;
                }

                public function headers(): array
                {
                    return $this->headers;
                }

                public function columns()
                {
                    return $this->columns;
                }
            })->export();
        });
    }

    /**
     * export collection
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @return void
     */
    public function download()
    {
        LaravelCollection::macro(__FUNCTION__, function (array $headers, bool $csv = false){
            return (new class($headers, $this->all(), $csv) extends CatchExport
            {
                protected $headers;

                protected $columns;

                public function __construct($headers, $columns, $csv)
                {
                    $this->headers = $headers;

                    $this->columns = $columns;

                    $this->toCsv = $csv;
                }

                public function headers(): array
                {
                    return $this->headers;
                }

                public function columns()
                {
                    return $this->columns;
                }
            })->download();
        });
    }

    /**
     * toOptions
     *
     * @time 2021年08月11日
     * @return void
     */
    public function toOptions()
    {
        LaravelCollection::macro(__FUNCTION__, function (){
            return $this->transform(function ($item, $key) use (&$options){
                if ($item instanceof Arrayable) {
                    $item = $item->toArray();
                }

                if (is_array($item)) {
                    $item = array_values($item);
                    return [
                        'value' => $item[0],
                        'label' => $item[1]
                    ];
                } else {
                     return [
                         'value' => $key,
                         'label' => $item
                     ];
                 }
            })->values();
        });
    }
}
