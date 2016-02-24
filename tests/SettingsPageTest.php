<?php

use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;


class SettingsPageTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setup()
    {
        parent::setUp();

        $user = factory(\App\User::class)->create(['is_admin' => 1]);

        $this->user = $user;



    }

    /**
     * @test
     */
    public function should_see_settings()
    {

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => [ 'users' => [$this->user->email]]
            ]
        );

        $this->actingAs($this->user)->visit('/admin/feature_flags')
            ->see('Create Feature Flag')->see('add-twitter-field');

    }

    /**
     * @test
     */
    public function can_edit_settings()
    {
        $key = str_random();

        $flag = factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
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

    /**
     * @test
     */
    public function can_create_settings()
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
