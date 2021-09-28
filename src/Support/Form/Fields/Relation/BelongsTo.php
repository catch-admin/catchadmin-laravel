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

class BelongsTo extends Select
{
    /**
     *
     * @time 2021年08月20日
     * @param string $name
     * @param string $title
     * @param bool $multi
     * @return BelongsTo
     */
    public static function make(string $name, string $title, bool $multi = false): BelongsTo
    {
        $belongsTo = new self($name, $title);

        if ($multi) {
            $belongsTo = $belongsTo->multiple();
        }

        return $belongsTo->attr('relation', 'belongsTo');
    }

    /**
     * as
     *
     * @time 2021年08月11日
     * @param string $field
     * @return $this
     */
    public function as(string $field): BelongsTo
    {
        $this->attr('as', $field);

        return $this;
    }

    /**
     *  label
     * @time 2021年08月20日
     * @param $label
     * @param $value
     * @return $this
     */
    public function label($label, $value): BelongsTo
    {
        $this->attr('label', $label);

        $this->attr('value', $value);

        return $this;
    }
}
