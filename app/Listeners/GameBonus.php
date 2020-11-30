<?php

namespace App\Listeners;

use App\Events\GamePlayed;
use App\Models\Bonus;
use App\Services\BonusService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GameBonus
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
     * @param  GamePlayed  $event
     * @return void
     */
    public function handle(GamePlayed $event)
    {
        $user = $event->user;
        $game = $event->game;

        $loss = max(0, $game->bet - $game->win); // net loss
        $win = max(0, $game->win - $game->bet); // net win
        $minLoss = config('settings.bonuses.game.loss_amount_min');
        $minWin = config('settings.bonuses.game.win_amount_min');
        $lossBonusPct = config('settings.bonuses.game.loss_amount_pct');
        $winBonusPct = config('settings.bonuses.game.win_amount_pct');

        // game was lost by user
        if ($loss > 0 && $loss >= $minLoss && $lossBonusPct > 0) {
            BonusService::create($user->account, Bonus::TYPE_GAME_LOSS, $loss * $lossBonusPct / 100);
        // game was won by user
        } else if ($win > 0 && $win >= $minWin && $winBonusPct > 0) {
            BonusService::create($user->account, Bonus::TYPE_GAME_WIN, $win * $winBonusPct / 100);
        }

        // check if user has referrer
        if ($user->referrer) {
            $lossBonusPct = $user->referrer->referrer_game_loss_pct ?: config('settings.bonuses.referral.referrer_game_loss_pct');
            $winBonusPct = $user->referrer->referrer_game_win_pct ?: config('settings.bonuses.referral.referrer_game_win_pct');
            // loss
            if ($loss > 0 && $lossBonusPct > 0) {
                BonusService::create($user->referrer->account, Bonus::TYPE_REFERRER_GAME_LOSS, $loss * $lossBonusPct / 100);
            // win
            } else if ($win > 0 && $winBonusPct > 0) {
                BonusService::create($user->referrer->account, Bonus::TYPE_REFERRER_GAME_WIN, $win * $winBonusPct / 100);
            }
        }
    }
}
