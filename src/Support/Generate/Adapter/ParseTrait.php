<?php

namespace Catcher\Support\Generate\Adapter;


trait ParseTrait
{
    protected function getNamespace(): string
    {
        return ucfirst(array_shift($this->origin));
    }

    protected function getModule(): string
    {
        return ucfirst(array_shift($this->origin));
    }

    protected function getSelfName()
    {
        return array_pop($this->origin);
    }
}
