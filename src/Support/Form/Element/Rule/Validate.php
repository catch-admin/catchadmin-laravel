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

namespace Catcher\Support\Form\Element\Rule;

class Validate
{
    const TYPE_STRING = 'string';

    const TYPE_ARRAY = 'array';

    const TYPE_NUMBER = 'number';

    const TYPE_INTEGER = 'integer';

    const TYPE_FLOAT = 'float';

    const TYPE_OBJECT = 'object';

    const TYPE_ENUM = 'enum';

    const TYPE_URL = 'url';

    const TYPE_HEX = 'hex';

    const TYPE_EMAIL = 'email';

    const TYPE_DATE = 'date';

    const TRIGGER_CHANGE = 'change';

    const TRIGGER_BLUR = 'blur';

    const TRIGGER_SUBMIT = 'submit';

    protected $validate = [
        'fields' => []
    ];

    protected $type;

    protected $trigger;

    /**
     * Validate constructor.
     * @param string $type
     * @param string $trigger
     */
    public function __construct(string $type, string $trigger = self::TRIGGER_CHANGE)
    {
        $this->type($type);

        $this->trigger($trigger);
    }

    /**
     * @param string $trigger
     * @return $this
     */
    public function trigger(string $trigger): Validate
    {
        $this->trigger = $trigger;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function type(string $type): Validate
    {

        $this->type = $type;

        return $this;
    }

    /**
     *
     *
     * @time 2021年08月25日
     * @param $validate
     * @return $this
     */
    public function set($validate): Validate
    {
        if (! is_array($validate['fields'])) {
            $validate['fields'] = [];
        }

        return $this;
    }

    /**
     *
     * @time 2021年08月25日
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields): Validate
    {
        $this->validate['fields'] = array_merge($this->validate['fields'], $fields);

        return $this;
    }

    /**
     *
     * @time 2021年08月25日
     * @param $field
     * @param $validate
     * @return $this
     */
    public function field($field, $validate): Validate
    {
        $this->validate['fields'][$field] = $validate;

        return $this;
    }

    /**
     * 必填
     *
     * @return $this
     */
    public function required(): Validate
    {
        $this->validate['required'] = true;

        return $this;
    }

    /**
     * 长度或值必须在这个范围内
     *
     * @param int|float $min
     * @param int|float $max
     * @return $this
     */
    public function range($min, $max): Validate
    {
        $this->validate['min'] = (float)$min;

        $this->validate['max'] = (float)$max;

        return $this;
    }

    /**
     * 长度或值必须大于这个值
     *
     * @param int|float $min
     * @return $this
     */
    public function min($min): Validate
    {
        $this->validate['min'] = (float)$min;

        return $this;
    }

    /**
     * 长度或值必须小于这个值
     *
     * @param int|float $max
     * @return $this
     */
    public function max($max): Validate
    {
        $this->validate['max'] = (float)$max;

        return $this;
    }

    /**
     * 长度或值必须等于这个值
     *
     * @param int $length
     * @return $this
     */
    public function length(int $length): Validate
    {
        $this->validate['len'] = $length;

        return $this;
    }

    /**
     * 值必须在 list 中
     *
     * @param array $list
     * @return $this
     */
    public function enum(array $list): Validate
    {
        $this->validate['enum'] = $list;

        return $this;
    }

    /**
     * 错误信息
     *
     * @param string$message
     * @return $this
     */
    public function message(string $message): Validate
    {
        $this->validate['message'] = $message;

        return $this;
    }

    /**
     * 正则
     *
     * @param string $pattern
     * @return $this
     */
    public function pattern(string $pattern): Validate
    {
        $this->validate['pattern'] = $pattern;

        return $this;
    }

    public function getValidate(): array
    {
        $validate = $this->validate;
        $validate['type'] = $this->type;
        $validate['trigger'] = $this->trigger;
        $fields = $validate['fields'];

        if (!($fieldCount = count($fields)) && count($validate) === 1) {
            return [];
        }

        if ($fieldCount) {
            foreach ($fields as $k => $field) {
                $fields[$k] = $field instanceof self ? $field->getValidate() : $field;
            }
            $validate['fields'] = (object)$fields;
        } else {
            unset($validate['fields']);
        }

        return $validate;
    }
}
