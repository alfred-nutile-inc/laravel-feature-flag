<?php


$factory->define(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class, function ($faker) {
    return [
        'key' => str_random(3),
        'variants' => []
    ];
});



$factory->define(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlagUser::class, function ($faker) {
    return [
        'name' => $faker->word,
        'email' => $faker->email,
        'password' => bcrypt(str_random(25))
    ];
});
