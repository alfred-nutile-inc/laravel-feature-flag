<?php

namespace Tests;

use AlfredNutileInc\LaravelFeatureFlags\FeatureFlagHelper;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use AlfredNutileInc\LaravelFeatureFlags\ExportImportRepository;
use AlfredNutileInc\LaravelFeatureFlags\FeatureFlag;

class ExportTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldExportFeatureFlags()
    {

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'foo',
                'variants' => ["on"]
            ]
        );

        $repo = new ExportImportRepository();

        $results = $repo->export();

        $this->assertNotNull($results);

        $this->assertEquals([
            [
                'key' => "foo",
                "variants" => ['on']
            ]
        ], $results);
    }

    public function testShouldImportResults()
    {
        $exported = \File::get(__DIR__ . '/fixtures/exported.json');
        $exported = json_decode($exported, true);
        $repo = new ExportImportRepository();

        $results = $repo->import($exported);

        $ff = FeatureFlag::all();

        $this->assertNotNull($ff);

        $this->assertCount(1, $ff);

        $this->assertEquals("foo", $ff->first()->key);
    }

    public function testShouldNotDuplicateResults()
    {
        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'foo',
                'variants' => ["on"]
            ]
        );

        $exported = \File::get(__DIR__ . '/fixtures/exported.json');
        $exported = json_decode($exported, true);
        $repo = new ExportImportRepository();

        $results = $repo->import($exported);

        $ff = FeatureFlag::all();
        $this->assertNotNull($ff);

        $this->assertCount(1, $ff);

        $this->assertEquals("foo", $ff->first()->key);
    }

    public function testUpdatesExistingResult()
    {
        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'foo',
                'variants' => ["off"]
            ]
        );

        $exported = \File::get(__DIR__ . '/fixtures/exported.json');
        $exported = json_decode($exported, true);
        $repo = new ExportImportRepository();

        $results = $repo->import($exported);

        $ff = FeatureFlag::all();

        $this->assertNotNull($ff);

        $this->assertCount(1, $ff);

        $this->assertEquals("foo", $ff->first()->key);

        $this->assertEquals("on", $ff->first()->variants[0]);
    }
}
