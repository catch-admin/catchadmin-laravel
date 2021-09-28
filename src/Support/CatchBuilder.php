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

namespace Catcher\Support;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Form\CatchForm as Form;
use Catcher\Support\Table\CatchTable as Table;

/**
 * CatchBuilder
 *
 * @method beforeSave(Form $form);
 * @method beforeUpdate(Form $form);
 * @method beforeDestroy(Form $form);
 * @method afterSave(Form $form);
 * @method afterUpdate(Form $form);
 * @method afterDestroy(Form $form);
 *
 * @time 2021年08月19日
 */
abstract class CatchBuilder
{
    protected $model;


    protected $creatorId = true;

    /**
     * form fields
     *
     * @time 2021年08月19日
     * @return array
     */
    abstract protected function fields(): array;

    /**
     * table builder
     *
     * @time 2021年08月19日
     * @return mixed
     */
    abstract protected function table(Table $table);

    /**
     * invoke
     *
     * @time 2021年08月19日
     * @return array
     */
    public function __invoke(): array
    {
        if (! $this->model) {
            throw new FailedException('Builder Must Set Model');
        }

        $builder = [
            'form' => $this->getForm(),

            'table' => $this->getTable(),
        ];

        // only form | table
        $only = request('only');
        if ($only) {
            return [
                $only => $builder[$only]->create()
            ];
        }

        // TODO: Implement __invoke() method.
        return [
            'form' => $builder['form']->create(),

            'table' => $builder['table']->create()
        ];
    }

    /**
     * form
     *
     * @time 2021年08月19日
     * @return Form
     */
    public function getForm(): Form
    {
        $form = new Form;

        return $form->creating(function (){

            $fields = $this->fields();

            $fields[] = Form::hidden(app($this->model)->getKeyName());

            return $fields;

        }, $this->model)
        // form hook
        ->when(method_exists($this, 'beforeSave'), function (Form $form){
            $form->beforeSave(function ($form){
                return $this->beforeSave($form);
            });
        })
        ->when(method_exists($this, 'beforeUpdate'), function (Form $form){
            $form->beforeUpdate(function ($form){
                return $this->beforeUpdate($form);
            });
        })
        ->when(method_exists($this, 'beforeDestroy'), function (Form $form){
            $form->beforeDestroy(function ($form){
                return $this->beforeDestroy($form);
            });
        })
        ->when(method_exists($this, 'afterSave'), function (Form $form){
            $form->afterSave(function ($form){
                return $this->afterSave($form);
            });
        })
        ->when(method_exists($this, 'afterUpdate'), function (Form $form){
            $form->afterUpdate(function ($form){
               return $this->afterUpdate($form);
            });
        })
        ->when(method_exists($this, 'afterDestroy'), function (Form $form){
            $form->afterDestroy(function ($form){
                return $this->afterDestroy($form);
            });
        })
        ->when(! $this->creatorId, function (Form $form){
            $form->dontWriteCreatorId();
        });
    }

    /**
     * table
     *
     * @time 2021年08月19日
     * @return Table
     */
    public function getTable(): Table
    {
        $table = new Table;

        return $table->creating(class_basename($this), function (Table $table) {
            $this->table($table);
        }, $this->model);
    }


    /**
     * export
     *
     * @time 2021年09月18日
     * @return mixed
     */
    public function export()
    {
        return [
            'url' => $this->getTable()->exportTo()
        ];
    }
}
