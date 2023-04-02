<?php

namespace LaracraftTech\LaravelDynamicModel;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DynamicModel extends Model implements DynamicModelInterface
{
    use DynamicModelBinding;

    /**
     * The standard DynamicModel is not guarded,
     * feel free to create your own dynamic model by using the BindsDynamically trait
     * and implementing the DynamicModelInterface!
     *
     * @var array
     */
    protected $guarded = [];
}
