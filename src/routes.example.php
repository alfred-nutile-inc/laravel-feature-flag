<?php

/**
 * @codeCoverageIgnore
 */
Route::get(
    'admin/feature_flags/example',
    [
        'uses' => '\FriendsOfCat\LaravelFeatureFlags\ExampleController@seeTwitterField',
        'as' => 'laravel-feature-flag.example'
    ]
);
