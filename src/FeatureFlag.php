<?php

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $casts = [
        'variants' => 'json',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        static::saved(function ($model) {
            \Cache::forget('feature_flags:all');
        });
    }
}
