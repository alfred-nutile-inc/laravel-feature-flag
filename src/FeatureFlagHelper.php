<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/28/16
 * Time: 9:00 PM
 */

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Support\Facades\Log;

trait FeatureFlagHelper
{

    public function registerFeatureFlags()
    {
        try
        {
            $features = FeatureFlag::all()->toArray();

            foreach($features as $key => $value)
            {
                $features = $this->transformFeatures($features, $value, $key);
                unset($features[$key]);
            }

            $world = new World();

            \Feature\Feature::create($world, $features);


        }
        catch(\Exception $e)
        {
            Log::info("Silent Failure of Feature Flag");
        }
    }

    private function transformFeatures($features, $value, $key)
    {
        $features[$value['key']] = $value;
        $features[$value['key']]['users'] = (isset($value['variants']['users'])) ? $value['variants']['users'] : [];
        return $features;
    }
}