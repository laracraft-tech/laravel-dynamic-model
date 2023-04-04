<?php

namespace LaracraftTech\LaravelDynamicModel;

interface DynamicModelInterface
{
    /**
     * Make sure the DynamicModel has a bindDynamic function.
     */
    public function bindDynamically(string $tableName, string $dbConnection = null): void;
}
