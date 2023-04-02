<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Database\Eloquent\Model;

class DynamicModelFactory
{
    /**
     * Create the Dynamic Model Instance and make sure the one provided/created,
     * is based on Eloquent and implements the DynamicModelInterface
     */
    public function create(string $concreteClassName, string $tableName, string $dbConnection = null): Model&DynamicModelInterface
    {
        /** @var $dynamicModel Model & DynamicModelInterface */
        $dynamicModel = new $concreteClassName();

        if (! method_exists($dynamicModel, 'bindDynamically')) {
            throw DynamicModelException::bindFuncDoesNotExist($concreteClassName);
        }

        $dynamicModel->bindDynamically($tableName, $dbConnection);

        return $dynamicModel;
    }
}
