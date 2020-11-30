<?php

namespace App\Models;

use App\Models\Formatters\Formatter;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use Formatter;

    protected $formats = [
        'amount' => 'decimal',
        'balance' => 'decimal',
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Getter for title attribute
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        // add whitespaces to games titles, e.g. EuropeanRoulette => European Roulette
        return __( preg_replace('/([a-z])([A-Z])/s','$1 $2', class_basename($this->transactionable_type)));
    }
}
