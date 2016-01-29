<?php

use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class FeatureFlagTest extends TestCase
{
    use DatabaseTransactions, FeatureFlagHelper;

    public function setup()
    {
        parent::setUp();

        if($flags = \AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::all())
        {
            foreach($flags as $flag) { $flag->delete(); }
        }
    }

    /**
     * @test
     */
    public function should_not_see_feature_as_admin()
    {
        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->be($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'active' => 1,
                'variants' => [ 'users' => ['foo@foo.com']]
            ]
        );

        $this->get('/admin/users/' . $user_id . '/edit')->dontSee('Twitter Name');

    }

    /**
     * @test
     */
    public function should_see_feature()
    {
        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->actingAs($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => [ 'users' => [$user->email]]
            ]
        );

        $this->registerFeatureFlags();

        $this->assertTrue(\Feature\Feature::isEnabled('add-twitter-field'));

    }

    /**
     * @test
     */
    public function not_see_twitter_info_on_profile_page()
    {
        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1,
            'twitter' => 'footwitter'
        ]);

        $this->actingAs($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'see-twitter-field',
                'variants' => [ 'users' => ['not_you@foo.com']]
            ]
        );

        $this->registerFeatureFlags();

        $this->get('/profile/' . $user_id)->dontSee('Twitter Name');

    }



}
