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
    public function packageRegistered(): void
    {
        //
    }
}
