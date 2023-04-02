<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use LaracraftTech\LaravelDynamicModel\DynamicModel;

class MyExtendedDynamicModel extends DynamicModel
{
    public function doSomething()
    {
        return 'foo';
    }
}
