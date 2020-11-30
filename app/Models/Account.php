<?php

namespace App\Models;

use App\Models\Formatters\Formatter;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use Formatter;

    protected $formats = [
        'balance' => 'decimal',
    ];

    /**
     * The attributes that should be hidden from JSON output.
     *
     * @var array
     */
    protected $hidden = [
        'code'
    ];

    /**
     * This format will be used when the model is serialized to an array or JSON.
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    public function bonuses(array $types = [])
    {
        return $this->hasMany(Bonus::class)
            ->when(!empty($types), function ($query) use ($types) {
                $query->whereIn('type', $types);
            });
    }

    /**
     * Return relationship by class name.
     * It's useful for retrieving deposits / withdrawals, which can not be referenced directly, because they are implemented in a separate package.
     *
     * @param $class
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function related($class)
    {
        return $this->hasMany($class);
    }

    public function games()
    {
        return $this->hasMany(Game::class)->where('status', Game::STATUS_COMPLETED);
    }
}
