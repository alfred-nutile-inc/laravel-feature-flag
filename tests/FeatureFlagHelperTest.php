<?php

namespace Tests;

use FriendsOfCat\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class FeatureFlagHelperTest extends TestCase
{
    use DatabaseMigrations, FeatureFlagHelper;


    public function testCacheSettings()
    {

        \Cache::shouldReceive("rememberForever")->twice();

        \Cache::shouldReceive("forget")->twice();

        $feature = factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'foo',
                'variants' => ["on"]
            ]
        );

        $this->registerFeatureFlags();

        \Auth::shouldReceive('guest')->andReturn(false);

        \Auth::shouldReceive('user')->andReturn((object)["id" => 1]);


        $feature = factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'bar',
                'variants' => ["on"]
            ]
        );
        $this->registerFeatureFlags();

    }
}
