# Feature Flags In Laravel

  * [Overview](#overview)
  * [Installing](#installing)
  * [View](#view)
  * [Menu](#menu)
  * [Testing](#testing)  
  * [Todo](#todo)

<a name=overview></a>
## Overview

We are working on using FeatureFlags or Toggles in our applications. For one we are aiming to do all our work on mainline branch at all times so this would be a key coding discipline to use FeatureFlags so we can hide a feature in progress knowing it will not interfere with the application. For example if a hotfix or another feature is ready to go to production we can push that with no worries of the in progress feature. 

At the core we use this library [Atriedes/feature](https://github.com/Atriedes/feature) as it has the logic needed to consider common feature flag states eg user, users, on, off, groups, admin, internal, random etc. However, we are also mixing in some nice Laravel [Authorization](https://laravel.com/docs/5.2/authorization)features so you can do things like: 

In a blade template:

~~~php
@can('feature-flage', 'add-twitter-field')
<!-- code here -->
@endcan
~~~

Or in PHP:

~~~php
if (Gate::allows('feature-flag', 'awesome-feature')) {
    <!-- code here -->
}
~~~

~~~php
if (Gate::denies('feature-flag', 'awesome-feature')) {
    <!-- code here -->
}
~~~

<a name=installing></a>
## Installing 

Set your `composer.json` to the following to avoid composer error messages:

~~~
"config": {
        ....
    },
    "minimum-stability": "dev"
}
~~~

Require the package using composer: 

~~~
composer require alfred-nutile-inc/laravel-feature-flag
~~~

### Providers

Add the following to your config/app.php providers array:

~~~
AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider::class,
~~~

Publish the package migrations:

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider" --tag='migrations'
~~~

Then run migration to setup the base table:

~~~
php artisan migrate
~~~

### Demo / Example

If you want to try the demo/example also include the following in your config/app.php providers array:

~~~
AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureProvider::class
~~~

and then run:

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureProvider" --tag='migrations'
php artisan migrate
~~~

It has a rollback to help clean up after.

### Notes

This will make a number of routes:
~~~
+--------+----------+------------------------------------+---------------------------+--------------------------------------------------------------------------------+------------+
| Domain | Method   | URI                                | Name                      | Action                                                                         | Middleware |
+--------+----------+------------------------------------+---------------------------+--------------------------------------------------------------------------------+------------+
|        | GET|HEAD | /                                  |                           | Closure                                                                        |            |
|        | POST     | admin/feature_flags                | feature_flags.store       | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@store       |            |
|        | GET|HEAD | admin/feature_flags                | feature_flags.index       | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@getSettings |            |
|        | GET|HEAD | admin/feature_flags/create         | feature_flags.create_form | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@create      |            |
|        | GET|HEAD | admin/feature_flags/example        | feature_flags.example       | \AlfredNutileInc\LaravelFeatureFlags\ExampleController@seeTwtterField          |            |
|        | DELETE   | admin/feature_flags/{feature}      | feature_flags.delete      | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@destroy     |            |
|        | PUT      | admin/feature_flags/{feature}      | feature_flags.update      | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@update      |            |
|        | GET|HEAD | admin/feature_flags/{feature}/edit | feature_flags.edit_form   | \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagSettingsController@edit        |            |
+--------+----------+------------------------------------+---------------------------+--------------------------------------------------------------------------------+------------+
~~~

Note: It defaults wide open! Add auth as needed.

~~~
    public function __construct()
    {
        $this->middleware('auth');
    }
~~~

Note: The view is `@extends('layouts.default')` so if yours differs just publish the views locally 

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider" --tag='views'
~~~

This will then place the files in `resources/vendors/feature_flags`


### Then the ExampleFeature Provider

Just so I could try out this library on something so I could wrap my head around it I made an example feature that added a twitter field to the user data.

This was great cause it was a schema change so I had to make sure the field was `nullable` and it offered some view level interactions with the FeatureFlag.

There is a dummy route called `/admin/feature_flags/example` that you can visit and it will show that it is not on.

![ff_off](https://dl.dropboxusercontent.com/s/lld10qlvnbhzyhz/ff_off.png?dl=0)

But if you then go to the database to add it `/admin/feature_flags/create`

and add 

![ff_on](https://dl.dropboxusercontent.com/s/lcepthfx5t9i5rj/ff_on.png?dl=0)

And go back it will be on.

<a name=view></a>
## In the View


I made a view of it 
~~~
@can('feature-flag', 'add-twitter-field')
<div class="form-group">
    <div class="form-group">
        <label for="twitter">Twitter Name</label>
        <input type="text" name="twitter" class="form-control" value="@if($user->twitter){{ $user->twitter }}@endif"/>
    </div>
</div>
@endcan
~~~

That can be injected into the main view that this feature will be altering

~~~
<div class="form-group">
    <div class="form-group">
        <label for="name">Email</label>
        <input type="text" name="email" class="form-control" value="@if($user->email){{ $user->email  }}@endif"/>
    </div>
</div>

@include('feature_flags::twitter_name_input')

<div class="form-group">
    <div class="form-group">
        <label for="active">Is Admin</label>
        @if($user->is_admin && $user->is_admin == 1)
            <input type="checkbox" name="is_admin" class="form-control" checked="checked" value="1">
        @else
            <input type="checkbox" name="is_admin" class="form-control" value="1">
        @endif
    </div>
</div>
~~~


Now you can turn off this info as needed. And in your Controllers/Repository just remember to not assume that field is coming in via request or that it even exists in the Model.

~~~
$user->twitter = ($request->input("twitter")) ? $request->input("twitter") : null;
~~~

<a name=testing></a>
## Testing

This Library pulls in `jowy/feature` and that library has tests. Other than that the there is the settings page which I do have some Laravel tests for that you can run once the package is installed.

<a name=todo></a>
## TODO

  * Use Model Events to do that level of work
  * Cache of the FeatureFlag Settings and update Cache on Change
  * Show how it works in the menu and other areas eg include and Provider

