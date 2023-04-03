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
        /** @var $concreteClassName Model&DynamicModelInterface */
        if (! method_exists($concreteClassName, 'bindDynamically')) {
            throw DynamicModelException::bindFuncDoesNotExist($concreteClassName);
        }

        $concreteClassName::setDynamicTableName($tableName);
        if ($dbConnection) {
            $concreteClassName::setDynamicDBConnection($dbConnection);
        }

        return new $concreteClassName();
    }
}
