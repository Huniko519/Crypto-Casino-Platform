<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    /**
     * Social login providers
     * More can be found at https://socialiteproviders.netlify.com/
     */
    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => '/login/facebook/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'twitter' => [
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => '/login/twitter/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => '/login/google/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'linkedin' => [
        'client_id'     => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect'      => '/login/linkedin/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'yahoo' => [
        'client_id'     => env('YAHOO_CLIENT_ID'),
        'client_secret' => env('YAHOO_CLIENT_SECRET'),
        'redirect'      => '/login/yahoo/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'coinbase' => [
        'client_id'     => env('COINBASE_CLIENT_ID'),
        'client_secret' => env('COINBASE_CLIENT_SECRET'),
        'redirect'      => '/login/coinbase/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    // https://steemit.com/utopian-io/@ikidnapmyself/steem-oauth2-provider-for-laravel-socialite
    'steem' => [
        'client_id'     => env('STEEM_CLIENT_ID'),
        'client_secret' => env('STEEM_CLIENT_SECRET'),
        'redirect'      => '/login/steem/callback', // If the redirect option contains a relative path, it will automatically be resolved to a fully qualified URL.
    ],

    'login_providers' => [
        'facebook' => [
            'icon' => 'fab fa-facebook-f'
        ],
        'twitter' => [
            'icon' => 'fab fa-twitter'
        ],
        'google' => [
            'icon' => 'fab fa-google'
        ],
        'linkedin' => [
            'icon' => 'fab fa-linkedin-in'
        ],
        'yahoo' => [
            'icon' => 'fab fa-yahoo'
        ],
        'coinbase' => [
            'icon' => 'fas fa-cubes'
        ],
        'steem' => [
            'icon' => 'fas fa-cubes'
        ],
    ]
];
