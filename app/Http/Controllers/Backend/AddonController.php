<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\PackageManager;
use App\Helpers\ReleaseManager;
use App\Http\Controllers\Controller;
use App\Services\ArtisanService;
use App\Services\DotEnvService;
use App\Services\LicenseService;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AddonController extends Controller
{
    public function index(PackageManager $packageManager, ReleaseManager $releaseManager)
    {
        return view('backend.pages.addons.index', [
            'releases' => $releaseManager->getInfo(),
            'packages' => $packageManager->getAll()
        ]);
    }

    /**
     * Disable add-on
     *
     * @param $packageId
     * @param PackageManager $packageManager
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function disable($packageId, PackageManager $packageManager)
    {
        $package = $packageManager->get($packageId);
        if (!$package)
            return back()->withErrors(__('Package ":id" does not exist.', ['id' => $packageId]));

        try {
            if (Storage::disk('local')->put('packages/' . $packageId . '/disabled', '')) {
                //ArtisanService::clearAllCaches();
                return back()->with('success', __('Add-on ":name" successfully disabled.', ['name' => $package->name]));
            } else {
                return back()->withErrors(__('Could not disable the add-on. Please check that storage/app folder is writable.'));
            }
        } catch(\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Enable add-on
     *
     * @param $packageId
     * @param PackageManager $packageManager
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function enable($packageId, PackageManager $packageManager)
    {
        $package = $packageManager->get($packageId);
        if (!$package)
            return back()->withErrors(__('Package ":id" does not exist.', ['id' => $packageId]));

        try {
            if (Storage::disk('local')->delete('packages/' . $packageId . '/disabled')) {
                //ArtisanService::clearAllCaches();
                return back()->with('success', __('Add-on ":name" successfully enabled.', ['name' => $package->name]));
            } else {
                return back()->withErrors(__('Could not enable the add-on. Please check that storage/app folder is writable.'));
            }
        } catch(\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function installShow($packageId, PackageManager $packageManager)
    {
        return view('backend.pages.addons.install', [
            'package' => $package = $packageManager->get($packageId)
        ]);
    }

    public function installRun($packageId, Request $request, LicenseService $licenseService, PackageManager $packageManager, ReleaseManager $releaseManager, DotEnvService $dotEnvService)
    {
        set_time_limit(1800);

        $releases = $releaseManager->getInfo();
        $package = $packageManager->get($packageId);

        if (!$package)
            return back()->withInput()->withErrors(__('Package ":id" does not exist.', ['id' => $packageId]));

        if (!isset($package->hash) || !isset($releases->{'add-ons'}->{$package->id}))
            return back()->withInput()->withErrors(__('Package ":id" can not be installed or upgraded.', ['id' => $packageId]));

        $response = $licenseService->download($request->code, env('LICENSEE_EMAIL'), $package->hash, $releases->{'add-ons'}->{$package->id}->version);

        // if response is not binary (files)
        if (is_object($response))
            return back()->withInput()->withErrors($response->message);

        // save code as env variable
        $env = $dotEnvService->load();
        $env[strtoupper(str_replace('-', '_', $packageId) . '_PURCHASE_CODE')] = $request->code;
        $dotEnvService->save($env);

        // unzip package
        try {
            $zipFileName = 'releases/' . $packageId . '.zip';
            $storage = Storage::disk('local');
            $storage->put($zipFileName, $response);
            $zipFile = Zipper::make($storage->path($zipFileName));
            $zipFile->extractTo(base_path());
            $zipFile->close();
            $storage->delete($zipFileName);
        } catch(\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        // If the package is being installed (hence $package->enabled is FALSE) then
        //  register its service provider and register autoload once again (because currently loaded function is not aware of new classes)
        if (!$package->enabled) {
            spl_autoload_register([new PackageManager(), 'autoload']);
            foreach ($package->providers as $provider) {
                app()->register($provider);
            }
        }

        // run migrations
        ArtisanService::migrateAndSeed();
        // clear cache
        ArtisanService::clearAllCaches();

        return redirect()->route('backend.addons.index')->with('success', __('Add-on ":name" successfully installed or updated. Please check the add-on documentation to see if there are any extra steps required.', ['name' => $package->name]));
    }

    public function changelog($packageId)
    {
        $path = base_path('packages/' . $packageId . '/CHANGELOG.txt');
        return File::exists($path) ?
            '<pre>' . File::get($path) . '</pre>' :
            __('No changelog found.');
    }
}
