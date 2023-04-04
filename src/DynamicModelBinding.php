<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Support\Facades\Schema;

trait DynamicModelBinding
{
    public function bindDynamically(string $tableName, string $dbConnection = null): void
    {
        // first change connection, if desired
        if ($dbConnection) {
            $this->setConnection($dbConnection);
        }

        // set the table for the dynamic model
        $this->setTable($tableName);

        if (! Schema::hasTable($this->table)) {
            throw DynamicModelException::tableDoesNotExist($this->table);
        }

        // apply primary key, incrementing and key type
        $connection = Schema::getConnection();

        $table = $connection->getDoctrineSchemaManager()->listTableDetails($this->table);

        if (! $primaryKey = $table->getPrimaryKey()) {
            throw DynamicModelException::primaryKeyDoesNotExist();
        }

        $primaryKeyName = $primaryKey->getColumns()[0];
        $primaryColumn = $connection->getDoctrineColumn($this->table, $primaryKeyName);

        $this->primaryKey = $primaryColumn->getName();
        $this->incrementing = $primaryColumn->getAutoincrement();
        $this->keyType = ($primaryColumn->getType()->getName() === 'string') ? 'string' : 'integer';
    }
}
