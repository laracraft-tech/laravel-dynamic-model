<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use LaracraftTech\LaravelDynamicModel\DynamicModelBinding;
use LaracraftTech\LaravelDynamicModel\DynamicModelInterface;

class MyTraitedDynamicModel extends MyBaseModel implements DynamicModelInterface
{
    use DynamicModelBinding;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->bindDynamically();
    }
}
