<?php

namespace App\Listeners;

use App\Models\Bonus;
use App\Services\BonusService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignUpBonus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        // sign up bonus
        BonusService::create($user->account, Bonus::TYPE_SIGN_UP, config('settings.bonuses.sign_up_credits'));

        // check if user has referrer
        if ($user->referrer) {
            // referee bonus
            BonusService::create(
                $user->account,
                Bonus::TYPE_REFEREE_SIGN_UP,
                $user->referrer->referee_sign_up_credits ?: config('settings.bonuses.referral.referee_sign_up_credits')
            );

            // referrer bonus
            BonusService::create(
                $user->referrer->account,
                Bonus::TYPE_REFERRER_SIGN_UP,
                $user->referrer->referrer_sign_up_credits ?: config('settings.bonuses.referral.referrer_sign_up_credits')
            );
        }
    }
}
