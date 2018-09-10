<?php

/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/24/16
 * Time: 11:08 AM
 */

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class World implements \Feature\Contracts\World
{

    /**
     * @codeCoverageIgnore
     * @noteImplemented
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function configValue($name, $default = null)
    {
        dd("HERE");
    }

    /**
     * @return mixed
     * @codeCoverageIgnore
     * @noteImplemented
     */
    public function uaid()
    {
        //
    }

    /**
     * @return int
     */
    public function userId()
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->id;
    }

    /**
     * @param string|int $userId
     * @return string
     */
    public function userName($user_name)
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->email == $user_name;
    }

    /**
     * @param int $userId
     * @param int $groupdId
     * @return bool
     * @codeCoverageIgnore
     * @noteImplemented
     */
    public function inGroup($userId, $groupdId)
    {
        // TODO: Implement inGroup() method.
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isAdmin($userId)
    {
        //need to implement
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     * @noteImplemented
     */
    public function isInternalRequest()
    {
        // TODO: Implement isInternalRequest() method.
    }

    /**
     * @return string
     * @codeCoverageIgnore
     * @noteImplemented
     */
    public function urlFeatures()
    {
        // TODO: Implement urlFeatures() method.
    }

    /**
     * @param $name
     * @param $variant
     * @param $selector
     * @return void
     * @codeCoverageIgnore
     * @noteImplemented
     */
    public function log($name, $variant, $selector)
    {
    }
}
