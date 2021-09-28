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

namespace Catcher\Support\Form\Element\Components;

use Catcher\Support\Form\Element\Driver\FormComponent;
use Catcher\Support\Form\Element\Rule\ValidateFactory;
use Illuminate\Support\Collection;

/**
 * 多级联动组件
 * Class Cascader
 *
 * @method $this type(string $type) 数据类型, 支持 city_area(省市区三级联动), city (省市二级联动), other (自定义)
 * @method $this props(array $props) 配置选项
 * @method $this size(string $size) 尺寸, 可选值: medium / small / mini
 * @method $this placeholder(string $placeholder) 输入框占位文本, 默认值: 请选择
 * @method $this disabled(bool $disabled = true) 是否禁用, 默认值: false
 * @method $this clearable(bool $clearable = true) 是否支持清空选项, 默认值: false
 * @method $this showAllLevels(bool $showAllLevels = true) 输入框中是否显示选中值的完整路径, 默认值: true
 * @method $this collapseTags(bool $collapseTags = true) 多选模式下是否折叠Tag, 默认值: false
 * @method $this separator(string $separator) 选项分隔符, 默认值: 斜杠' / '
 * @method $this filterable(bool $filterable = true) 是否可搜索选项
 * @method $this debounce(float $debounce) 搜索关键词输入的去抖延迟，毫秒, 默认值: 300
 * @method $this popperClass(string $popperClass) 自定义浮层类名
 */
class Cascader extends FormComponent
{
    /**
     * 省市区三级联动数据
     */
    const TYPE_CITY_AREA = 'city_area';

    /**
     * 省市二级联动数据
     */
    const TYPE_CITY = 'city';

    /**
     * 自定义数据
     */
    const TYPE_OTHER = 'other';


    protected $defaultValue = [];

    protected $selectComponent = true;

    protected $defaultProps = [
        'type' => self::TYPE_OTHER,
        'options' => []
    ];

    /**
     * @var array
     */
    protected static $propsRule = [
        'type' => 'string',
        'props' => 'array',
        'size' => 'string',
        'placeholder' => 'string',
        'disabled' => 'bool',
        'clearable' => 'bool',
        'showAllLevels' => 'bool',
        'collapseTags' => 'bool',
        'separator' => 'string',
        'filterable' => 'bool',
        'debounce' => 'float',
        'popperClass' => 'string',
    ];

    /**
     * @param mixed $value
     * @return $this
     */
    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * 可选项的数据源
     * 例如:{
     *    "value":"北京市", "label":"北京市", "children":[{
     *        "value":"东城区", "label":"东城区"
     *    }]
     *  }
     *
     * @param array|Collection $data
     * @return $this
     */
    public function options($data): self
    {
        if ($data instanceof Collection) {
            $data = $data->toTree();
        }

        $this->props['options'] = $data;

        return $this;
    }

    /**
     * @param string $var
     * @return $this
     */
    public function jsOptions(string $var): self
    {
        $this->props['options'] = 'js.' . $var;

        return $this;
    }

    public function createValidate()
    {
        return ValidateFactory::validateArr();
    }
}
