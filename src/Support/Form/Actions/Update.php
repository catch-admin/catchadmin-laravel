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
use Catcher\Support\Form\Actions\Relation\UpdateRelation;

class Update extends Action
{
    use UpdateRelation;

    /**
     * deal form data
     *
     * @time 2021年08月24日
     * @return bool
     */
    public function deal(): bool
    {
        // TODO: Implement run() method.
        $this->model = $this->model->where($this->form->getCondition())
            ->firstOr(function (){
                throw new FailedException('Data Is Not Exist');
            });

        $fillAble = $this->model->getFillable();

        foreach ($this->form as $field => $value) {
            if (in_array($field, $fillAble) && $value) {
                $this->model->{$field} = $value;
            }
        }

        if (! $this->model->save()) {
            throw new FailedException('Update Failed');
        }

        return true;
    }
}
