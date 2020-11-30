<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CommandManager;
use App\Helpers\PackageManager;
use App\Helpers\ReleaseManager;
use App\Http\Controllers\Controller;
use App\Services\ArtisanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    private $log = 'laravel.log';

    public function index(CommandManager $commandManager, ReleaseManager $releaseManager, PackageManager $packageManager)
    {
        $releases = $releaseManager->getInfo();
        $outdatedAddons = array_filter($packageManager->getEnabled(), function ($package) use ($releases) {
            return isset($releases->{'add-ons'}->{$package->id}) &&
                version_compare($releases->{'add-ons'}->{$package->id}->version, $package->version, '>');
        });

        return view('backend.pages.maintenance', [
            'system_info'       => 'PHP ' . PHP_VERSION . ' ' . php_uname(),
            'log_size'          => Storage::disk('logs')->exists($this->log) ? Storage::disk('logs')->size($this->log) / 1048576 : 0,
            'commands'          => $commandManager->all(),
            'releases'          => $releaseManager->getInfo(),
            'outdated_addons'   => $outdatedAddons,
        ]);
    }

    public function viewLog()
    {
        return Storage::disk('logs')->exists($this->log) ?
            '<pre>' . Storage::disk('logs')->get($this->log) . '</pre>' :
            __('No application log file found.');
    }

    public function downloadLog()
    {
        return Storage::disk('logs')->exists($this->log) ? Storage::disk('logs')->download($this->log) : __('No application log file found.');
    }

    /**
     * Enable maintenance mode
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request)
    {
        Artisan::call('down', [
            '--message' => $request->message
        ]);

        return $this->success();
    }

    /**
     * Disable maintenance mode
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable()
    {
        Artisan::call('up');
        return $this->success();
    }

    /**
     * Clear all caches
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cache()
    {
        set_time_limit(1800);
        ArtisanService::clearAllCaches();
        return $this->success();
    }

    /**
     * Run migrations
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function migrate()
    {
        set_time_limit(1800);
        ArtisanService::migrateAndSeed();
        return $this->success();
    }

    /**
     * Run cron
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cron()
    {
        set_time_limit(1800);
        Artisan::call('schedule:run');
        return $this->success();
    }

    public function task(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->get($request->command);

        if (!$command)
            return redirect()->route('backend.maintenance.index')->withErrors(__('Such task does not exist.'));

        return view('backend.pages.tasks.run', [
            'command'   => $command,
            'last_run'  => $commandManager->getLastRun($command['class'])
        ]);
    }

    public function runTask(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->get($request->command);

        if (!$command)
            return redirect()->route('backend.maintenance.index')->withErrors(__('Such task does not exist.'));

        set_time_limit(1800);

        // ensure only supported arguments are passed
        $params = $request->only(array_column($command['arguments'], 'id'));

        // execute artisan command
        Artisan::call($command['signature'], $params);

        return $this->success();
    }

    private function success()
    {
        return redirect()->route('backend.maintenance.index')->with('success', __('Operation performed successfully.'));
    }
}
