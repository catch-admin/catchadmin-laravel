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

namespace Catcher\Support\Form\Actions;

use Catcher\Exceptions\FailedException;
use Catcher\Support\Form\CatchForm;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Action
 *
 * @time 2021年08月19日
 */
abstract class Action
{
    /**
     * form's model
     *
     * @var Model
     */
    protected $model;

    /**
     * @var CatchForm
     */
    protected $form;


    /**
     * @var array
     */
    protected $fields;


    /**
     * @param CatchForm $form
     * @throws BindingResolutionException
     */
    public function __construct(CatchForm $form)
    {
        $this->form = $form;

        $this->model = $form->getModel();

        $this->fields = $form->create();
    }

    /*
     *
     * @time 2021年08月19日
     * @return mixed
     */
     public function run()
     {
         try {
             DB::beginTransaction();

             // deal form data
             $res = $this->deal();

             // deal with relations
             $this->dealWithRelation();

             DB::commit();
             return $res;
         } catch (\Exception $exception) {
            DB::rollBack();
            throw new FailedException($exception->getMessage());
         }
     }


    /**
     * deal
     *
     * @time 2021年08月24日
     * @return mixed
     */
     abstract protected function deal();

    /**
     * get relations
     *
     * @time 2021年08月11日
     * @return array
     */
    protected function getRelations(): array
    {
        $relations = [];

        foreach ($this->fields as $field) {
            if (isset($field['attrs']['relation'])) {
                $relations[$field['attrs']['relation']][] = $field['attrs']['as'] ?? $field['field'];
            }

            // 查找 control 里面
            if (isset($field['control']) && is_array($field['control'])) {
                foreach ($field['control'] as $control) {
                    if (isset($control['rule']) && is_array($control['rule'])) {
                        foreach ($control['rule'] as $rule) {
                            if (isset($rule['attrs']['relation'])) {
                                $relations[$rule['attrs']['relation']][] = $rule['attrs']['as'] ?? $rule['field'];
                            }
                        }
                    }
                }
            }
        }

        return $relations;
    }

    /**
     * deal with relation
     *
     * @time 2021年08月24日
     * @return void
     */
    protected function dealWithRelation()
    {
        foreach ($this->getRelations() as $relation => $fields) {
            $this->{$relation}($fields);
        }
    }
}
