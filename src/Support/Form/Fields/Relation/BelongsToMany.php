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
namespace Catcher\Support\Form\Fields\Relation;

use Catcher\Support\Form\Element\Components\Select;

class BelongsToMany extends Select
{
    /**
     * make
     *
     * @time 2021年08月11日
     * @param string $name
     * @param string $title
     * @return self
     */
    public static function make(string $name, string $title): BelongsToMany
    {
        $belongsToMany = new self($name, $title);

        return $belongsToMany->attr('relation', 'belongsToMany')->multiple();
    }

    /**
     * as
     *
     * @time 2021年08月24日
     * @param string $as
     * @return $this
     */
    public function as(string $as): self
    {
        $this->attr('as', $as);

        return $this;
    }
}
