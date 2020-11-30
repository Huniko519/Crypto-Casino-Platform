<?php

namespace Packages\GameSlots\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;

class GameFreeSlots extends Model
{
    protected $fillable = [
        'account_id', 'lines', 'bet', 'quantity'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
