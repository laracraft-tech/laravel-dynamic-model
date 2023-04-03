<?php

namespace LaracraftTech\LaravelDynamicModel;

interface DynamicModelInterface
{
    public static function setDynamicTableName(string $tableName): void;

    public static function setDynamicDBConnection(string $dbConnection): void;

    /**
     * Make sure the DynamicModel has a bindDynamic function.
     */
    public function bindDynamically(): void;
}
