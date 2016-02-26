<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Support\ServiceProvider;

class ExampleFeatureProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views/twitter-example', 'twitter-example');

        $this->publishMigrations();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }

    private function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations_example/' => database_path('migrations')
        ], 'migrations');
    }
}
