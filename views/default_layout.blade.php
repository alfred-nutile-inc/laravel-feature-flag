<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Feature Flag') }}</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
        crossorigin="anonymous">

</head>

<body class="top-navigation skin-1 fixed-nav pace-done">
    <div id="app">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        You are using the default layout for Feature Flags. Set your .env to
                        <b>LARAVEL_FEATURE_FLAG_VIEW=your_default_layout</b>
                        to use that one. See more in the package readme.
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

</body>

</html>