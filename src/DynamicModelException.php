<?php

namespace LaracraftTech\LaravelDynamicModel;

use InvalidArgumentException;

final class DynamicModelException extends InvalidArgumentException
{
    public static function connectionDoesNotExist(string $connection): static
    {
        return new self("The database connection '$connection' you provided to the dynamic model does not exists! Please add it into your config/database.php!");
    }

    public static function tableDoesNotExist(string $table): static
    {
        return new self("The table '$table' you provided to the dynamic model does not exists! Please create it first!");
    }

    public static function primaryKeyDoesNotExist(): static
    {
        return new static('The table you provided to the dynamic model has no primary key set! Please create it first!');
    }
}
