<?php

namespace LaracraftTech\LaravelDynamicModel;

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
    public function create(string $fqnClass, string $dynamicTableName, ?string $dynamicConnectionName = null): Model&DynamicModelInterface
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
            return new $dynamicFQNClass;
        });

        return app($dynamicFQNClass);
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

        $schema = Schema::connection($this->dynamicConnectionName);

        if (! $schema->hasTable($this->dynamicTableName)) {
            throw DynamicModelException::tableDoesNotExist($this->dynamicTableName);
        }

        $indexes = $schema->getIndexes($this->dynamicTableName);

        $primaryKeyName = null;
        foreach ($indexes as $index) {
            if ($index['primary']) {
                $primaryKeyName = $index['columns'][0];
                break;
            }
        }

        if (! $primaryKeyName) {
            throw DynamicModelException::primaryKeyDoesNotExist();
        }

        $columns = $schema->getColumns($this->dynamicTableName);
        $primaryColumn = null;
        foreach ($columns as $column) {
            if ($column['name'] === $primaryKeyName) {
                $primaryColumn = $column;
                break;
            }
        }

        return [
            'primaryKey' => $primaryColumn['name'],
            'keyType' => Str::of($primaryColumn['type_name'])->contains('int') ? 'int' : 'string',
            'incrementing' => $primaryColumn['auto_increment'] ? 'true' : 'false',
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
