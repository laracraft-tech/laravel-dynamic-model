<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Database\Eloquent\Model;

class DynamicModel extends Model implements DynamicModelInterface
{
    /**
     * The standard DynamicModel is not guarded,
     * feel free to create your own dynamic model by using the BindsDynamically trait
     * and implementing the DynamicModelInterface!
     */
    protected $guarded = [];
}
