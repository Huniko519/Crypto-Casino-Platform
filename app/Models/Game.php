<?php

namespace App\Models;

use App\Models\Formatters\Formatter;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use Formatter;

    const STATUS_CREATED = -1;
    const STATUS_STARTED = 0;
    const STATUS_COMPLETED = 1;

    protected $formats = [
        'bet'               => 'decimal',
        'win'               => 'decimal',
        'net_win'           => 'decimal',
        'return_to_player'  => 'percentage',
        'house_edge'        => 'percentage',
    ];

    /**
     * The attributes that should be hidden from JSON output.
     *
     * @var array
     */
    protected $hidden = [
        'gameable_type', 'secret', 'server_seed'
    ];

    /**
     * This format will be used when the model is serialized to an array or JSON.
     *
     * @var array
     */
    protected $casts = [
        'bet' => 'float',
        'win' => 'float',
    ];

    protected $appends = ['server_hash'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function gameable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }

    /**
     * Override original model's delete() method to delete (cascade) polymorphic relationships
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->transaction()->delete(); // delete linked transaction
        $this->gameable()->delete(); // delete specific game, e.g. Dice, Roulette
        return parent::delete();
    }

    public function getServerHashAttribute()
    {
        return $this->secret != '' && $this->server_seed != '' ? hash_hmac('sha256', $this->secret, $this->server_seed) : '';
    }

    public function getClientHashAttribute()
    {
        return $this->secret != '' && $this->server_seed != '' && $this->client_seed ? hash_hmac('sha256', $this->secret . $this->server_seed, $this->client_seed) : '';
    }

    public function getShiftValueAttribute()
    {
        return $this->secret != '' && $this->server_seed != '' && $this->client_seed ? hexdec(substr($this->client_hash, -5)) : 0;
    }

    public function getTitleAttribute()
    {
        return $this->gameable->title ?: __('app.game_' . class_basename(get_class($this->gameable)));
    }

    public function getNetWinAttribute()
    {
        return $this->win - $this->bet;
    }
}
