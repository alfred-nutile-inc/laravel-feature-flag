# Feature Flags In Laravel

  * [Overview](#overview)
  * [Installing](#installing)
  * [Usage](#usage)
  * [Example](#example)
  * [Testing](#testing)  
  * [Todo](#todo)

<a name=overview></a>
## Overview

You can find a comprehensive blog post about [this library here](https://alfrednutile.info/posts/175). This project is a work in progress.

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

This package creates a number of routes. They can be overridden by publishing the views:

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider" --tag='views'
~~~

This will then place the files in `resources/vendors/feature_flags`. Just note that the views `@extends('layouts.default')` so if yours differs you will need to make an adjustment to the published views files. 

Important: The routes detault to being projected by $this->middleware('auth') but you should check your installation to make sure permissions are acceptable.

<a name=usage></a>
## Usage

Visit `/admin/feature_flags` to manage features via the UI.

<a name=example></a>
## Demo / Example

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

There is a dummy route called `/admin/feature_flags/example` that you can visit and it will show that it is not on. But if you then go to the admin UI `/admin/feature_flags` you can toggle it on and off. 


<a name=testing></a>
## Testing

This Library pulls in `jowy/feature` and that library has tests. Other than that the there is the settings page which I do have some Laravel tests for that you can run once the package is installed.

<a name=todo></a>
## TODO

  * Use Model Events to do that level of work
  * Cache of the FeatureFlag Settings and update Cache on Change
  * Show how it works in the menu and other areas eg include and Provider

