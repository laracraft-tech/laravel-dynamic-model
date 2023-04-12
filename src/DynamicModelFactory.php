<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class DynamicModelFactory
{
    private string $dynamicTableName;

    private string $dynamicConnectionName;

    public function create(string $fqnClass, string $dynamicTableName, string $dynamicConnectionName = null): Model&DynamicModelInterface
    {
        $this->dynamicTableName = $dynamicTableName;
        $this->dynamicConnectionName = $dynamicConnectionName ?? config('database.default');

        $dynamicFQNClass = $this->getDynamicClass($fqnClass);

        $this->createDynamicClass($fqnClass);

        return new $dynamicFQNClass();
    }

    private function getDynamicClass(string $class): string
    {
        return "{$class}_{$this->dynamicConnectionName}_{$this->dynamicTableName}";
    }

    private function getDynamicTableValues(): array
    {
        if (! isset(config('database.connections')[$this->dynamicConnectionName])) {
            throw DynamicModelException::connectionDoesNotExist($this->dynamicConnectionName);
        }

        $currentDBConnection = Schema::getConnection()->getName();

        if ($currentDBConnection !== $this->dynamicConnectionName) {
            Schema::connection($this->dynamicConnectionName);
        }

        if (! Schema::hasTable($this->dynamicTableName)) {
            throw DynamicModelException::tableDoesNotExist($this->dynamicTableName);
        }

        // get primary key, incrementing and key type
        $connection = Schema::getConnection();

        $table = $connection->getDoctrineSchemaManager()->listTableDetails($this->dynamicTableName);

        if (! $primaryKey = $table->getPrimaryKey()) {
            throw DynamicModelException::primaryKeyDoesNotExist();
        }

        $primaryKeyName = $primaryKey->getColumns()[0];
        $primaryColumn = $connection->getDoctrineColumn($this->dynamicTableName, $primaryKeyName);

        // reset to old connection
        if ($currentDBConnection !== $this->dynamicConnectionName) {
            Schema::connection($currentDBConnection);
        }

        return [
            'primaryKey' => $primaryColumn->getName(),
            'keyType' => Str::of($primaryColumn->getType()->getName())->contains('int') ? 'int' : 'string',
            'incrementing' => $primaryColumn->getAutoincrement() ? 'true' : 'false',
        ];
    }

    private function createDynamicClass(string $fqnClass)
    {
        $dynamicTableValues = $this->getDynamicTableValues();

        $reflect = new ReflectionClass($fqnClass);
        $namespace = $reflect->getNamespaceName();
        $baseClass = $reflect->getShortName();
        $className = $this->getDynamicClass($baseClass);

        $classDeclaration = <<<CODE
            namespace $namespace;

            use $fqnClass;

            class $className extends $baseClass
            {
                protected \$connection = '$this->dynamicConnectionName';
                protected \$table = '$this->dynamicTableName';

                protected \$primaryKey = '{$dynamicTableValues['primaryKey']}';
                protected \$keyType = '{$dynamicTableValues['keyType']}';
                public \$incrementing = {$dynamicTableValues['incrementing']};
            }
        CODE;
        eval($classDeclaration);
    }
}
