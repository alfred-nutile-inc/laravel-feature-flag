<?php

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Database\Seeder;

class AddExampleFeaturesTableSeeder extends Seeder
{
    public function run()
    {
        $feature = new \FriendsOfCat\LaravelFeatureFlags\FeatureFlag();
        $feature->key = 'add-twitter-field';
        $feature->variants = [ 'users' => ['alfrednutile@gmail.com'] ];
        $feature->save();

        $feature = new \FriendsOfCat\LaravelFeatureFlags\FeatureFlag();
        $feature->key = 'see-twitter-field';
        $feature->variants = [ 'users' => ['foobar@gmail.com'] ];
        $feature->save();
    }
}
