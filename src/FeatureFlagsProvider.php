<?php

namespace AlfredNutileInc\LaravelFeatureFlags;


use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class FeatureFlagsProvider extends ServiceProvider {

    use FeatureFlagHelper;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();

        $this->registerViewFiles();

        $this->injectLinks();

        $this->registerFeatureFlags();

        $this->publishMigrations();

        $this->publishViews();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
       $this->registerViewFiles();
    }

    private function registerRoutes()
    {
      if (! $this->app->routesAreCached()) {
          require __DIR__ . '/routes.php';
      }
    }

    private function registerViewFiles()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'feature_flags');
    }

    private function injectLinks()
    {
            view()->composer(
                'layouts.default', function($view) {
                if ($view->offsetExists('links')) {
                    $links_original = $view->offsetGet('links');
                    $links = [
                        ['title' => 'Feature Flags', 'url' => route('feature_flags.index'), 'icon' => 'flag-o']
                    ];

                    $view->with('links', array_merge($links_original, $links));
                }
            }
            );

    }

    private function overRideGate()
    {
        $this->app->singleton(GateContract::class, function ($app) {
            return new GateOverride($app, function () use ($app) {
                return $app['auth']->user();
            });
        });

    }

    private function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    private function publishViews()
    {
        $this->publishes([
            __DIR__.'/../views/' => base_path('resources/views/vendor/laravel-feature-flag')
        ], 'views');
    }



}
