<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/24/16
 * Time: 11:08 AM
 */

namespace AlfredNutileInc\LaravelFeatureFlags;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class World implements \Feature\Contracts\World
{

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function configValue($name, $default = null)
    {
        // TODO: Implement configValue() method.
    }

    /**
     * @return mixed
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
        if(Auth::guest())
            return false;

        return Auth::user()->email;
    }

    /**
     * @param string|int $userId
     * @return string
     */
    public function userName($userId)
    {
        if(Auth::guest())
            return false;

        return Auth::user()->email;
    }

    /**
     * @param int $userId
     * @param int $groupdId
     * @return bool
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
        if(Auth::guest())
            return false;

        return Auth::user()->is_admin == 1;
    }

    /**
     * @return bool
     */
    public function isInternalRequest()
    {
        // TODO: Implement isInternalRequest() method.
    }

    /**
     * @return string
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
     */
    public function log($name, $variant, $selector)
    {
        Log::info(sprintf("Name %s, Variant %s Selector %s", $name, $variant, $selector));
    }
}