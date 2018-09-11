<?php

namespace FriendsOfCat\LaravelFeatureFlags;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeatureFlagSettingsController extends Controller
{

    public function getSettings(ExportImportRepository $repo)
    {
        try {
            $settings = FeatureFlag::all();
            $token = csrf_token();
            $exports = $repo->export();
            return view('laravel-feature-flag::settings', compact('settings', 'token', 'exports'));
        } catch (\Exception $e) {
            \Log::error("Error getting settings");
            \Log::error($e);

            return redirect("/")->withMessage("Error visiting Settings page");
        }
    }

    public function create(Request $request)
    {
        $flag = new FeatureFlag();

        return view('laravel-feature-flag::create', compact('flag'));
    }

    public function import(Request $request, ExportImportRepository $repo)
    {
        try {
            $repo->import(json_decode($request->features, true));

            return redirect()->route('laravel-feature-flag.index')->withMessage("Created and or Updated Features");
        } catch (\Exception $e) {
            \Log::error("Error importing feature flags");
            \Log::error($e);
            return redirect()->route('laravel-feature-flag.index')->withMessage("Could not import feature flags");
        }
    }

    public function store(Request $request)
    {
        try {
            $flag = new FeatureFlag();
            $flag->key = $request->input('key');
            $flag->variants = $request->input('variants');
            $flag->save();

            return redirect()->route('laravel-feature-flag.index')->withMessage("Created Feature");
        } catch (\Exception $e) {
            return redirect()->route('laravel-feature-flag.index')->withMessage("Could not find feature flag");
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $flag = FeatureFlag::findOrFail($id);

            return view('laravel-feature-flag::edit', compact('flag'));
        } catch (\Exception $e) {
            return redirect()->route('laravel-feature-flag.index')->withMessage("Could not find feature flag");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $flag = FeatureFlag::findOrFail($id);

            $flag->variants = ($request->input('variants')) ? json_decode($request->input('variants'), true) : null;
            $flag->save();

            return redirect()->route(
                'laravel-feature-flag.index'
            )->withMessage(sprintf("Feature Flag Updated %d", $id));
        } catch (\Exception $e) {
            return redirect()->route('laravel-feature-flag.index')->withMessage("Could not find feature flag");
        }
    }

    public function destroy($id)
    {
        try {
            $flag = FeatureFlag::findOrFail($id);

            $flag->delete();

            return redirect()->route(
                'laravel-feature-flag.index'
            )->withMessage(sprintf("Feature Flag Updated %d", $id));
        } catch (\Exception $e) {
            return redirect()->route('laravel-feature-flag.index')
                ->withMessage("Could not find feature flag");
        }
    }
}
