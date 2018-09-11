<?php
/**
 * Created by PhpStorm.
 * User: alfrednutile
 * Date: 1/24/16
 * Time: 11:06 AM
 */

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Support\Facades\Auth;

class Feature
{
    const ON  = "on";
    const OFF = "off";

    /**
     * @var self
     */
    private static $instance;

    /**
     * @var array
     */
    private $stanza;

    /**
     * @param array $stanza
     */
    private function __construct( array $stanza)
    {
        $this->stanza = $stanza;
    }

    /**
     * @param array $stanza
     * @return Feature
     */
    public static function create(array $stanza)
    {
        if (static::$instance) {
            return static::$instance;
        }

        static::$instance = new static($stanza);

        return static::$instance;
    }


    /**
     * @param $feature
     * @return bool
     */
    public static function isEnabled($feature)
    {
        $feature_variant = static::getConfig($feature);

        if($feature_variant != self::ON and $feature_variant != self::OFF) {
            return self::isUserEnabled($feature_variant);
        }

        return ($feature_variant == self::ON);
    }

    /**
     * @param $feature
     * @return string
     */
    private static function getConfig($feature)
    {
        if (isset(static::$instance->stanza[$feature])) {
            return static::$instance->stanza[$feature];
        }

        $feature_flag = FeatureFlag::where('key', $feature)->first();
        if (isset($feature_flag)) {
            return  $feature_flag->variants;
        }

        return self::OFF;
    }

    /**
     * @param $feature_variant
     * @return bool
     */
    protected static function isUserEnabled($feature_variant)
    {
        if ($user_email = static::getUserEmail()) {

            $result = (is_array($feature_variant)) ? json_decode($feature_variant['variants'], true) : json_decode($feature_variant, true);
            $target_array = $result['users'];

            if (in_array($user_email, $target_array)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param string|int $userId
     * @return string
     */
    public static function getUserEmail()
    {
        if (Auth::guest()) {
            return false;
        }

        return Auth::user()->email;
    }


}
