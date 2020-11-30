<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'referrer'], function () {
    Auth::routes(['verify' => config('settings.users.email_verification')]);
});

// Social login
Route::prefix('login')
    ->name('login.')
    ->namespace('Auth')
    ->middleware('guest','social')
    ->group(function () {
        Route::get('{provider}', 'SocialLoginController@redirect');
        Route::get('{provider}/callback', 'SocialLoginController@сallback');
    });

// Pass certain config variables to the client side via variables.js
// read more: https://medium.com/@serhii.matrunchyk/using-laravel-localization-with-javascript-and-vuejs-23064d0c210e
Route::get('js/variables.js', function () {
    $response = Cache::rememberForever('variables.js', function () {
        $settings = [
            'theme'     => config('settings.theme'),
            'format'    => config('settings.format'),
        ];

        $broadcasting = [
            'connections' => [
                'pusher' => [
                    'key' => config('broadcasting.connections.pusher.key'),
                    'options' => [
                        'cluster' => config('broadcasting.connections.pusher.options.cluster')
                    ]
                ]
            ]
        ];

        return 'var cfg = ' . json_encode(['settings' => $settings, 'broadcasting' => $broadcasting]) . ';';
    });
    return $response;
})->name('variables');

// Pass translation strings to the client side via locale.js
// read more: https://medium.com/@serhii.matrunchyk/using-laravel-localization-with-javascript-and-vuejs-23064d0c210e
Route::get('js/locale.js', function () {
    $locale = app()->getLocale();
    $response = Cache::rememberForever('locale-' . $locale . '.js', function () use ($locale) {
        $langStrings = [];

        if ($locale == 'en') {
            $langStrings = include(resource_path('lang/en/app.php'));

            $langStrings = array_combine(
                array_map(function($key) {
                    return 'app.' . $key;
                }, array_keys($langStrings)),
                array_values($langStrings)
            );
        }

        $langFile = resource_path('lang/' . $locale . '.json');
        if (file_exists($langFile)) {
            $langStrings = array_merge($langStrings, json_decode(file_get_contents($langFile), JSON_OBJECT_AS_ARRAY));
        }

        return 'var i18n = ' . json_encode($langStrings) . ';';
    });
    return $response;
})->name('locale');

// Public frontend routes
Route::name('frontend.')
    ->namespace('Frontend')
    ->middleware('referrer')
    ->group(function () {
        Route::get('/', 'PageController@index')->name('index');
        // static pages
        Route::get('page/{slug}', 'PageController@display')->name('static');
        // leaderboard
        Route::get('leaderboard', 'PageController@leaderboard')->name('leaderboard');
        // history
        Route::get('history/recent', 'PageController@recentGames')->name('history.recent');
        Route::get('history/top-wins', 'PageController@topWins')->name('history.top-wins');
        Route::get('history/top-losses', 'PageController@topLosses')->name('history.top-losses');
        Route::get('history/my', 'PageController@myGames')->name('history.my');
        Route::get('history/{game}/verify', 'PageController@verify')->name('history.verify');
        // user profile page (view only)
        Route::get('users/{user}/show', 'UserController@show')->name('users.show');
        // locale
        Route::post('locale/{locale}/remember', 'LocaleController@remember')->name('locale.remember');
    });

// Auth frontend routes
Route::name('frontend.')
    ->namespace('Frontend')
    ->middleware('auth', 'active', 'email_verified', '2fa')
    ->group(function () {
        //user profile and password
        Route::get('user/edit', 'UserController@edit')->name('users.edit');
        Route::put('user/edit', 'UserController@update')->name('users.update');
        Route::get('user/password', 'UserController@editPassword')->name('users.password.edit');
        Route::put('user/password', 'UserController@updatePassword')->name('users.password.update');
        // user account
        Route::get('user/account', 'AccountController@show')->name('account.show');
        // security
        Route::get('user/security', 'SecurityController@index')->name('security.index');
        Route::get('user/security/2fa/enable', 'SecurityController@enable2Fa')->name('security.2fa.enable');
        Route::post('user/security/2fa/enable', 'SecurityController@enable2FaComplete')->name('security.2fa.enable');
        Route::get('user/security/2fa/disable', 'SecurityController@disable2Fa')->name('security.2fa.disable');
        Route::post('user/security/2fa/disable', 'SecurityController@disable2FaComplete')->name('security.2fa.disable');
        // 2FA
        // these routes should not have login prefix, otherwise they will conflict with social login
        Route::get('2fa', 'SecurityController@verifyTotp')->name('login.2fa');
        Route::post('2fa', 'SecurityController@verifyTotpComplete')->name('login.2fa')->middleware('throttle:10,1');
        // referral program
        Route::get('bonuses', 'BonusController@index')->name('bonuses.index');
        // chat
        Route::get('chat', 'ChatMessageController@index')->name('chat.index');
        Route::get('chat/messages/get', 'ChatMessageController@getMessages')->name('chat.messages.get');
        Route::post('chat/messages/send', 'ChatMessageController@sendMessage')->name('chat.messages.send');
    });

// Backend routes
Route::prefix('admin')
    ->name('backend.')
    ->namespace('Backend')
    ->middleware('auth', 'active', 'email_verified', '2fa', 'role:' . App\Models\User::ROLE_ADMIN)
    ->group(function () {
        // admin dashoard
        Route::get('dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('dashboard/games', 'DashboardController@games')->name('dashboard.games');
        Route::get('dashboard/accounts', 'DashboardController@accounts')->name('dashboard.accounts');
        // users management
        Route::resource('users', 'UserController',  ['except' => ['create','store','show']]);
        Route::get('users/{user}/delete', 'UserController@delete')->name('users.delete');
        Route::get('users/{user}/mail', 'UserController@mailShow')->name('users.mail.show');
        Route::post('users/{user}/mail', 'UserController@mailSend')->name('users.mail.send');
        // accounts
        Route::get('accounts', 'AccountController@index')->name('accounts.index');
        Route::get('accounts/{account}/transactions', 'AccountController@transactions')->name('accounts.transactions');
        Route::get('accounts/{account}/debit', 'DebitController@create')->name('accounts.debit');
        Route::post('accounts/{account}/debit', 'DebitController@store')->name('accounts.debit');
        Route::get('accounts/{account}/credit', 'CreditController@create')->name('accounts.credit');
        Route::post('accounts/{account}/credit', 'CreditController@store')->name('accounts.credit');
        // transactions
        Route::resource('transactions', 'TransactionController',  ['only' => ['index']]);
        // games
        Route::resource('games', 'GameController',  ['only' => ['index','show']]);
        // add-ons
        Route::get('add-ons', 'AddonController@index')->name('addons.index');
        Route::post('add-ons/{packageId}/enable', 'AddonController@enable')->name('addons.enable');
        Route::post('add-ons/{packageId}/disable', 'AddonController@disable')->name('addons.disable');
        Route::get('add-ons/{packageId}/install', 'AddonController@installShow')->name('addons.install.show');
        Route::post('add-ons/{packageId}/install', 'AddonController@installRun')->name('addons.install.run');
        Route::get('add-ons/{packageId}/changelog', 'AddonController@changelog')->name('addons.changelog');
        // settings
        Route::get('settings', 'SettingController@index')->name('settings.index');
        Route::post('settings', 'SettingController@update')->name('settings.update');
        // file uploads
        Route::post('files', 'FileController@store')->name('files.store');
        // maintenance
        Route::get('maintenance', 'MaintenanceController@index')->name('maintenance.index');
        Route::post('maintenance/enable', 'MaintenanceController@enable')->name('maintenance.enable');
        Route::post('maintenance/disable', 'MaintenanceController@disable')->name('maintenance.disable');
        Route::post('maintenance/cache/clear', 'MaintenanceController@cache')->name('maintenance.cache');
        Route::post('maintenance/migrate', 'MaintenanceController@migrate')->name('maintenance.migrate');
        Route::post('maintenance/task', 'MaintenanceController@task')->name('maintenance.task');
        Route::post('maintenance/task/run', 'MaintenanceController@runTask')->name('maintenance.task.run');
        Route::post('maintenance/cron', 'MaintenanceController@cron')->name('maintenance.cron');
        Route::get('maintenance/log/view', 'MaintenanceController@viewLog')->name('maintenance.log.view');
        Route::get('maintenance/log/download', 'MaintenanceController@downloadLog')->name('maintenance.log.download');
        Route::get('license', 'LicenseController@index')->name('license.index');
        Route::post('license', 'LicenseController@register')->name('license.register');
    });
