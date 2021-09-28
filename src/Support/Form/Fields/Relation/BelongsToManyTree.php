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

use Catcher\Support\Form\Element\Components\Tree;

class BelongsToManyTree extends Tree
{
    protected $parentId = 'parent_id';

    /**
     * make
     *
     * @time 2021年08月11日
     * @param string $name
     * @param string $title
     * @return BelongsToManyTree
     */
    public static function make(string $name, string $title): BelongsToManyTree
    {
        $belongsToMany = new self($name, $title);

        return $belongsToMany->attr('relation', 'belongsToMany');
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

    /**
     * set parent id field
     *
     * @time 2021年08月24日
     * @param string $parentId
     * @return $this
     */
    public function setParent(string $parentId): BelongsToManyTree
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * get parent id
     *
     * @time 2021年08月24日
     * @return string
     */
    public function getParentId(): string
    {
        return $this->parentId;
    }
}
