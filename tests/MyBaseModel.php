<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use Illuminate\Database\Eloquent\Model;
use LaracraftTech\LaravelDynamicModel\DynamicModelInterface;

class MyBaseModel extends Model implements DynamicModelInterface
{
    public function doSomethingBase()
    {
        return 'bar';
    }
}
