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

namespace Catcher\Support;

class Tree
{
    protected static $pk = 'id';

    /**
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param string $pk
     * @return Tree
     */
    public static function setPk(string $pk): Tree
    {
        self::$pk = $pk;

        return new self();
    }

    /**
     * return done
     *
     * @author CatchAdmin
     * @time 2021年07月25日
     * @param array $items
     * @param int $pid
     * @param string $pidField
     * @param string $child
     * @return array
     */
    public static function done(array $items, int $pid = 0, string $pidField = 'parent_id', string $child = 'children'): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item[$pidField] == $pid) {
                $children = self::done($items, $item[self::$pk], $pidField, $child);

                if (count($children)) {
                    $item[$child] = $children;
                }

                $tree[] = $item;
            }
        }

        return $tree;
    }
}
