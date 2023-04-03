<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Support\Facades\Schema;

trait DynamicModelBinding
{
    private static string $dynamicTableName;

    private static ?string $dynamicDBConnection = null;

    public static function setDynamicTableName(string $tableName): void
    {
        self::$dynamicTableName = $tableName;
    }

    public static function setDynamicDBConnection(string $dbConnection): void
    {
        self::$dynamicDBConnection = $dbConnection;
    }

    public function bindDynamically(): void
    {
        // change connection, if desired
        if (self::$dynamicDBConnection) {
            $this->setConnection(self::$dynamicDBConnection);
        }

        if (! Schema::hasTable(self::$dynamicTableName)) {
            throw DynamicModelException::tableDoesNotExist(self::$dynamicTableName);
        }

        // set the table for the dynamic model
        $this->setTable(self::$dynamicTableName);

        // apply primary key, incrementing and key type
        $connection = Schema::getConnection();

        $table = $connection->getDoctrineSchemaManager()->listTableDetails(self::$dynamicTableName);

        if (! $primaryKey = $table->getPrimaryKey()) {
            throw DynamicModelException::primaryKeyDoesNotExist();
        }

        $primaryKeyName = $primaryKey->getColumns()[0];
        $primaryColumn = $connection->getDoctrineColumn(self::$dynamicTableName, $primaryKeyName);

        $this->primaryKey = $primaryColumn->getName();
        $this->incrementing = $primaryColumn->getAutoincrement();
        $this->keyType = ($primaryColumn->getType()->getName() === 'string') ? 'string' : 'integer';
    }
}
