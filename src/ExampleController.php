<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExampleController extends Controller
{

    public function seeTwtterField()
    {
        return view('twitter::full_page_twitter_show');
    }

}
