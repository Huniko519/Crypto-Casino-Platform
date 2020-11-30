<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    // regular user sign up
    const TYPE_SIGN_UP = 20;

    // game bonuses
    const TYPE_GAME_LOSS = 21;
    const TYPE_GAME_WIN = 22;

    // deposit bonus
    const TYPE_DEPOSIT = 23;

    // referee bonus (who was referred to the casino)
    const TYPE_REFEREE_SIGN_UP = 1;

    // referrer bonuses
    const TYPE_REFERRER_SIGN_UP = 11;
    const TYPE_REFERRER_GAME_BET = 12; // no longer used, but preserved for backward compatibility
    const TYPE_REFERRER_DEPOSIT = 13;
    const TYPE_REFERRER_GAME_LOSS = 14;
    const TYPE_REFERRER_GAME_WIN = 15;
    const TYPE_REFERRER_RAFFLE_TICKET = 16;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }

    public function getTitleAttribute()
    {
        switch ($this->type) {
            case self::TYPE_SIGN_UP:
                return __('Sign up bonus');
                break;

            case self::TYPE_GAME_LOSS:
                return __('Game loss bonus');
                break;

            case self::TYPE_GAME_WIN:
                return __('Game win bonus');
                break;

            case self::TYPE_DEPOSIT:
                return __('Deposit bonus');
                break;

            case self::TYPE_REFEREE_SIGN_UP:
                return __('Referee sign up bonus');
                break;

            case self::TYPE_REFERRER_SIGN_UP:
                return __('Referrer sign up bonus');
                break;

            case self::TYPE_REFERRER_GAME_BET;
                return __('Referrer game bonus');
                break;

            case self::TYPE_REFERRER_GAME_LOSS;
                return __('Referrer game loss bonus');
                break;

            case self::TYPE_REFERRER_GAME_WIN;
                return __('Referrer game win bonus');
                break;

            case self::TYPE_REFERRER_DEPOSIT;
                return __('Referrer deposit bonus');
                break;

            case self::TYPE_REFERRER_RAFFLE_TICKET;
                return __('Referrer raffle ticket bonus');
                break;

            default:
                return __('Bonus');
        }
    }
}
