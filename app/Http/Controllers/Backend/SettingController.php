<?php

namespace App\Http\Controllers\Backend;

use App\Services\DotEnvService;
use App\Services\LocaleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $locale = new LocaleService();
        $locales = $locale->locales();
        $schemes = [
            'dark-purple'   => __('Dark purple'),
            'light-blue'    => __('Light blue'),
            'neptune'       => __('Neptune'),
            'cannon-pink'   => __('Cannon pink'),
            'fire-bush'     => __('Fire bush'),
            'emperor'       => __('Emperor')
        ];
        $logLevels = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];
        $separators = [ord('.') => '.', ord(',') => ',', ord(' ') => __('space'), ord(':') => ':', ord(';') => ';', ord('-') => '-'];

        return view('backend.pages.settings', [
            'schemes'       => $schemes,
            'locales'       => $locales,
            'log_levels'    => $logLevels,
            'separators'    => $separators,
        ]);
    }

    public function update(Request $request)
    {
        // merging saved variables (settings) into current env variables
        $dotEnvService = new DotEnvService();
        $env = $dotEnvService->load();
        $env = array_merge($env, $request->except(['_token', 'nonenv']));

        // save settings to .env file
        if (!$dotEnvService->save($env))
            return redirect()->back()->withErrors(__('There was an error while saving the settings.'));

        // clear JS cache
        Cache::forget('variables.js');
        return redirect()->back()->with('success', __('Settings successfully saved.'));
    }
}
