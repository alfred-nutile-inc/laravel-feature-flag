<?php

namespace Tests;

use FriendsOfCat\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use FriendsOfCat\LaravelFeatureFlags\FeatureFlagUser;
use Ramsey\Uuid\Uuid;

class FeatureFlagTest extends TestCase
{
    use DatabaseMigrations, FeatureFlagHelper;

    protected $user;

    public function testShouldSeeFeatureAsAdmin()
    {
        $this->markTestSkipped("Gotta get this one working outside laravel");

        $user_id = Uuid::uuid4()->toString();

        $user = factory(FeatureFlagUser::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->be($user);

        factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => '{ "users": [ "' . $user->email . '" ]}'
            ]
        );

        $path = '/admin/users/' . $user_id . '/edit';
        $this->get($path)->see('Twitter Name');
        $response = $this->call('GET', $path);
        $this->assertEquals(200, $response->status());
    }

    public function testShouldNotSeeFeatureAsAdmin()
    {
        $this->markTestSkipped("Gotta get this one working outside laravel");
        $user_id = Uuid::uuid4()->toString();

        $user = factory(FeatureFlagUser::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->be($user);

        factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
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

    public function testShouldSeeFeatureOnProfile()
    {
        $this->markTestSkipped("Gotta get this one working outside laravel");
        $user_id = Uuid::uuid4()->toString();

        $user = factory(FeatureFlagUser::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->actingAs($user);

        factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
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

    public function testNotSeeTwitterOnProfilePage()
    {
        $this->markTestSkipped("Gotta get this one working outside laravel");
        $user_id = str_random(32);

        $user = factory(FeatureFlagUser::class)->create([
            'id' => $user_id,
            'is_admin' => 1,
            'twitter' => 'footwitter'
        ]);

        $this->actingAs($user);

        factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'see-twitter-field',
                'variants' => '{ "users": [ "not_you" ]}'
            ]
        );

        $this->get('/profile/' . $user_id)->dontSee('Twitter Name');

        $response = $this->call('GET', '/profile/' . $user_id);

        $this->assertEquals(200, $response->status());
    }


    public function testOnOff()
    {

        $this->user = factory(FeatureFlagUser::class)->create();

        $this->be($this->user);

        $feature = factory(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'testing',
                'variants' => 'off'
            ]
        );

        $this->registerFeatureFlags();

        $this->get('/example')->assertSeeText("Testing Off");


        //$feature->variants = "on";

        //$feature->save();

        //$this->registerFeatureFlags();

        //$this->get('/example')->assertDontSeeText("Testing Off")->assertSeeText("Testing On");
    }
}
