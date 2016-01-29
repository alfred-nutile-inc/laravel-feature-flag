# Feature Flags In Laravel

  * [Overview](#overview)
  * [Installing](#installing)
  * [View](#view)
  * [Menu](#menu)
  * [Todo](#todo)

<a name=overview></a>
## Overview

We are working on using FeatureFlags or Toggles in our applications. For one we are aiming to do all our work on mainline branch at all times so this would be a key coding discipline to use FeatureFlags so we can hide a feature in progress knowing it will not interfere with the application. For example if a hotfix or another feature is ready to go to production we can push that with no worries of the in progress feature. 

>FeatureFlags so we can hide a feature in progress knowing it will not interfere with the application

You can see many of the reasons in this article [http://martinfowler.com/articles/feature-toggles.html](http://martinfowler.com/articles/feature-toggles.html) by Pete Hodgson for using this system. So not just the more common situations of permissions but also Release Toggles, Experiment Toggles and more see list and image below

![flag_types](http://martinfowler.com/articles/feature-toggles/chart-4.png)

**[image from martin fowler feature-toggles article**


  * Release Toggles
  * Ops Toggles
  * Permission Toggles
  * Experiment Toggles
  
The core coding logic I will be using is this library [Atriedes/feature](https://github.com/Atriedes/feature) as it has the logic needed to consider common feature flag states eg user, users, on, off, groups, admin, internal, random etc.


  * on or off value simple!
  * on for users
  * on for groups
  * on for a user
  * bucketing random
  * random
  * percentage
  * url query string
  
>The core coding logic I will be using is this library [Atriedes/feature](https://github.com/Atriedes/feature)

One key thing, as I use this in Laravel, is I will try and mix this with the existing [Authorization](https://laravel.com/docs/5.2/authorization
) workflow that is already present. This gives me some already prepared ways to think about this both at the view layer, model layer and controller layer and where to register these states. Plus we then get great Laravel docs to help explain some of it.

For example I can use this in my theme

~~~
@can('add-twitter-field')
<!-- code here -->
@endcan
~~~


<a name=installing></a>
## Installing 

This will install two things. The library I made to do this and the Example library I am using to show it in action.

### Providers

Add the below to your config/app.php

~~~
AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider::class,
AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureProvider::class,
~~~

Then run migration

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\FeatureFlagsProvider" --tag='migrations'

php artisan migrate
~~~

To setup the base table.

If you want to try the demo go with

~~~
php artisan vendor:publish --provider="AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureProvider" --tag='migrations'
php artisan migrate
~~~

It has a rollback to help clean up after.



### The Core Library FeatureFlagsProvider

What does this do?

It does some basic Laravel work for registering views for settings, routes for managing settings CRUD and some cache on model changes so we can update the World as needed.

The big thing it does do is instantiate World.

~~~
    public function boot()
    {
        $this->registerViewFiles();

        $this->injectLinks();

        $this->registerFeatureFlags();
    }
    
    private function registerFeatureFlags()
    {
        $features = FeatureFlag::where('active', 1)->get()->toArray();

        foreach($features as $key => $value)
        {
            $features = $this->transformFeatures($features, $value, $key);
            unset($features[$key]);
        }

        $world = new World();

        \Feature\Feature::create($world, $features);
    }

    private function transformFeatures($features, $value, $key)
    {
        $features[$value['key']] = $value;
        $features[$value['key']]['users'] = (isset($value['variants']['users'])) ? $value['variants']['users'] : [];
        return $features;
    }
    
    
~~~

>The big thing it does do is instantiate World.

The database saves the data in a way that I will talk about below. Above is the transformation of that data into a more compatible form to this library.

So at this point we have World, which is where we set our way of finding truth and `$features` which is the state of all features.

**NOTE**
The `FeatureFlagProvider` will override one method in the default `Gate` class. This was just so having a
`@can` in a view did not crash the app if I was turn turn off that `Gate`

#### World

This class implements the interface that comes with the library

You can see that [here](https://gist.github.com/anonymous/c508101f0a85a4751c93)

I simply do some logic in there based off User email since that is all I am using right now instead of user id or name. More can be added as needed to react to all the other options.

But this is how we find things like 'can this user see this feature', 'what users are active for this feature' etc. Basically it is the class you use to tie your framework into the FeatureFlag logic. So when it asks for groups, users, etc it has the methods and logic needed to compare your data to the feature flag requirements.


### Then the ExampleFeature Provider

Just so I could try out this library on something so I could wrap my head around it I made an example feature that added a twitter field to the user data.

This was great cause it was a schema change so I had to make sure the field was `nullable` and it offered some view level interactions with the FeatureFlag.

<a name=view></a>
## In the View


I made a view of it 
~~~
@can('add-twitter-field')
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

All of this is registered in the Provider setup above

~~~
public function boot(GateContract $gate)
{

  $this->registerPolicies($gate);
  
  $gate->define('add-twitter-field', '\AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureFlagLogic@addTwitterField');
  
  $gate->define('see-twitter-field', '\AlfredNutileInc\LaravelFeatureFlags\ExampleFeatureFlagLogic@seeTwitterField');
    }
~~~

Those logic classes are super simple thanks to this library and really could just have been Closures.

~~~
<?php

namespace AlfredNutileInc\LaravelFeatureFlags;


class ExampleFeatureFlagLogic
{

    public function addTwitterField()
    {
        return \Feature\Feature::isEnabled('add-twitter-field');
    }

    public function seeTwitterField($user)
    {
        return \Feature\Feature::isEnabled('see-twitter-field');
    }


}
~~~

Now you can turn off this info as needed. And in your Controllers/Repository just remember to not assume that field is coming in via request or that it even exists in the Model.

~~~
$user->twitter = ($request->input("twitter")) ? $request->input("twitter") : null;
~~~

Honestly I think it is better to add this to a Laravel Model Event. Then as you listen to that Event you can react to it. And when not having the feature on or installed any longer it is one less place your code is being injected into the application. 

<a name='migrations'></a>
## FeatureFlag Migration 

The Migration will make one table for `feature_flags` and add a column to the user table for the example twitter field feature.

Note the Example Provider will add the `twitter` column to the `user` table. It is nullable so it will not be an issue if other parts of the app do not consider this data.

In the `feature_flags` table there we have the id column, the key column, active column to even consider the feature flag and then the variants column if any. I might get rid of the active column but thought it might speed up queries.

The variant column being json allows us to store unstructured data to hold any data for different situations so we can cover all the possible variants listed above.

For example

~~~

$server_config['show-twitter'] => array('users' => array('on' => array('fred')))

~~~

would store as

~~~
|ID |KEY           |ACTIVE   |VARIANT                          |
|---|--------------|---------|---------------------------------|
| 2 | show-twitter | 1       |{ 'users': [ 'on': [ 'fred' ] ] }|
|   |              |         |                                 |
~~~


<a name=menu></a>
## Menu

Just a note the demo has the Feature Flag menu. Because this core app uses the ViewComposer pattern to create a "links" array I can add to that later on in my FeatureFlagProvider 

![menu](https://dl.dropbox.com/s/daftzzfq7it6wxx/feature_flag_menu.png?dl=0)

~~~
    private function injectLinks()
    {

            view()->composer(
                'layouts.default', function($view) {
                if ($view->offsetExists('links')) {
                    $links_original = $view->offsetGet('links');
                    $links = [
                        ['title' => 'Feature Flags', 'url' => route('feature_flags.index'), 'icon' => 'flag-o']
                    ];

                    $view->with('links', array_merge($links_original, $links));
                }
            }
            );

    }
~~~

But that is for another post! 


<a name=todo></a>
## TODO

  * Use Model Events to do that level of work
  * Cache of the FeatureFlag Settings and update Cache on Change
  * Show how it works in the menu and other areas eg include and Provider

