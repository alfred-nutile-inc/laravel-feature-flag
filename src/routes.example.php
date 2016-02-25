<?php
Route::get('admin/feature_flags/example', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwtterField', 'as' => 'feature_flags.example']);
