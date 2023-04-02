<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use Illuminate\Database\Eloquent\Model;

class MyBaseModel extends Model
{
    public function doSomethingBase()
    {
        return 'bar';
    }
}
