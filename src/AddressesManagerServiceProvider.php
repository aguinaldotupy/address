<?php

namespace Tupy\AddressesManager;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AddressesManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Filesystem $filesystem)
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'addressesmanager');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'addressesmanager');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('addresses-manager.php'),
            ], 'addresses-manager-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/create_tables_addresses_manager.stub' => $this->getMigrationFileName($filesystem)
            ], 'addresses-manager-migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'addresses-manager');

        // Register the main class to use with the facade
        $this->app->singleton('addressesmanager', function () {
            return new AddressesManager;
        });
    }

    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_tables_addresses_manager.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_tables_addresses_manager.php")
            ->first();
    }
}
