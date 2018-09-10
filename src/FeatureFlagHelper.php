<?php

/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/28/16
 * Time: 9:00 PM
 */

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Support\Facades\Log;

trait FeatureFlagHelper
{

    public function registerFeatureFlags()
    {
        try {
            $features = \Cache::rememberForever('feature_flags:all', function () {
                $features = FeatureFlag::all()->toArray();

                foreach ($features as $key => $value) {
                    $features = $this->transformFeatures($features, $value, $key);
                    unset($features[$key]);
                }

                return $features;
            });

            if (!$features) {
                $features = [];
            }

            $world = new World();

            \Feature\Feature::create($world, $features);
        } catch (\Exception $e) {
            Log::info(sprintf("Silent Failure of Feature Flag %s", $e->getMessage()));
        }
    }

    private function transformFeatures($features, $value, $key)
    {
        $features[$value['key']] = $this->getAndSetValue($value);

        if (isset($value['variants']['users'])) {
            $features[$value['key']]['users'] = $value['variants']['users'];
        }

        return $features;
    }

    private function getAndSetValue($value)
    {
        if ($value['variants'] == 'on' or $value['variants'] == 'off') {
            return $value['variants'];
        }

        return $value;
    }
}
