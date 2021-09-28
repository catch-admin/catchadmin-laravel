<?php
namespace Catcher\Support\Form\Actions\Relation;

use Catcher\Exceptions\FailedException;

trait DestroyRelation
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
            if (method_exists($this->model, $relation)) {
                $this->model->{$relation}()->detach();
            } else {
                throw new FailedException(sprintf('Did You Set BelongsToMany [%s] Relation ?', $relation));
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
            if (method_exists($this->model, $relation)) {
                $this->model->{$relation}()->delete();
            } else {
                throw new FailedException(sprintf('Did You Set HasMany [%s] Relation ?', $relation));
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
            if (method_exists($this->model, $relation)) {
                $this->model->{$relation}()->delete();
            } else {
                throw new FailedException(sprintf('Did You Set HasOne [%s] Relation ?', $relation));
            }
        }
    }
}
