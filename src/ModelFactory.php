<?php

/**
 * @codeCoverageIgnore
 */
$factory->define(\FriendsOfCat\LaravelFeatureFlags\FeatureFlag::class, function ($faker) {
    return [
        'key' => str_random(3),
        'variants' => []
    ];
});
