<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

class FeatureFlagsForJavascript
{
<<<<<<< HEAD
=======

>>>>>>> fix style work
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
