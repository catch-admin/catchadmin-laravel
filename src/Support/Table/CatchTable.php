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

namespace Catcher\Support\Table;

use Catcher\Support\Table\Traits\Attributes;
use Catcher\Support\Table\Traits\Events;
use Catcher\Support\Table\Traits\Header;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Catcher\Support\Table\Traits\Actions;
use Catcher\Support\Table\Traits\Search;
use Closure;

class CatchTable
{
    use Events, Actions, Search, Attributes, Header;

    /**
     * tree table
     *
     * @var array
     */
    protected $tree = [];

    /**
     * @var string
     */
    protected $apiRoute;

    /**
     * @var string
     */
    protected $importRoute;

    /**
     * @var string
     */
    protected $exportRoute;

    /**
     * @var array
     */
    protected $excel = [];

    /**
     * table name
     *
     * @var string
     */
    protected $ref;

    /**
     * create
     *
     * @var \Closure
     */
    protected $create;


    /**
     * @var Model
     */
    protected $model;

    /**
     * creating
     *
     * @time 2021年08月12日
     * @param $name
     * @param Closure $closure
     * @param string $model
     * @return self
     */
    public function creating($name, Closure $closure, string $model): self
    {
        $this->ref = $name;

        $this->create = $closure;

        $this->model = $model;

        return $this;
    }


    /**
     * create table
     *
     * @time 2021年08月12日
     * @return array
     */
    public function create(): array
    {
        call_user_func($this->create, $this);

        return $this();
    }

    /**
     * table created
     *
     * @time 2021年08月13日
     * @return $this
     */
    public function created(): self
    {
        call_user_func($this->create, $this);

        return $this;
    }

    /**
     * get name
     *
     * @time 2021年08月12日
     * @return string
     */
    public function getName(): string
    {
        return $this->ref;
    }

    /**
     * excel 信息
     *
     * @time 2021年04月21日
     * @param array $excel
     * @return $this
     */
    public function excel(array $excel = []): self
    {
        $emptyArr = [];

        foreach ($excel as $e) {
            $this->excel[] = $e->render();
        }

        $this->excel = $emptyArr;

        return $this;
    }

    /**
     * 设置 api route
     *
     * @time 2021年03月29日
     * @param string $apiRoute
     * @return $this
     */
    public function apiRoute(string $apiRoute): self
    {
        $this->apiRoute = $apiRoute;

        return $this;
    }

    /**
     * 导出路由
     *
     * @time 2021年04月02日
     * @param string $route
     * @return $this
     */
    public function importRoute(string $route): self
    {
        $this->importRoute = $route;

        return $this;
    }


    /**
     * 导出路由
     *
     * @time 2021年04月02日
     * @param string $route
     * @return $this
     */
    public function exportRoute(string $route): self
    {
        $this->exportRoute = $route;

        return $this;
    }


    /**
     * export out
     *
     * @time 2021年09月18日
     * @return mixed
     */
    public function exportTo()
    {
        return (new Export($this))();
    }

    /**
     * 变成 tree table
     *
     * @time 2021年03月29日
     * @param string $rowKey
     * @param array $props ['children' => '', 'hasChildren' => '']
     * @return $this
     */
    public function toTreeTable(string $rowKey = 'id', array $props = []): self
    {
        $this->tree['row_key'] = $rowKey;

        $this->tree['props'] = count($props) ? $props : [
            'children' => 'children',
            'hasChildren' => 'hasChildren'
        ];

        return $this;
    }

    /**
     * render
     *
     * @time 2021年03月21日
     * @return array
     */
    public function __invoke(): array
    {
        $render = [];

        foreach (get_class_vars(self::class) as $property => $v) {
            if (! empty($this->{$property})) {
                $render[$property] = $this->{$property};
            }
        }

        $render['ref'] = $this->getName();

        return $render;
    }

    /**
     * get fields
     *
     * @time 2021年08月13日
     * @return array
     */
    public function getFields(): array
    {
        $fields = $relations = [];

        $headers = $this->created()->headers;

        foreach ($headers as $k => &$header) {
            if (isset($header['relations'])) {
                $relations[$header['prop']] = $header['relations'];
                unset($headers[$k]);
                continue;
            }

            if (isset($header['as'])) {
                $fields[] = sprintf('%s as %s', $header['prop'], $header['as']);

                $header['prop'] = $header['as'];

               unset($header['as']);
            } else {
                $ignore = $header['ignore'] ?? false;

                if (isset($header['prop']) && $header['prop'] && ! $ignore) {
                    $fields[] = $header['prop'];
                }
            }
        }

        $this->headers = $headers;

        return [$fields, $relations];
    }

    /**
     * append headers
     *
     * @time 2021年03月28日
     * @param $header
     * @return $this
     */
    public function appendHeaders($header): self
    {
        if ($header instanceof HeaderItem) {
            $this->headers[] = $header;
        }

        if (is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        }

        return $this;
    }


    /**
     * append header
     *
     * @time 2021年03月28日
     * @param array $header
     * @return $this
     */
    public function appendHeader(array $header): self
    {
        $this->headers = array_merge($this->headers, $header);

        return $this;
    }

    /**
     * get header
     *
     * @time 2021年03月29日
     * @return array
     */
    public function getHeader(): array
    {
        return $this->headers;
    }

    /**
     * get model
     *
     * @time 2021年08月13日
     * @return Application|Model|mixed
     */
    public function getModel()
    {
        if (is_string($this->model)) {
            $this->model = app($this->model);
        }

        return $this->model;
    }
}
