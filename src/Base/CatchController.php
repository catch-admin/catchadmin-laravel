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

declare(strict_types=1);

namespace Catcher\Base;

use Catcher\Support\Form\CatchForm;
use Catcher\Support\Table\CatchTable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;

abstract class CatchController extends Controller
{
    /**
     * @var object
     */
    public $builder;

    /**
     * @var CatchTable
     */
    protected $table;

    /**
     * @var CatchForm
     */
    protected $form;

    public function __construct()
    {
        $this->form = $this->builder->getForm();

        $this->table = $this->builder->getTable();
    }

    /**
     * index
     *
     * @time 2021年08月11日
     * @return array
     */
    public function index(): array
    {
        $list = $this->table->index();

        return $list instanceof LengthAwarePaginator ? CatchResponse::paginate($list) : CatchResponse::success($list);
    }

    /**
     * show
     *
     * @time 2021年08月11日
     * @return void
     */
    public function show($id)
    {

    }

    /**
     * store
     *
     * @time 2021年08月11日
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        return CatchResponse::success($this->form->setData($request->all())->store());
    }


    /**
     * update
     *
     * @time 2021年08月11日
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id, Request $request): array
    {
        return CatchResponse::success(
            $this->form->setCondition([
                $this->form->getModel()->getKeyName() => $id
            ])->setData($request->all())->update()
        );
    }


    /**
     * destroy
     *
     * @time 2021年08月11日
     * @param $id
     * @return array
     */
    public function destroy($id): array
    {
        $ids = explode(',', $id);

        foreach ($ids as $id) {
            $this->form->setCondition([
                $this->form->getModel()->getKeyName() => $id
            ])->destroy();
        }

        return CatchResponse::success(true);
    }
}
