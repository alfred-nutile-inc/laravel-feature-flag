<?php
Route::get('admin/feature_flags/example', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwtterField', 'as' => 'laravel-feature-flag.example']);
