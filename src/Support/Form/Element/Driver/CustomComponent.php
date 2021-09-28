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

namespace Catcher\Support\Form\Element\Driver;


use Catcher\Support\Form\Element\Rule\BaseRule;
use Catcher\Support\Form\Element\Rule\CallPropsRule;
use Catcher\Support\Form\Element\Rule\ChildrenRule;
use Catcher\Support\Form\Element\Rule\ControlRule;
use Catcher\Support\Form\Element\Rule\EmitRule;
use Catcher\Support\Form\Element\Rule\PropsRule;
use Catcher\Support\Form\Element\Rule\ValidateRule;

/**
 * 自定义组件
 * Class CustomComponent
 */
class CustomComponent implements \JsonSerializable, \ArrayAccess
{
    use BaseRule;
    use ChildrenRule;
    use EmitRule;
    use PropsRule;
    use ValidateRule;
    use CallPropsRule;
    use ControlRule;

    protected static $propsRule = [];

    protected $defaultProps = [];

    protected $appendRule = [];

    /**
     * CustomComponent constructor.
     * @param string|null $type
     */
    public function __construct(string $type = null)
    {
        $this->setRuleType(is_null($type) ? $this->getComponentName() : $type)->props($this->defaultProps);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function __invoke(): array
    {
        return $this->build();
    }

    public function toJson()
    {
        return json_encode($this->build());
    }

    protected function getComponentName(): string
    {
        return lcfirst(basename(str_replace('\\', '/', get_parent_class($this))));
    }

    public function appendRule($name, $value): CustomComponent
    {
        $this->appendRule[$name] = $name == 'props' ? (object)$value : $value;
        return $this;
    }

    public function getRule()
    {
        return array_merge(
            $this->parseBaseRule(),
            $this->parseEmitRule(),
            $this->parsePropsRule(),
            $this->parseValidateRule(),
            $this->parseChildrenRule(),
            $this->parseControlRule()
        );
    }

    public function build(): array
    {
        return $this->appendRule + $this->getRule();
    }

    public function jsonSerialize(): array
    {
        return $this->build();
    }

    public function offsetExists($offset)
    {
        return isset($this->props[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->props[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->props[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->props[$offset]);
    }
}
