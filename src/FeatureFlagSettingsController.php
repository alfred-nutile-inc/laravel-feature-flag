<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeatureFlagSettingsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getSettings()
    {
        $settings = FeatureFlag::all();
        $token = csrf_token();
        return view('feature_flags::settings', compact('settings', 'token'));
    }

    public function create(Request $request)
    {
        $flag = new FeatureFlag();

        return view('feature_flags::create', compact('flag'));
    }

    public function store(Request $request)
    {
        try
        {
            $flag = new FeatureFlag();
            $flag->key = $request->input('key');
            $flag->variants = $request->input('variants');
            $flag->save();

            return redirect()->route('feature_flags.index')->withMessage("Created Feature");
        }
        catch(\Exception $e)
        {
            return redirect()->route('feature_flags.index')->withMessage("Could not find feature flag");
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try
        {
            $flag = FeatureFlag::findOrFail($id);

            return view('feature_flags::edit', compact('flag'));
        }
        catch(\Exception $e)
        {
            return redirect()->route('feature_flags.index')->withMessage("Could not find feature flag");
        }
    }

   public function update(Request $request, $id)
    {
        try
        {
            $flag = FeatureFlag::findOrFail($id);

            $flag->variants     = ($request->input('variants')) ? json_decode($request->input('variants'), true) : null;
            $flag->save();

            return redirect()->route('feature_flags.index')->withMessage(sprintf("Feature Flag Updated %d", $id));
        }
        catch(\Exception $e)
        {
            return redirect()->route('feature_flags.index')->withMessage("Could not find feature flag");
        }
    }

    public function destroy($id)
    {
        try
        {
            $flag = FeatureFlag::findOrFail($id);

            $flag->delete();

            return redirect()->route('feature_flags.index')->withMessage(sprintf("Feature Flag Updated %d", $id));
        }
        catch(\Exception $e)
        {
            return redirect()->route('feature_flags.index')->withMessage("Could not find feature flag");
        }
    }
}
