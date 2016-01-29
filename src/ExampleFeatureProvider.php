<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class ExampleFeatureProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {

        $this->registerPolicies($gate);

        $gate->define('add-twitter-field', '\AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureFlagLogic@addTwitterField');

        $gate->define('see-twitter-field', '\AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureFlagLogic@seeTwitterField');

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        $this->loadViewsFrom(__DIR__.'/../views', 'twitter');

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