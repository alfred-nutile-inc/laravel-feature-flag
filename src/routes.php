<?php


Route::get('admin/feature_flags', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@getSettings', 'as' => 'feature_flags.index']);
Route::get('admin/feature_flags/{feature}/edit', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@edit', 'as' => 'feature_flags.edit_form']);
Route::get('admin/feature_flags/create', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@create', 'as' => 'feature_flags.create_form']);
Route::post('admin/feature_flags', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@store', 'as' => 'feature_flags.store']);
Route::put('admin/feature_flags/{feature}', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@update', 'as' => 'feature_flags.update']);
Route::delete('admin/feature_flags/{feature}', ['uses' => '\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@destroy', 'as' => 'feature_flags.delete']);