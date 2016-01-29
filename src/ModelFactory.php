<?php

$factory->define(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class, function ($faker) {
    return [
        'key' => str_random(3),
        'variants' => []
    ];
});

