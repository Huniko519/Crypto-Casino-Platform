<?php


namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ArtisanService
{
    public static function migrateAndSeed()
    {
        // Forcing Migrations To Run In Production
        Artisan::call('migrate', [
            '--force' => TRUE,
        ]);

        // run seeders
        Artisan::call('db:seed', [
            '--force' => TRUE,
        ]);
    }

    public static function clearAllCaches()
    {
        // clear cached variables and data
        Cache::flush();
        // clear config cache
        Artisan::call('config:clear');
        // clear app cache
        Artisan::call('cache:clear');
        // clear views cache
        Artisan::call('view:clear');
        // clear routes cache
        Artisan::call('route:clear');
    }
}
