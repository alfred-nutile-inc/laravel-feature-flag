<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 8/22/17
 * Time: 8:56 PM
 */

namespace Tests;

use AlfredNutileInc\LaravelFeatureFlags\FeatureFlag;
use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsForJavascript;
use Illuminate\Support\Facades\App;

class FeatureFlagsForJavascriptTest extends TestCase
{

    public function testGetWithResults()
    {

        $this->markTestSkipped("Left off here...");
        $mock = \Mockery::mock(FeatureFlag::class);
        $mock->shouldReceive('all')->once();

        App::instance(FeatureFlag::class, $mock);

        $fjs = new FeatureFlagsForJavascript();

        $this->assertEmpty($fjs->get());
    }
}
