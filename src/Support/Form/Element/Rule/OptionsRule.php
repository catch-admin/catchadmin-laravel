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

use Catcher\Exceptions\FailedException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

trait OptionsRule
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * 添加选项
     *
     * @param string $value
     * @param string $label
     * @param bool $disabled
     * @return $this
     */
    public function appendOption(string $value, string $label, bool $disabled): self
    {
        $this->options[] = compact('value', 'label', 'disabled');

        return $this;
    }

    /**
     * 批量添加选项
     *
     * @param array $options
     * @return $this
     */
    public function appendOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * 批量设置的选项
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     *
     * @param array|Collection $options
     * @return $this
     */
    public function options($options): self
    {
        if (is_array($options)) {
            $options = collect($options);
        }

        if ($options instanceof Collection) {
            return $this->setOptions($options->toOptions()->toArray());
        }

        throw new FailedException('Options Type Not Support');
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param $option
     * @return array
     */
    protected function parseOption($option): array
    {
        if ( ! is_object($option)) {
            return $option;
        }

        return $option->getOption();
    }

    /**
     * @return array
     */
    protected function parseOptions(): array
    {
        $options = [];

        foreach ($this->options as $option) {
            $options[] = $this->parseOption($option);
        }

        return $options;
    }

    public function parseOptionsRule(): array
    {
        return ['options' => $this->parseOptions()];
    }
}
