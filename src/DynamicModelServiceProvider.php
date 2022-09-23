<?php

namespace LaracraftTech\LaravelDynamicModel;

use Illuminate\Support\ServiceProvider;

class DynamicModelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laracraft-tech');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laracraft-tech');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'dynamic-model');

        // Register the service the package provides.
        $this->app->bind(DynamicModel::class, function ($app, $parameters = []) {
            if (!isset($parameters['table_name'])) {
                throw new \Exception('please provide table_name parameter');
            }

            config()->set('dynamic-model.current_table', $parameters['table_name']);

            return new DynamicModel();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-dynamic-model'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('dynamic-model.php'),
        ], 'config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/laracraft-tech'),
        ], 'laravel-dynamic-model.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/laracraft-tech'),
        ], 'laravel-dynamic-model.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/laracraft-tech'),
        ], 'laravel-dynamic-model.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
