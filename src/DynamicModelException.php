<?php

namespace LaracraftTech\LaravelDynamicModel;

use InvalidArgumentException;

class DynamicModelException extends InvalidArgumentException
{
    public static function tableDoesNotExist(string $tableName): static
    {
        return new static("The table '$tableName' you provided to the dynamic model does not exists! Please create it first!");
    }

    public static function primaryKeyDoesNotExist(): static
    {
        return new static("The table you provided to the dynamic model has no primary key set! Please create it first!");
    }

    public static function bindFuncDoesNotExist(string $model): static
    {
        return new static("Cant bind the model '$model' to the table! Make sure it extends the DynamicModel class or uses the DynamicModelBinding trait!");
    }
}
