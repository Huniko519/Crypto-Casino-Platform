<?php

// Installation routes
Route::prefix('install')
    ->name('install.')
    ->namespace('Packages\Installer\Http\Controllers')
    ->middleware(
        'web',
        Packages\Installer\Http\Middleware\RedirectIfInstalled::class
    )
    ->group(function () {
        // view form
        Route::get('{step}', 'InstallerController@view')->where('step', '(1|2|3|4)')->name('view');
        // process form submission
        Route::post('{step}/process', 'InstallerController@process')->where('step', '(1|2|3)')->name('process');
    });