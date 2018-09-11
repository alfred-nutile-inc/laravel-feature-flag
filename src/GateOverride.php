<?php

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Auth\Access\Gate as GateFoundation;

/**
 * Class GateOverride
 * @package FriendsOfCat\LaravelFeatureFlags
 * @codeCoverageIgnore
 * @NOTE find out more what this class is doing
 */
class GateOverride extends GateFoundation
{

    protected function callBeforeCallbacks($user, $ability, array $arguments)
    {
        $arguments = array_merge([$user, $ability], $arguments);

        foreach ($this->beforeCallbacks as $before) {
            if (! is_null($result = call_user_func_array($before, $arguments))) {
                return $result;
            }
        }
    }
}
