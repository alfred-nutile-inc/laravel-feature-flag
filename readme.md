# Feature Flags In Laravel


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]


  * [Overview](#overview)
  * [Installing](#installing)
  * [Usage](#usage)
  * [Usage Non Auth](#usage-non-auth)
  * [Import Export](#ie)
  * [Command Line](#command)
  * [Example](#example)
  * [Testing](#testing)
  * [Todo](#todo)

<a name=overview></a>
## Overview

You can find a comprehensive blog post about [this library here](https://alfrednutile.info/posts/175). This project is a work in progress.

We are working on using FeatureFlags or Toggles in our applications. For one we are aiming to do all our work on mainline branch at all times so this would be a key coding discipline to use FeatureFlags so we can hide a feature in progress knowing it will not interfere with the application. For example if a hotfix or another feature is ready to go to production we can push that with no worries of the in progress feature.

At the core we use this library [Atriedes/feature](https://github.com/Atriedes/feature) as it has the logic needed to consider common feature flag states eg user, users, on, off, groups, admin, internal, random etc. However, we are also mixing in some nice Laravel [Authorization](https://laravel.com/docs/5.2/authorization) features so you can do things like:

In a blade template:

~~~php
@can('feature-flag', 'add-twitter-field')
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

If you need to pass your feature flags to a front-end JS framework like Angular or Vue.js, you can do so by using the FeatureFlagsForJavascript::get() static method.

This uses this library [https://github.com/laracasts/PHP-Vars-To-Js-Transformer](https://github.com/laracasts/PHP-Vars-To-Js-Transformer) to put this info into the `windows` object, and for Angular the `$window` now you can access it:

~~~
JavaScript::Put(
            [
                'pusher_public_key' => env('PUSHER_PUBLIC'),
                'feature_flags'     => FeatureFlagsForJavascript::get()
            ]
        );
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

This will then place the files in `resources/vendors/laravel-feature-flags`. Just note that the views `@extends('layouts.default')` so if yours differs you will need to make an adjustment to the published views files.

Next, publish the configuration:

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider" --tag='config'
~~~

Important: The routes detault to being projected by the 'auth' middleware but you should check your installation to make sure permissions are acceptable. Middleware settings are configurable in 'config/laravel-feature-flag.php' file.



Make sure to set the `default_view` as well for the layout.

`config/laravel-feature-flag.php`

Your .env
```
LARAVEL_FEATURE_FLAG_VIEW="layouts.default"
```

<a name=usage></a>
## Usage

Visit `/admin/feature_flags` to manage features via the UI.


## Usage Non Auth

Sometimes you are not using this at the Auth user level, it is rare for most of our use cases but for non authenticated situations you can just use this

~~~
if(\Feature\Feature::isEnabled('see-twitter-field'))
{
  //do something
}
~~~

You can see more about that class here `vendor/jowy/feature/src/Feature/Feature.php`

Remember you needed to put this into the database, so it is on or off. You might not have a UI, maybe this is a microservice for example, so just migrate the state into the database for example

~~~
$feature = new FeatureFlag();
$feature->key = "see-twitter-field";
$feature->variants = "on"; //or "off"
$feature->save();
~~~

Now when the FeatureFlag Provider instantiates it will set this as the "World" state and you can access it via the isEnabled "on" being true and "off" being false.


<a name="ie"></a>
## Import Export

The UI has an import export feature.
This will allow you to easily move settings to a new build or environment.
Just visit the settings page and you will see the export import tab.

<a name="command"></a>
## Command Line

You can run a command:

```
php artisan ff:import_features storage/exported.json
```

To import an exported file, <a name="#ie">Import Export</a> for more info.

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

> [Helper Package](https://github.com/orchestral/testbench)

This Library pulls in `jowy/feature` and that library has tests. Other than that the there is the settings page which I do have some Laravel tests for that you can run once the package is installed.

Also if you are trying to test the use of it in your work you can use the helper trait in your test class

```php

    use DatabaseTransactions, \AlfredNutileInc\LaravelFeatureFlags\FeatureFlagHelper;
```

Then from there factory out your additions and state then reregister the world

```php

    /**
     * @test
     */
    public function should_fail_validation_since_twitter_missing()
    {
        //Make a form request
        //Set validation on that related to twitter field
        //make sure the feature flag is on

        $user_id = Rhumsaa\Uuid\Uuid::uuid4()->toString();

        $user = factory(\App\User::class)->create([
            'id' => $user_id,
            'is_admin' => 1
        ]);

        $this->actingAs($user);

        factory(\AlfredNutileInc\LaravelFeatureFlags\FeatureFlag::class)->create(
            [
                'key' => 'add-twitter-field',
                'variants' => 'on'
            ]
        );

        $this->registerFeatureFlags();
        ////
    }

```

<a name=todo></a>
## TODO

  * Use Model Events to do that level of work
  * Cache of the FeatureFlag Settings and update Cache on Change
  * Show how it works in the menu and other areas eg include and Provider



[ico-version]: https://img.shields.io/packagist/v/alfred-nutile-inc/laravel-feature-flag.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alfred-nutile-inc/laravel-feature-flag/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/alfred-nutile-inc/laravel-feature-flag.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/alfred-nutile-inc/laravel-feature-flag.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alfred-nutile-inc/laravel-feature-flag.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/alfred-nutile-inc/laravel-feature-flag
[link-travis]: https://travis-ci.org/alfred-nutile-inc/laravel-feature-flag
[link-scrutinizer]: https://scrutinizer-ci.com/g/alfred-nutile-inc/laravel-feature-flag/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/alfred-nutile-inc/laravel-feature-flag
[link-downloads]: https://packagist.org/packages/alfred-nutile-inc/laravel-feature-flag
[link-author]: https://github.com/alfred-nutile-inc