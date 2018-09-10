<?php

namespace Tests;

use FriendsOfCat\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use FriendsOfCat\LaravelFeatureFlags\World;

class FeatureFlagHelperTest extends TestCase
{
    use DatabaseMigrations, FeatureFlagHelper;


    public function testCacheSettings()
    {
        $world = new World();

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

        $this->assertEquals(1, $world->userId());

        $feature = factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'bar',
                'variants' => ["on"]
            ]
        );
        $this->registerFeatureFlags();

        $this->assertEquals(1, $world->userId());
    }
}
