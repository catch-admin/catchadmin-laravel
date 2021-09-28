<?php
namespace Catcher\Support\Form\Fields;

use Catcher\Support\Form\Element\Components\Select;
use Illuminate\Support\Collection;

class SelectMultiple extends Select
{
    /**
     * make
     *
     * @time 2021年08月09日
     * @param string $name
     * @param string $title
     * @param array|Collection $options
     * @return Select
     */
    public static function make(string $name, string $title, $options = null): Select
    {
        $multiple = new self($name, $title);

        if ($options) {
            return $multiple->multiple()->options($options);
        }

        return $multiple->multiple()->clearable();
    }
}
