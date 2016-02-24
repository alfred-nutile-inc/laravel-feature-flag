<?php

namespace AlfredNutileInc\LaravelFeatureFlags;


use Illuminate\Support\Facades\Auth;

class ExampleFeatureFlagLogic
{

    public function addTwitterField()
    {
        return \Feature\Feature::isEnabled('add-twitter-field');
    }

    public function seeTwitterField()
    {
        Log::info("Being called?");
        return \Feature\Feature::isEnabled('see-twitter-field');
    }


}
