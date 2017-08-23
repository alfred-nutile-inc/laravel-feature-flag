<?php

/**
 * @codeCoverageIgnore
 */
Route::get(
    'admin/feature_flags/example',
    [
        'uses' => '\AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwitterField',
        'as' => 'laravel-feature-flag.example'
    ]
);
