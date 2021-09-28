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

trait Attributes
{
    /**
     * 是否隐藏分页
     *
     * @var bool
     */
    protected $hidePagination = false;

    /**
     * @var bool
     */
    protected $loading = false;

    /**
     * @var array
     */
    protected $dialog;


    /**
     * @var bool
     */
    protected $forceUpdate = false;

    /**
     * 斑马纹
     *
     * @var bool
     */
    protected $stripe = false;

    /**
     * 树状展开
     *
     * @var bool
     */
    protected $defaultExpandAll;

    /**
     * 默认
     *
     * @var bool
     */
    protected $border = true;

    /**
     * bind table
     *
     * @var bool
     */
    protected $bind = false;

    /**
     * @var string
     */
    protected $tips = null;

    /**
     * 固定表头
     *
     * @var int
     */
    protected $height;

    /**
     * 隐藏分页
     *
     * @time 2021年03月29日
     * @return $this
     */
    public function hiddenPaginate(): self
    {
        $this->hidePagination = true;

        return $this;
    }

    /**
     * 展开
     *
     * @time 2021年04月26日
     * @param bool $expand
     * @return $this
     */
    public function expandAll(bool $expand = true): self
    {
        $this->defaultExpandAll = $expand;

        return $this;
    }

    /**
     * loading
     *
     * @time 2021年03月29日
     * @return $this
     */
    public function loading(): self
    {
        $this->loading = true;

        return $this;
    }


    /**
     * 设置弹出层的宽度
     *
     * @time 2021年03月29日
     * @param string $width
     * @return $this
     */
    public function dialogWidth(string $width): self
    {
        $this->dialog['width'] = $width;

        return $this;
    }

    /**
     * 开启斑马纹
     *
     * @time 2021年05月07日
     * @return $this
     */
    public function stripe(): self
    {
        $this->stripe = true;

        return $this;
    }

    /**
     * 固定表头
     *
     * @time 2021年05月07日
     * @param int $height
     * @return $this
     */
    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * 表格提示
     *
     * @time 2021年05月12日
     * @param string $content
     * @param string $type
     * @return $this
     */
    public function tips(string $content, string $type = 'success'): self
    {
        $this->tips = [
            'content' => $content,
            'type' => $type
        ];

        return $this;
    }

    /**
     * table 使用 v-bind
     *
     * @time 2021年04月27日
     * @return $this
     */
    public function bind(): self
    {
        $this->bind = true;

        return $this;
    }

    /**
     * 强制更新组件
     *
     * @time 2021年04月05日
     * @return $this
     */
    public function forceUpdate(): self
    {
        $this->forceUpdate = true;

        return $this;
    }
}
