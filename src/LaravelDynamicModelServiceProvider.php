<?php

namespace LaracraftTech\LaravelDynamicModel;

use Spatie\LaravelPackageTools\Exceptions\InvalidPackage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDynamicModelServiceProvider extends PackageServiceProvider
{
    protected array $boundClassesWithParams = [];

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
    public function packageRegistered(): void
    {
        $this->app->beforeResolving(DynamicModelInterface::class, function ($class, $parameters, $app) {
            // if empty param do nothing before resolving,
            // just try to resolve already bound class...
            if (empty($parameters)) {
                return;
            }

            $paramHash = hash('sha256', json_encode($parameters));

            // if the class is already bound with the same parameters
            // just try to resolve already bound class...
            if ($app->has($class) && $this->boundClassesWithParams[$class] === $paramHash) {
                return;
            }

            $app->bind($class, function ($container) use ($class, $parameters, $paramHash) {
                $instance = new $class;
                $instance->bindDynamically(...$parameters);
                $this->boundClassesWithParams[$class] = $paramHash;

                return $instance;
            });
        });
    }
}
