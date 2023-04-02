<?php

namespace LaracraftTech\LaravelDynamicModel;

use Exception;
use Illuminate\Support\Facades\Schema;

trait DynamicModelBinding
{
    public function bindDynamically(string $tableName, string $dbConnection = null): void
    {
        if (!Schema::hasTable($tableName)) {
            throw DynamicModelException::tableDoesNotExist($tableName);
        }

        // change connection, if desired
        if ($dbConnection) $this->setConnection($dbConnection);

        // set the table for the dynamic model
        $this->setTable($tableName);

        // apply primary key, incrementing and key type
        $connection = Schema::getConnection();

        $table = $connection->getDoctrineSchemaManager()->listTableDetails($tableName);

        if (!$primaryKey = $table->getPrimaryKey()) {
            throw DynamicModelException::primaryKeyDoesNotExist();
        }

        $primaryKeyName = $primaryKey->getColumns()[0];
        $primaryColumn = $connection->getDoctrineColumn($tableName, $primaryKeyName);

        $this->primaryKey = $primaryColumn->getName();
        $this->incrementing = $primaryColumn->getAutoincrement();
        $this->keyType = ($primaryColumn->getType()->getName() === 'string') ? 'string' : 'integer';
    }
}
