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

namespace Catcher\Support\Table\Traits;

trait Components
{
    /**
     * component
     *
     * @time 2021年03月24日
     * @param string $name
     * @param string $updateField
     * @param array $options
     * @return self
     */
    public function component(string $name, string $updateField = '', array $options = []): self
    {
        $this->attributes['component'][] = [
            'name' => $name,
            'field' => $updateField ? : $this->attributes['prop'],
            'options' => $options
        ];

        return $this;
    }

    /**
     * switch
     *
     * @time 2021年03月23日
     * @param array $options
     * @param null $updateFields
     * @return self
     */
    public function switch(array $options = [], $updateFields = null): self
    {
        return $this->component('switch_', $updateFields ? : $this->attributes['prop'], $options);
    }

    /**
     * edit
     *
     * @time 2021年03月23日
     * @param null $updateFields
     * @return self
     */
    public function edit($updateFields = null):  self
    {
        return $this->component('edit', $updateFields ? : $this->attributes['prop']);
    }

    /**
     * Edit Number
     *
     * @time 2021年03月23日
     * @param null $updateFields
     * @return self
     */
    public function editNumber($updateFields = null): self
    {
        return $this->component('editNumber', $updateFields ? : $this->attributes['prop']);
    }


    /**
     * 多选组件
     *
     * @time 2021年05月03日
     * @param array $options
     * @param null $updateFields
     * @return self
     */
    public function select(array $options, $updateFields = null): self
    {
        return $this->component('select_', $updateFields ? : $this->attributes['prop'], $options);
    }

    /**
     * 预览组件
     *
     * @time 2021年05月03日
     * @param null $field
     * @return self
     */
    public function preview($field = null): self
    {
        return $this->component('preview', $field ? : $this->attributes['prop']);
    }

    /**
     * 链接跳转
     *
     * @time 2021年05月09日
     * @param null $field
     * @return self
     */
    public function url($field = null): self
    {
        return $this->component('url', $field ? : $this->attributes['prop']);
    }

    /**
     * 复制组件
     *
     * @time 2021年05月12日
     * @param null $field
     * @return self
     */
    public function copy($field = null): self
    {
        return $this->component('copy', $field ? : $this->attributes['prop']);
    }

    /**
     * download 组件
     *
     * @time 2021年05月05日
     * @param null $field
     * @return self
     */
    public function download($field = null): self
    {
        return $this->component('download', $field ? : $this->attributes['prop']);
    }
}
