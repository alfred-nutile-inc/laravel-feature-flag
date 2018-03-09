<?php

namespace Tests;

use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagUser;

class FeatureFlagTest extends TestCase
{
    use FeatureFlagHelper;

    protected $user;

    public function testShouldSeeFeatureAsAdmin()
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

    public function testShouldSeeFeatureOnProfile()
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

    public function testNotSeeTwitterOnProfilePage()
    {
        $this->markTestSkipped("Gotta get this one working");
        $user_id = str_random(32);

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


    public function testOnOff()
    {

        \DB::table('users')->insert(
            ['email' => 'john@example.com', 'password' => "foo", 'name' => "bar"]
        );

        $this->user = FeatureFlagUser::first();

        $this->be($this->user);

        $feature = factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
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
