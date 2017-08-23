<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 8/22/17
 * Time: 3:17 PM
 */

namespace Tests;

use AlfredNutileInc\LaravelFeatureFlags\World;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Mockery\Mock;

class WorldTest extends TestCase
{

    public function testUserIdFalse()
    {

        $world = new World();
        $this->assertFalse($world->userId());
    }

    public function testUserIdTrue()
    {

        $world = new World();

        Auth::shouldReceive('guest')->once()->andReturn(false);
        Auth::shouldReceive('user')->once()->andReturn((object) [ "id" => 1]);

        $this->assertEquals(1, $world->userId());
    }

    public function testUserNameTrue()
    {

        $world = new World();

        Auth::shouldReceive('guest')->once()->andReturn(false);
        Auth::shouldReceive('user')->once()->andReturn((object) [ "email" => "foo@foo.com"]);

        $this->assertTrue($world->userName("foo@foo.com"));
    }

    public function testUserNameFalse()
    {

        $world = new World();

        Auth::shouldReceive('guest')->once()->andReturn(false);
        Auth::shouldReceive('user')->once()->andReturn((object) [ "email" => "foo@foo.com"]);

        $this->assertFalse($world->userName("bar@foo.com"));
    }


    public function testIsAdmin()
    {

        $world = new World();

        Auth::shouldReceive('findOrFail')->once()->andReturn((object) [ "is_admin" => true]);

        $this->assertTrue($world->isAdmin(1));
    }

    public function testIsNotAdmin()
    {

        $world = new World();

        Auth::shouldReceive('findOrFail')->once()->andReturn((object) [ "is_admin" => false]);

        $this->assertFalse($world->isAdmin(1));
    }
}
