<?php

namespace LaracraftTech\LaravelDynamicModel;

use Doctrine\DBAL\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

class DynamicModelFactory
{
    private string $dynamicTableName;

    private string $dynamicConnectionName;

    /**
     * @throws ReflectionException
     */
    public function create(string $fqnClass, string $dynamicTableName, string $dynamicConnectionName = null): Model&DynamicModelInterface
    {
        $this->dynamicTableName = $dynamicTableName;
        $this->dynamicConnectionName = $dynamicConnectionName ?? config('database.default');

        $dynamicFQNClass = $this->getDynamicClass($fqnClass);

        if (app()->has($dynamicFQNClass)) {
            return app($dynamicFQNClass);
        }

        if (! class_exists($dynamicFQNClass)) {
            $this->createDynamicClass($fqnClass);
        }

        app()->bind($dynamicFQNClass, function () use ($dynamicFQNClass) {
            return new $dynamicFQNClass();
        });

        return app($dynamicFQNClass);
    }

    private function getDynamicClass(string $class): string
    {
        return "{$class}_{$this->dynamicConnectionName}_{$this->dynamicTableName}";
    }

    /**
     * @throws Exception
     */
    private function getDynamicTableValues(): array
    {
        if (! isset(config('database.connections')[$this->dynamicConnectionName])) {
            throw DynamicModelException::connectionDoesNotExist($this->dynamicConnectionName);
        }

        $currentDBConnection = Schema::getConnection()->getName();

        $connection = Schema::getConnection();
        if ($currentDBConnection !== $this->dynamicConnectionName) {
            $connection = Schema::connection($this->dynamicConnectionName)->getConnection();
        }

        if (! $connection->getSchemaBuilder()->hasTable($this->dynamicTableName)) {
            throw DynamicModelException::tableDoesNotExist($this->dynamicTableName);
        }

        $table = $connection->getDoctrineSchemaManager()->introspectTable($this->dynamicTableName);

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

    /**
     * @throws ReflectionException
     */
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
