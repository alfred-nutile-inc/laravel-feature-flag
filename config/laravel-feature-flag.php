<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Feature Flag Configuration
    |--------------------------------------------------------------------------
    |
    | Below you may set the middleware that will be used on the routes provided
    | by the laravel-feature-flag package. You may pass in a string for a
    | middleware or an array for multiple middleware.
    |
    */

    'route_middleware' => 'auth',
    'default_view' => 'layouts.default',
    'add_link_to_menu' => false

    // Example with multiple middleware:
    // 'route_middleware' => "['auth', 'custom_middleware']",

];
