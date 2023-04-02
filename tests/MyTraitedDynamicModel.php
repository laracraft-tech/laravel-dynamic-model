<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use LaracraftTech\LaravelDynamicModel\DynamicModelBinding;
use LaracraftTech\LaravelDynamicModel\DynamicModelInterface;

class MyTraitedDynamicModel extends MyBaseModel implements DynamicModelInterface
{
    use DynamicModelBinding;

    protected $guarded = [];
}
