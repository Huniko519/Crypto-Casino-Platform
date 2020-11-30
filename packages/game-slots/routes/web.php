<?php

// game routes
Route::name('games.')
    ->namespace('Packages\GameSlots\Http\Controllers\Frontend')
    ->middleware(['web', 'auth', 'active', 'email_verified', '2fa']) // it's important to add web middleware as it's not automatically added for packages routes
    ->group(function () {
        // show initial game screen
        Route::get('games/slots', 'GameSlotsController@show')->name('slots.show');
        // play game (spin the reels)
        Route::post('games/slots/play', 'GameSlotsController@play')->name('slots.play')->middleware('concurrent');
    });


Route::prefix('admin')
    ->name('backend.games.slots.')
    ->namespace('Packages\GameSlots\Http\Controllers\Backend')
    ->middleware('web', 'auth', 'active', 'email_verified', 'role:' . App\Models\User::ROLE_ADMIN)
    ->group(function () {
        Route::post('games/slots/files', 'GameSlotsController@files')->name('files');
    });
