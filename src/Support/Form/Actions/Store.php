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
use Catcher\Support\Form\Actions\Relation\StoreRelation;

class Store extends Action
{
    use StoreRelation;

    protected $storeMethod = 'storeBy';

    /**
     *
     * @time 2021年08月24日
     * @return mixed
     */
    public function deal()
    {
        // store data
        if (method_exists($this->model, $this->storeMethod)) {
            $res = $this->model->storeBy($this->form);
        } else {
            $fillable = $this->model->getFillable();

            foreach ($this->form as $field => $value) {
                if (in_array($field, $fillable) && isset($data[$field])) {
                    $this->model->{$field} = $value;
                }
            }

            $res = $this->model->save();
        }

        if (! $res) {
            throw new FailedException('Form Saved Failed');
        }

        return $this->model->getKey();
    }
}
