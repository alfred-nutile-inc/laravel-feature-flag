<?php

namespace Tests;

use FriendsOfCat\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class SettingsPageTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        $this->markTestSkipped(
            "We need to figure out how to do these UI tests outside of laravel OR
        during the travis build setup a laravel up to run this in. 
        More later https://github.com/orchestral/testbench/blob/3.5/README.md"
        );
        parent::setUp();

        $user = factory(\App\User::class)->create(['is_admin' => 1]);
        $this->user = $user;
    }

    public function testShouldSeeSettings()
    {

        factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => [ 'users' => [$this->user->email]]
            ]
        );

        $this->actingAs($this->user)->visit('/admin/feature_flags')
            ->see('Create Feature Flag')->see('add-twitter-field');
    }

    public function testCanEditSettings()
    {
        $key = str_random();

        $flag = factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => $key,
                'variants' => [ 'users' => [$this->user->email]]
            ]
        );

        $variant = str_random();

        $this->actingAs($this->user)->visit('admin/feature_flags/' . $flag->id . '/edit')
            ->type("\"$variant\"", 'variants')
            ->press('Save')
            ->see('Set your feature flags')
            ->see($variant);
    }

    public function testCanCreateSettings()
    {


        $key = str_random();
        $variant = str_random();

        $this->actingAs($this->user)->visit('admin/feature_flags/create')
            ->type($key, 'key')
            ->type($variant, 'variants')
            ->press('Save')
            ->see('Set your feature flags')
            ->see($key)
            ->see($variant);
    }
}
