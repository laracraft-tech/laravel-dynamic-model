<?php

namespace LaracraftTech\LaravelDynamicModel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaracraftTech\LaravelDynamicModel\LaravelDynamicModelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LaracraftTech\\LaravelDynamicModel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDynamicModelServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-dynamic-model_table.php.stub';
        $migration->up();
        */
    }
}
