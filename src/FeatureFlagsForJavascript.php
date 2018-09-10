<?php

namespace FriendsOfCat\LaravelFeatureFlags;

class FeatureFlagsForJavascript
{
    public static function get()
    {
        $flags = FeatureFlag::all();

        $results = [];
        foreach ($flags as $feature_flag) {
            $results[$feature_flag->key] = \Feature\Feature::isEnabled($feature_flag->key);
        }

        return $results;
    }
}
