<?php

/**
 * @codeCoverageIgnore
 */
Route::group(['middleware' => config('laravel-feature-flag.route_middleware')], function () {
    Route::get(
        'admin/feature_flags',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@getSettings',
            'as' => 'laravel-feature-flag.index'
        ]
    );
    Route::get(
        'admin/feature_flags/{feature}/edit',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@edit',
            'as' => 'laravel-feature-flag.edit_form'
        ]
    );
    Route::get(
        'admin/feature_flags/create',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@create',
            'as' => 'laravel-feature-flag.create_form'
        ]
    );
    Route::post(
        'admin/feature_flags/imports',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@import',
            'as' => 'laravel-feature-flag.imports'
        ]
    );
    Route::post(
        'admin/feature_flags',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@store',
            'as' => 'laravel-feature-flag.store'
        ]
    );
    Route::put(
        'admin/feature_flags/{feature}',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@update',
            'as' => 'laravel-feature-flag.update'
        ]
    );
    Route::delete(
        'admin/feature_flags/{feature}',
        [
            'uses' => '\FriendsOfCat\LaravelFeatureFlags\FeatureFlagSettingsController@destroy',
            'as' => 'laravel-feature-flag.delete'
        ]
    );
});
