<?php

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class FeatureFlagUser extends Model implements Authenticatable
{

    use AuthenticableTrait;
    protected $table = "users";
}
