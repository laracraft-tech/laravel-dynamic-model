<?php

namespace LaracraftTech\LaravelDynamicModel;

interface DynamicModelInterface
{
    /**
     * Make sure the DynamicModel has a bindDynamic function.
     *
     * @param string $tableName
     * @param string|null $dbConnection
     */
    public function bindDynamically(string $tableName, string $dbConnection = null): void;
}
