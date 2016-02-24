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
    public function should_see_feature_as_admin()
    {
        $this->markTestSkipped("Gotta get this one working");

        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->be($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => '{ "users": [ "' .  $user->email . '" ]}'
            ]
        );

        $path = '/admin/users/' . $user_id . '/edit';
        $this->get($path)->see('Twitter Name');
        $response = $this->call('GET', $path);
        $this->assertEquals(200, $response->status());
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
                'variants' => '{ "users": [ "foo" ]}'
            ]
        );

        $path = '/admin/users/' . $user_id . '/edit';
        $this->get($path)->dontSee('Twitter Name');

        $response = $this->call('GET', $path);

        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     */
    public function should_see_feature_on_profile()
    {
        $this->markTestSkipped("Gotta get this one working");
        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->actingAs($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'see-twitter-field',
                'variants' => '{ "users": [ "' . $user->email . '" ]}'
            ]
        );

        $path = '/profile/' . $user_id;
        $this->get($path)->see('Twitter Name');
        $response = $this->call('GET', $path);
        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     */
    public function not_see_twitter_info_on_profile_page()
    {
        $this->markTestSkipped("Gotta get this one working");
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
                'variants' => '{ "users": [ "not_you" ]}'
            ]
        );

        $this->get('/profile/' . $user_id)->dontSee('Twitter Name');

        $response = $this->call('GET', '/profile/' . $user_id);

        $this->assertEquals(200, $response->status());
    }



}
