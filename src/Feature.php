<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/24/16
 * Time: 11:06 AM
 */

namespace FriendsOfCat\LaravelFeatureFlags;

use Feature\Contracts\World;

class Feature implements World
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
        // TODO: Implement uaid() method.
    }

    /**
     * @return int
     */
    public function userId()
    {
        // TODO: Implement userId() method.
    }

    /**
     * @param string|int $userId
     * @return string
     */
    public function userName($userId)
    {
        // TODO: Implement userName() method.
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
        // TODO: Implement isAdmin() method.
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
        // TODO: Implement log() method.
    }
}
