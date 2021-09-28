<?php
namespace Catcher\Support\Form\Actions\Relation;

use Catcher\Exceptions\FailedException;

trait StoreRelation
{
    /**
     * belongsToMany Relation
     *
     * @time 2021年08月24日
     * @return void
     */
    protected function belongsToMany($relations)
    {
        foreach ($relations as $relation) {
            if (isset($this->form[$relation])) {
                if (method_exists($this->model, $relation)) {
                    $this->model->{$relation}()->attach($this->form[$relation]);
                } else {
                    throw new FailedException(sprintf('Did You Set BelongsToMany [%s] Relation ?', $relation));
                }
            }
        }
    }


    /**
     * deal with hasMany Relation
     *
     * @time 2021年08月24日
     * @return void
     */
    protected function hasMany($relations)
    {
        foreach ($relations as $relation) {
            if (isset($this->form[$relation])) {
                if (method_exists($this->model, $relation)) {
                    $relateModel = $this->model->{$relation}()->getRelated();

                    $models = [];
                    foreach ($this->form[$relation] as $d) {
                        $models[] = new $relateModel($d);
                    }

                    $this->model->{$relation}()->saveMany($models);
                } else {
                    throw new FailedException(sprintf('Did You Set HasMany [%s] Relation ?', $relation));
                }
            }
        }
    }

    /**
     * deal with hasOne Relation
     *
     * @time 2021年08月24日
     * @return void
     */
    protected function hasOne($relations)
    {
        foreach ($relations as $relation) {
            if (isset($data[$relation])) {
                if (method_exists($this->model, $relation)) {
                    $relateModel = $this->model->{$relation}()->getRelated();

                    $this->model->{$relation}()->save(new $relateModel($data[$relation]));
                } else {
                    throw new FailedException(sprintf('Did You Set HasOne [%s] Relation ?', $relation));
                }
            }
        }
    }
}
