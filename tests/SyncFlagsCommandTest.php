<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use FriendsOfCat\LaravelFeatureFlags\FeatureFlag;
use Illuminate\Support\Facades\Artisan;

class SyncFlagsCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldSyncFeatureFlags()
    {

        factory(FeatureFlag::class, 2)->create(
            [
                'variants' => 'on',
            ]
        );

        $sync_flags = [
            'new_flag' => 'off',
        ];

        $this->app['config']->set('laravel-feature-flag.sync_flags', $sync_flags);

        Artisan::call('feature-flag:sync', ['--force' => true]);

        // Only the new_flag should be left.
        $this->assertSame(1, FeatureFlag::count());

        $added_flag = FeatureFlag::where('key', 'new_flag')->first();

        $this->assertSame('new_flag', $added_flag->key);
        $this->assertSame('off', $added_flag->variants);
    }

    public function testShouldSyncNotOverwriteFeatureFlags()
    {

        factory(FeatureFlag::class)->create(
            [
                'key' => 'existing_flag',
                'variants' => 'on',
            ]
        );

        $sync_flags = [
            'existing_flag' => 'off',
        ];

        $this->app['config']->set('laravel-feature-flag.sync_flags', $sync_flags);

        Artisan::call('feature-flag:sync', ['--force' => true]);

        // Only the new_flag should be left.
        $this->assertSame(1, FeatureFlag::count());

        $existing_flag = FeatureFlag::where('key', 'existing_flag')->first();

        // Should still be 'on', not overwritten to 'off'.
        $this->assertSame('on', $existing_flag->variants);
    }

    public function testShouldSyncFeatureFlagsSkippingCleanup()
    {

        factory(FeatureFlag::class, 2)->create(
            [
                'variants' => 'on',
            ]
        );

        $sync_flags = [
            'new_flag' => 'off',
        ];

        $this->app['config']->set('laravel-feature-flag.sync_flags', $sync_flags);

        Artisan::call('feature-flag:sync', ['--force' => true, '--skip-cleanup' => true]);

        // Only the new_flag should be left.
        $this->assertSame(3, FeatureFlag::count());
    }
}
