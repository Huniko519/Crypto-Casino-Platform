<?php

return [
	/*
	 * Theme
	 */
	'theme' => env('THEME', 'dark-purple'),

    /**
     * Layout (Boxed of Full-width)
     */
    'layout' => env('LAYOUT', 'boxed'), // boxed / fluid

    /*
     * Number formatting
     */
    'format' => [
        'number' => [
            'decimals' => env('FORMAT_NUMBER_DECIMALS', 2),
            'decimal_point' => env('FORMAT_NUMBER_DECIMAL_POINT', 46), // period
            'thousands_separator' => env('FORMAT_NUMBER_THOUSANDS_SEPARATOR', 44), // comma
        ]
    ],

    'home' => [
        'slider' => json_decode(env('HOME_SLIDER', json_encode([
            'indicators' => TRUE,
            'controls' => TRUE,
            'animation' => 'fade', // supported: slide, fade
            'interval' => 5, // seconds
            'slides' => [
                [
                    'title' => 'Crypto Casino',
                    'subtitle' => 'Fair online gaming platform',
                    'image' => [
                        'url' => '/images/home/slider/crypto-casino-slide1.jpg',
                    ],
                    'link' => [
                        'title' => '',
                        'url' => '',
                        'class' => '',
                    ]
                ],
                [
                    'title' => 'Try your luck',
                    'subtitle' => 'Can you beat other players?',
                    'image' => [
                        'url' => '/images/home/slider/crypto-casino-slide2.jpg',
                    ],
                    'link' => [
                        'title' => 'Leaderboard',
                        'url' => '/leaderboard',
                        'class' => 'btn btn-primary btn-lg',
                    ]
                ]
            ]
        ])))
    ],

    /*
     * Users configuration
     */
    'users' => [
        // require users to verify their email or not
        'email_verification' => env('USERS_EMAIL_VERIFICATION', FALSE),
    ],

    /*
     * Bots configuration
     */
    'bots' => [
        'game' => [
            'frequency' => env('BOTS_PLAY_FREQUENCY', 30), // in minutes
            'count_min' => env('BOTS_SELECT_COUNT_MIN', 1),
            'count_max' => env('BOTS_SELECT_COUNT_MAX', 10),
            'min_bet'   => env('BOTS_MIN_BET'),
            'max_bet'   => env('BOTS_MAX_BET'),
        ],
        'raffle' => [
            'frequency' => env('BOTS_RAFFLE_FREQUENCY', 30), // in minutes
            'count_min' => env('BOTS_RAFFLE_COUNT_MIN', 1),
            'count_max' => env('BOTS_RAFFLE_COUNT_MAX', 2),
            'tickets_min' => env('BOTS_RAFFLE_TICKETS_MIN', 1),
            'tickets_max' => env('BOTS_RAFFLE_TICKETS_MAX', 2),
        ]
    ],

    /*
     * Bonuses
     */
    'bonuses' => [
        'sign_up_credits' => env('BONUSES_SIGN_UP_CREDITS', 1000), // regular user sign up bonus
        'game' => [
            'loss_amount_min' => env('BONUSES_GAME_LOSS_AMOUNT_MIN', 1000), // when loss on a game is greater than X
            'loss_amount_pct' => env('BONUSES_GAME_LOSS_AMOUNT_PCT', 10), // give % of net loss back to user
            'win_amount_min' => env('BONUSES_GAME_WIN_AMOUNT_MIN', 1000), // when win on a game is greater than X
            'win_amount_pct' => env('BONUSES_GAME_WIN_AMOUNT_PCT', 10), // give % of net win back to user
        ],
        'raffle' => [
            'ticket_pct' => env('BONUSES_RAFFLE_TICKET_PCT', 1),
        ],
        'deposit' => [
            'amount_min' => env('BONUSES_DEPOSIT_AMOUNT_MIN', 5000),
            'amount_pct' => env('BONUSES_DEPOSIT_AMOUNT_PCT', 5),
        ],
        'referral' => [
            'referee_sign_up_credits'   => env('BONUSES_REFERRAL_REFEREE_SIGN_UP_CREDITS', 50),
            'referrer_sign_up_credits'  => env('BONUSES_REFERRAL_REFERRER_SIGN_UP_CREDITS', 100),
            'referrer_deposit_pct'      => env('BONUSES_REFERRAL_REFERRER_DEPOSIT_PCT', 10),
            'referrer_game_loss_pct'    => env('BONUSES_REFERRAL_REFERRER_GAME_LOSS_PCT', 10),
            'referrer_game_win_pct'     => env('BONUSES_REFERRAL_REFERRER_GAME_WIN_PCT', 10),
        ]
    ],

	/*
     * Google Tag Manager container ID
     */
	'gtm_container_id' => env('GTM_CONTAINER_ID'),

    /*
     * Google reCaptcha
     */
    'recaptcha' => [
        'public_key'    => env('RECAPTCHA_PUBLIC_KEY'),
        'secret_key'    => env('RECAPTCHA_SECRET_KEY'),
    ],

    'backend' => [
        'dashboard' => [
            'cache_time' => env('BACKEND_DASHBOARD_CACHE_TIME', 60) // in minutes
        ]
    ]
];
