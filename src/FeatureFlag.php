<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $casts = [
        'variants' => 'json',
    ];

    public $timestamps = false;
}
