<?php

namespace LaracraftTech\LaravelDynamicModel;

use Exception;
use Illuminate\Database\Eloquent\Model;

class DynamicModelFactory
{
    /**
     * Create the Dynamic Model Instance and make sure the one provided/created,
     * is based on Eloquent and implements the DynamicModelInterface
     *
     * @param string $concreteClassName
     * @param string $tableName
     * @param string|null $dbConnection
     * @return Model & DynamicModelInterface
     */
    public function create(string $concreteClassName, string $tableName, string $dbConnection = null): Model & DynamicModelInterface
    {
        /** @var $dynamicModel Model & DynamicModelInterface */
        $dynamicModel = new $concreteClassName();

        if (!method_exists($dynamicModel, 'bindDynamically')) {
            throw DynamicModelException::bindFuncDoesNotExist($concreteClassName);
        }

        $dynamicModel->bindDynamically($tableName, $dbConnection);

        return $dynamicModel;
    }
}
