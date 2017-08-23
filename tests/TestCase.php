<?php

namespace Tests;

use Mockery;

class TestCase extends \Orchestra\Testbench\TestCase
{

    public function setUp()
    {
        parent::setUp();
    }


    public function tearDown()
    {
        Mockery::close();
    }
}
