<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExampleController extends Controller
{

    public function seeTwtterField()
    {

        /**
          * Gate is based around an authenticated user
          */
        $user = factory(\App\User::class)->create();
        \Auth::login($user);
        return view('twitter::full_page_twitter_show', compact('user'));
    }

}
