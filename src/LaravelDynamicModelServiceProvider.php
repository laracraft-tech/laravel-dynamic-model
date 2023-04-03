<?php

namespace LaracraftTech\LaravelDynamicModel;

use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDynamicModelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('laravel-dynamic-model');
    }

    /**
     * @throws InvalidPackage
     */
    public function register()
    {
        parent::register();

        $this->app->bind(DynamicModel::class, function ($app, $parameters = []) {
            if (! isset($parameters['table_name'])) {
                throw new \Exception('please provide table_name parameter');
            }

            return app(DynamicModelFactory::class)
                ->create(
                    DynamicModel::class,
                    $parameters['table_name'],
                    $parameters['db_connection'] ?? null
                );
        });
    }
}
