<?php
<<<<<<< HEAD

/**
 * @codeCoverageIgnore
 */
Route::get(
    'admin/feature_flags/example',
    [
        'uses' => '\AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwitterField',
=======
Route::get(
    'admin/feature_flags/example',
    [
        'uses' => '\AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwtterField',
>>>>>>> fix style work
        'as' => 'laravel-feature-flag.example'
    ]
);
