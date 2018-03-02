<?php

namespace Tests;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Mockery;
use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return
            [
            FeatureFlagsProvider::class,
        ];
    }


    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');

    }


    protected function getEnvironmentSetUp($app)
    {
        $app->configureMonologUsing(function ($monolog) {
            $path = __DIR__ . "/logs/laravel.log";

            $handler = $handler = new StreamHandler($path, 'debug');

            $handler->setFormatter(tap(new LineFormatter(null, null, true, true), function ($formatter) {
                /** @var LineFormatter $formatter */
                $formatter->includeStacktraces();
            }));

            /** @var \Monolog\Logger $monolog */
            $monolog->pushHandler($handler);
        });

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', env('DB_CONNECTION', 'mysql'));

        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', "test"),
            'username' => env('DB_USERNAME', "root"),
            'password' => env('DB_PASSWORD', ""),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ]);

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $app['config']->set('laravel-feature-flag.logging', true);
    }
}
